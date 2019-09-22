<?php

use App\Services\MKMService;
use Illuminate\Database\Seeder;
use \App\File;
use \App\Repositories\RaritiesRepository;


class EditionsTableSeeder extends Seeder
{
    private $raritiesRepository;

    private $colors = array('W' => 1, 'U' => 2, 'B' => 3, 'R' => 4, 'G' => 5);
//    private $colors = array('White' => 1, 'Blue' => 2, 'Black' => 3, 'Red' => 4, 'Green' => 5);
    private $rarities;
    private $sets_to_add = array('expansion', 'core', 'masters', 'draft_innovation', 'promo', 'masterpiece', 'funny');//
    private $boosters_in = array('core', 'expansion', 'masters', 'draft_innovation');
    private $types = array();
    private $supertypes = array();
    private $planeswalkers = array();
    private $actual_set;
    private $actual_set_id;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->raritiesRepository = new RaritiesRepository();
        $this->rarities = $this->raritiesRepository->getRaritiesToShort();
        $sets = json_decode(file_get_contents('https://api.scryfall.com/sets'))->data;

        $sets = array_reverse($sets);

        foreach ($sets as $set) {
            $this->actual_set = $set;
            $this->addSet($set);
        }
    }

    /**
     * Add complete edition with product as booster and booster box
     *
     * @param $set
     */
    private function addSet($set)
    {

        // if is digital -> skip
        if ($set->digital)
            return;

        // if is tokens -> skip
        if (strpos($set->name, 'Tokens') != 0)
            return;

        // if is not to be added(by set type) -> skip
        if (!in_array($set->set_type, $this->sets_to_add))
            return;

        // output something
        echo "adding " . $set->name . " edition.\n";

        // solving problem with code of conflux
        if ($set->code == "con")
            $set->code = "confl";

        // adding edition to DB
        $this->actual_set_id = DB::table('editions')->insertGetId([
            'name' => $set->name,
            'cards_count' => $set->card_count,
            'sign' => $set->code,
            'type' => $set->set_type,
            'release_date' => $set->released_at,
        ]);

        // if set has boosters -> adding products (booster, booster Box)
        if (in_array($set->set_type, $this->boosters_in)) {

            //adding product, booster and 36 boosters to stock
            $booster_id = DB::table('products')->insertGetId([
                'name' => $set->name . " booster",
                'categoryId' => 2,
                'base_price' => '75',
                'release_date' => $set->released_at,
            ]);
            DB::table('boosters')->insert([
                'product_id' => $booster_id,
                'edition_id' => $this->actual_set_id,
                'cards' => 15,
            ]);
            DB::table('stocks')->insert([
                'product_id' => $booster_id,
                'initial_price' => '75',
                'price' => '75',
                'quantity' => 36,
            ]);

            //adding product, booster box and 1 booster box to stock
            $booster_box_id = DB::table('products')->insertGetId([
                'name' => $set->name . " booster box",
                'categoryId' => 3,
                'base_price' => '2200',
                'release_date' => $set->released_at,
            ]);

            DB::table('booster_boxes')->insert([
                'product_id' => $booster_box_id,
                'edition_id' => $this->actual_set_id,
                'boosters' => 36,
                //'promo'=>nullable,
            ]);

            DB::table('stocks')->insert([
                'product_id' => $booster_box_id,
                'initial_price' => '2200',
                'price' => '2200',
                'quantity' => 1,
            ]);
        }

        // getting url of edition
        $url = $set->search_uri;

        // adding all cards from edition
        //try {
        $this->addCards($url);
        //} catch (Exception $e) {
        //  var_dump($e);
        //}
    }

    /**
     * Adding all cards from edition
     *
     * @param $url
     * @throws Exception
     * when problem with getting data from server
     */
    private function addCards($url)
    {
        //getting cards from set
        try {
            $result = json_decode(file_get_contents($url));
            $cards = $result->data;
        } catch (Exception $e) {
            throw new \Exception($e);
        }

        // adding each card
        foreach ($cards as $card) {
            //if card exist only in foil version, we add directly foil card
            $foil = ($card->foil && !$card->nonfoil) ? true : false;


            $this->addCard($card, $foil);

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
        $img_path =
            "image/" .
            $this->actual_set->code .
            "/" .
            str_replace(':', '',
                str_replace('"', '',
                    preg_replace('/\s/', '',
                        strtok(
                            strtok($card->name, '//'),
                            '?')
                    ))) .
            ".jpg";

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

            // saving path to DB
            DB::table('images')->insert([
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
        if (!file_exists(storage_path("app/public/image/" . $this->actual_set->code))) {

            Storage::makeDirectory("app/public/image/" . $this->actual_set->code, 1777, true);
        }

        // some output (downloading images takes some time)
        echo("getting image for " . $card->name . " from " . $this->actual_set->code . "\n");

        // downloading image
        $contents = file_get_contents($url);

        // saving image
        file_put_contents(storage_path("app/public/" . $img_path), $contents);

        // saving path to DB
        DB::table('images')->insert([
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


        DB::table('prices')->insert([
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
        $item_id = DB::table('products')->insertGetId([
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
        if(strpos($card->collector_number, '★')) {
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
        DB::table('cards')->insert([
            'id' => $item_id,
            'edition_id' => $this->actual_set_id,
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
                DB::table('card_color')->insert([
                    'card_id' => $item_id,
                    'color_id' => $this->colors[$color],
                ]);

        //should add more info about cards as types , supertypes for searching features


        // if we didnt added foil card ( called from upside when foil=true and nonfoil=false, or called recursively)
        if (!$foil && $card->foil)
            // recursively calling to add foil one
            $this->addCard($card, true);
    }
}
