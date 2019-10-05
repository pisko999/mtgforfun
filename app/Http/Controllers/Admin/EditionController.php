<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\EditionGetRequest;
use App\Repositories\CardRepositoryInterface;
use App\Repositories\EditionRepositoryInterface;
use App\Repositories\RaritiesRepository;
use App\Http\Controllers\Controller;

class EditionController extends Controller
{

    protected $editionRepository;
    protected $cardRepository;
    protected $edition;
    private $colors = array('W' => 1, 'U' => 2, 'B' => 3, 'R' => 4, 'G' => 5);
    private $rarities;
    private $raritiesRepository;


    public function __construct(EditionRepositoryInterface $editionRepository,
                                CardRepositoryInterface $cardRepository,
                                RaritiesRepository $raritiesRepository)
    {
        $this->editionRepository = $editionRepository;
        $this->cardRepository = $cardRepository;
        $this->raritiesRepository = $raritiesRepository;
        $this->rarities = $this->raritiesRepository->getRaritiesToShort();
        $this->middleware('auth');
        $this->middleware('admin');

    }

    public function editionCheckGet()
    {
        $editions = $this->editionRepository->getArrayForSelect();
        $r = 'admin.EditionCheckPost';
        return view('admin.editionGet', compact('editions', 'r'));
    }

    public function editionCheckPost(EditionGetRequest $request)
    {

        $edition = $this->editionRepository->getById($request->edition);
        if ($edition == null)
            return abort(404);
        $this->edition = $edition;

        $set = json_decode(file_get_contents('https://api.scryfall.com/sets/' . $edition->sign));
        try {
            $this->addCards($set->search_uri, $edition);
        } catch (\Exception $e) {
            \Debugbar::info($e);
        }


        $editions = $this->editionRepository->getArrayForSelect();
        $r = 'admin.EditionCheckPost';

        return view('admin.editionGet', compact('editions', 'r'));
    }

    private function addCards($url)
    {
        //getting cards from set
        try {
            $result = json_decode(file_get_contents($url));
            $cards = $result->data;
        } catch (Exception $e) {
            throw new \Exception($e);
        }

        $localCards = $this->cardRepository->getCardsByEditionGet($this->edition->id);
//\Debugbar::info($localCards[0]);
        // adding each card
        foreach ($cards as $card) {
            //\Debugbar::info($card);
            //if card dont exist
            $n = $localCards->filter(function ($e) use ($card) {
                return $e->product->name == $card->name && $e->number == $card->collector_number && $e->product->lang == $card->lang;
            });
            //\Debugbar::info($n);

            if ($n->count() == 0) {

                //if card exist only in foil version, we add directly foil card
                $foil = ($card->foil && !$card->nonfoil) ? true : false;


                $this->addCard($card, $foil);
            } //if exist
            else {

                foreach ($n as $localCard) {
                    //if dont have image
                    //\Debugbar::info($n);
                    //\Debugbar::info($localCard->product->image);
                    if ($localCard->product->image == null) {
                        $this->addImage($card, $localCard->id);
                    } elseif (!file_exists(storage_path($localCard->product->image->path))) {
                        $localCard->product->image()->delete();
                        $this->addImage($card, $localCard->id);
                    }
                }
            }
        }

        // if there are more pages of cards ( server distribute max 175 results)
        // calling recursively function itself
        if ($result->has_more)
            $this->addCards($result->next_page);

    }

    /**
     * get base_price of card
     * deppends on if it is foil
     * @param $card
     * @param $foil
     *
     * @return integer
     */
    private function getBasePrice($card, $foil)
    {
        $price = ceil(
            ($foil && $card->nonfoil) ?
                ($card->prices->usd_foil * 22) :
                (isset($card->prices->eur) ?
                    ($card->prices->eur * 25) :
                    (isset($card->prices->usd) ?
                        ($card->prices->usd * 22) :
                        0)));
        $price *= 1.2;
        if ($price < 3)
            $price = 3;
        return $price;
    }

    /**
     * get image path
     * @param $card
     * @return string
     */
    private function getImagePath($card)
    {
        $num = '';
        if ($card->set == "eld" && $card->collector_number > 249)
            $num .= "-" . $card->collector_number;
        $lang = '';
        echo $card->lang;
        if ($card->lang != "en")
            $lang .= "-" . $card->lang;

        $img_path =
            "image/" .
            $this->edition->sign .
            "/" .
            str_replace(':', '',
                str_replace('"', '',
                    preg_replace('/\s/', '',
                        strtok(
                            strtok($card->name, '//'),
                            '?')
                    ))) .
            $num .
            $lang .
            ".jpg";
        //echo $img_path;
        return $img_path;
    }

    /**
     * Add image for card
     * @param $card
     * @param $item_id
     */
    private function addImage($card, $item_id)
    {
        // create image name and path from card name
        $img_path = $this->getImagePath($card);

        // if image with given name exists, just add to db and return
        if (file_exists(storage_path('app/public/' . $img_path))) {
//\Debugbar::info($img_path);
            // saving path to DB
            \DB::table('images')->insert([
                'alt' => $card->name,
                'path' => $img_path,
                'product_id' => $item_id
            ]);

            return;
        }

        // if image not exists, we have to download it and save

        //if card have more faces, url of image is elsewhere and we saving just front side for now
        if (isset($card->image_uris))
            $url = $card->image_uris->normal;
        else
            $url = $card->card_faces[0]->image_uris->normal;

        // if directory with setcode doesnt exists, we have to create it
        if (!file_exists(storage_path("app/public/image/" . $this->edition->sign))) {

            \Storage::makeDirectory("public/image/" . $this->edition->sign, 0755, true);
        }

        // some output (downloading images takes some time)
        echo("getting image for " . $card->name . " from " . $this->edition->sign . "\n");

        // downloading image
        $contents = file_get_contents($url);

        // saving image
        file_put_contents(storage_path("app/public/" . $img_path), $contents);

        // saving path to DB
        \DB::table('images')->insert([
            'alt' => $card->name,
            'path' => $img_path,
            'product_id' => $item_id
        ]);
    }

    /**
     * saving base prices to db
     *
     * @param $price
     * @param $item_id
     */
    private function addPrices($price, $item_id)
    {


        \DB::table('prices')->insert([
            'product_id' => $item_id,
            'PO' => ceil($price * 0.3),
            'PL' => ceil($price * 0.4),
            'LP' => ceil($price * 0.6),
            'GD' => ceil($price * 0.7),
            'EX' => ceil($price * 0.8),
            'NM' => $price,
            'MT' => $price,
        ]);
    }

    /**
     * Adding exact card
     *
     * @param $card
     * @param $foil
     */
    private function addCard($card, $foil)
    {

        // checking if card has released_at date
        if (!isset($card->release_at))
            $card->release_at = "";

        // getting base_price for card
        $price = $this->getBasePrice($card, $foil);

        //add card to product table
        $item_id = \DB::table('products')->insertGetId([
            'name' => $card->name,
            'categoryId' => 1,
            'lang' => $card->lang,
            'base_price' => $price,
            'release_date' => $card->release_at,
        ]);

        // add image for card
        $this->addImage($card, $item_id);


        //add price
        $this->addPrices($price, $item_id);


        // some last checks for missing info from server (no all card have those)
        if (!isset($card->flavor_text))
            $card->flavor_text = "";

        if (!isset($card->mana_cost))
            $card->mana_cost = "";

        if (!isset($card->oracle_text))
            $card->oracle_text = "";

        if (!isset($card->collector_number))
            $card->collector_number = 0;

        $promo = '';
        //var_dump($card);
        if (strpos($card->collector_number, '★')) {
            $card->collector_number = str_replace('★', '', $card->collector_number);
            $promo = '*';
        }
        if (!is_numeric($card->collector_number)) {


            $s = $card->collector_number;
            for ($i = 0; $i < strlen($card->collector_number); $i++) {
                $c = $card->collector_number[$i];
                if (!is_numeric($c)) {
                    $promo .= $c;
                    $s = str_replace($c, '', $s);
                    //echo  ' '.$card->collector_number[$i] ;//. $s . $c . strpos($s, $c);
                }
            }
            $card->collector_number = $s;
        }

        //remove {} from manacost
        $to_replace = array("{", "}");
        $card->mana_cost = str_replace($to_replace, "", $card->mana_cost);

        // adding card in the end to DB
        \DB::table('cards')->insert([
            'id' => $item_id,
            'edition_id' => $this->edition->id,
            'rarity' => $this->rarities[$card->rarity],
            'number' => $card->collector_number,
            'promo' => $promo,
            'mana_cost' => $card->mana_cost,
            'text' => $card->oracle_text,
            'flavor' => $card->flavor_text,
            'foil' => $foil,
            'exists_foil' => $card->foil,
            'exists_nonfoil' => $card->nonfoil,
        ]);

        // adding colors of card for searching
        if (isset($card->colors))
            foreach ($card->colors as $color)
                \DB::table('card_color')->insert([
                    'card_id' => $item_id,
                    'color_id' => $this->colors[$color],
                ]);

        //should add more info about cards as types , supertypes for searching features


        // if we didnt added foil card ( called from upside when foil=true and nonfoil=false, or called recursively)
        if (!$foil && $card->foil)
            // recursively calling to add foil one
            $this->addCard($card, true);
    }

    public function editionRemoveGet()
    {
        $editions = $this->editionRepository->getArrayForSelect();
        $r = 'admin.EditionRemovePost';
        return view('admin.editionGet', compact('editions', 'r'));
    }

    public function editionRemovePost(EditionGetRequest $request)
    {

        $edition = $this->editionRepository->getById($request->edition);
        if ($edition == null)
            return abort(404);
       // try {
        foreach ($edition->cards as $card) {
            if ($card->product->stock->count() > 0) {
                if ($card->product->stock->items->count() > 0)
                    foreach ($card->product->stock->items as $item)
                        $item->delete();

                if ($card->product->stock->image != null)
                    $card->product->stock->image->delete();

                $card->product->stock->delete();
            }

            if ($card->product->image != null) {
                \Debugbar::info($card->product->image);
                $card->product->image()->delete();
            }

            if ($card->product->price != null)
                $card->product->price()->delete();

            $p = $card->product;

            $card->colors()->detach();
            $card->subtypes()->detach();
            $card->types()->detach();
            $card->creature()->delete();
            $card->planeswalker()->delete();
            $card->delete();
            $p->delete();

        }

        if ($edition->boosters->count() > 0)
            foreach ($edition->booster as $b)
                $b->delete();

        if ($edition->booster_boxes != null)

                $edition->booster_boxes->delete();

        $edition->delete();
        //}        catch (\Exception $e){}
        return $this->editionRemoveGet();
    }
}
