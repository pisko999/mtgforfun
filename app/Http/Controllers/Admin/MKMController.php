<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GetMKMSinglesRequest;
use App\Http\Requests\SetMKMProductIdRequest;
use App\Repositories\BoosterRepositoryInterface;
use App\Repositories\CardRepositoryInterface;
use App\Repositories\CommandRepositoryInterface;
use App\Repositories\EditionRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\StockRepositoryInterface;
use App\Services\MKMService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use mysql_xdevapi\Exception;

class MKMController extends Controller
{
    protected $editionRepository;
    protected $cardRepository;
    protected $stockRepository;

    public function __construct(
        EditionRepositoryInterface $editionRepository,
        CardRepositoryInterface $cardRepository,
        StockRepositoryInterface $stockRepository
    )
    {
        $this->editionRepository = $editionRepository;
        $this->cardRepository = $cardRepository;
        $this->stockRepository = $stockRepository;
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function addEditionSelect()
    {
        $editions = $this->editionRepository->getArrayForSelect();

        return view('admin.MKMAddEditionSelect', compact('editions'));
    }

    public function addEdition($id)
    {
        //$mkm = new MKMService();
        //$stock = $this->stockRepository->getAll();

        /*
        $message = array();
        $i = 0;
        foreach ($stock as $s) {
            if ($s->product->idProductMKM == null)
                continue;


            if ($s->quantity == 0)
                continue;

            $mkmproduct = $mkm->getProduct($s->product->idProductMKM);
            //\Debugbar::info($mkmproduct);
            if ($s->product->card != null) {
                if ($s->product->card->promo != '')
                    continue;
                if ($s->language != 'EN')
                    continue;

                $q = $s->quantity > 30 ? 30 : $s->quantity;

                if ($s->product->card->foil)
                    $s->price = $this->getPrice($mkmproduct->product->priceGuide->TRENDFOIL);
                else
                    $s->price = $this->getPrice($mkmproduct->product->priceGuide->TREND);

                $answer = $mkm->addToStock($s->product->idProductMKM, $q, $s->price / 25.5, $s->state, $s->lang, "", $s->product->card->foil);
                \Debugbar::info($answer);
//
//                    if (!isset($answer->inserted[0]->error))
//                        $s->idArticleMKM = $answer->inserted[0]->idArticle->idArticle;
//                    $s->save();
            }
            $i++;
            if ($i > 15)
                break;
        }
*/

        $edition = $this->editionRepository->getById($id);

        $nonfoils = $this->cardRepository->getCardsByEditionAndFoilWithProductAndStock($id, 0);
        $foils = $this->cardRepository->getCardsByEditionAndFoilWithProductAndStock($id, 1);

        $allCards[0] = $nonfoils;
        $allCards[1] = $foils;

        /*
        foreach ($cards as $card)
        {
            \Debugbar::info($card->product);
        }
        */
//$onlineCards = $mkm->getSingles($edition->idExpansionMKM);
//\Debugbar::info($mkm->getSingles($edition->idExpansionMKM)->single[0]);
        return view('admin.MKMAddEdition', compact('edition', 'allCards'));
    }

    public function CheckProductIds($id)
    {
        $mkm = new MKMService();
        $edition = $this->editionRepository->getByIdWithCardsAndProducts($id);
        $cards = $edition->cards;
        \Debugbar::info($cards);

        $expansionMKM = null;
        if (Storage::has('public/mkm/' . $id . '.json'))
            $expansionMKM = json_decode(Storage::get('public/mkm/' . $id . '.json'));
        else {
            $expansionMKM = $mkm->getSingles($edition->idExpansionMKM);
            Storage::put('public/mkm/' . $id . '.json', json_encode($expansionMKM));
        }

        $singles = $expansionMKM->single;
        \Debugbar::info($cards);

        foreach ($singles as $single) {
            \Debugbar::info($single);
            $local = $cards->filter(function ($l) use ($single){return ($l->number == $single->number);});
            foreach ($local as $l)
                if($l->product->idProductMKM == null) {
                    $l->product->idProductMKM = $single->idProduct;
                    $l->product->save();
                }
        }

        //return view('home');
        return redirect()->back();
    }

    public function checkCard($id)
    {
        $this->checkCardPrivate($id);

        //return view("home");
        return redirect()->back();

    }

    public function checkCardApi(Request $request){
        return $this->checkCardPrivate($request->id);
    }

    /*
     * return
     * 1 success
     * -1 no product id from MKM
     * -2 no stock
     */
    private function checkCardPrivate($id){
        $card = $this->cardRepository->getByIdWithProductAndStock($id);

        $product = $card->product;
        $stock = $product->stock;
        if ($product->idProductMKM == null)
            return -1;
        if (count($stock) == 0)
            return -2;
        foreach ($stock as $item) {

            if ($item->idArticleMKM == null)
                $item->addToMKM();
            else
                $item->checkOnMKM();

        }

        return 1;

    }

    private function getPrice($price)
    {
        $p = $price * 25.5 ;
        if ($p % 10 == 0)
            $p++;

        if ($p > 75 || ($p > 20 && $p % 10 > 5))
            $p = (ceil($p / 10) * 10) - 1;
        elseif ($p > 20 && $p % 10 <= 5)
            $p = (ceil($p / 10) * 10) - 5;
        elseif ($p > 15)
            $p = 19;
        elseif ($p > 12)
            $p = 15;
        elseif ($p > 9)
            $p = 12;
        elseif ($p > 7)
            $p = 9;
        elseif ($p > 5)
            $p = 5;
        elseif ($p < 4)
            $p = 4;

        return $p;
    }

    public function connect()
    {

        $mkm = new MKMService();

        $answer = $mkm->getGames();
        //$answer = $mkm->getProductList();

        //$answer = $conn->call("account");
        //\Debugbar::info($answer);
        //$answer = $mkm->getSingles(1);
        //\Debugbar::info($answer->single[15]);

        //$answer = $mkm->getStock();
        //$articles = $answer->article;
        //foreach ($articles as $article) {
        //    $mkm->decreaseStock($article->idArticle, $article->count);
        //}
        //$answer = $mkm->getProductList();
        //$answer = $mkm->getProduct(372131);
        \Debugbar::info($answer);
        //Storage::put('public/mkm/productlist.gz', base64_decode($answer->productsfile));

        //$answer = $mkm->getExpansions();
        //\Debugbar::info($answer);

        /*

        $productList = base64_decode($answer->productsfile);
        $filename = 'productlist.csv.gz';
        $file1 = fopen($filename,'wb');
        fwrite($file1,$productList);
        //Storage::put($filename, $productList);


// Raising this value may increase performance
        $buffer_size = 4096; // read 4kb at a time
        $out_file_name = str_replace('.gz', '', $filename);

// Open our files (in binary mode)
        $file = gzopen($filename, 'rb');
        $out_file = fopen($out_file_name, 'wb');

// Keep repeating until the end of the input file
        while (!gzeof($file)) {
            // Read buffer-size bytes
            // Both fwrite and gzread and binary-safe
            fwrite($out_file, gzread($file, $buffer_size));
        }

// Files are done, close files
        fclose($out_file);
        gzclose($file);

\Debugbar::info($productList);
*/
        //$answer = $mkm->increaseStock(497282389, 4);
        //\Debugbar::info($answer);
        /*
                $answer = $mkm->addToStock("250636", "2", "500");
                \Debugbar::info($answer);
        $idArticle = $answer->inserted[0]->idArticle->idArticle;
                $answer = $mkm->changeArticleInStock("397558152","1", "25");
                \Debugbar::info($answer);

                $answer = $mkm->deleteFromStock($idArticle, "4");
                \Debugbar::info($answer);


                $answer = $mkm->getStock();
                \Debugbar::info($answer->article[0]);

        */
        return view('admin.conn', compact("answer"));
    }

    public function setMKMProductsIds()
    {
        $mkm = new MKMService();

        $editions = $this->editionRepository->getOnlyWithMKM();
        return view("admin.setMKMProductsIds", compact('editions'));
    }

    /*
        public function setMKMProductsIdsByEdition($idEdition){
            $mkm = new MKMService();

            $edition = $this->editionRepository->getByMKMId($idEdition);
            $mkmSingles = $mkm->getSingles($idEdition)->single; //$edition->idExpansionMKM
            $singles = $edition->cards();
            \Debugbar::info($mkmSingles);
            foreach ($mkmSingles as $mkmSingle){
                $single = current(array_filter($singles, function($e) use($mkmSingle){ return $e->name==$mkmSingle->enName; }));
                $single->idProductMKM = $mkmSingle->idProduct;
            }
            echo $edition->name;
        }
    */
    public function setMKMProductId(SetMKMProductIdRequest $request)
    {
        $mkm = new MKMService();
        $singles = $this->cardRepository->getCardByNameAndEdition($request->name, $request->edition_id);
        if (count($singles) != 0) {
            foreach ($singles as $single) {
                $product = $single->product;
                $product->idProductMKM = $request->idProduct;
                $mkmProduct = $mkm->getProduct($request->idProduct);
                if ($product->card->foil)
                    $price = round($mkmProduct->product->priceGuide->TRENDFOIL * 25 * 1.2);
                else
                    $price = round($mkmProduct->product->priceGuide->TREND * 25 * 1.1);
                if ($price < 3)
                    $price = 3;
                if ($product->card->foil && $price < 5)
                    $price = 5;
                if ($price > 25)
                    $price = (ceil($price / 10) * 10) - 1;
                $product->base_price = $price;

                $product->save();
            }
            return "";
        } else
            return $request->name . " not ";
    }

    public function getMKMSingles(GetMKMSinglesRequest $request)
    {
        $mkm = new MKMService();
        $s = $mkm->getSingles($request->idEdition);
        return json_encode($s);
    }

    public function setMKMExpansionIds()
    {
        $mkm = new MKMService();

        $answer = $mkm->getExpansions();

        foreach ($answer->expansion as $expansion) {
            $local = $this->editionRepository->getByName($expansion->enName);
            if ($local != null) {
                $local->idExpansionMKM = $expansion->idExpansion;
                $local->save();
            } else {
                if ($expansion->abbreviation[0] == "X")
                    $expansion->abbreviation[0] = "p";
                $local = $this->editionRepository->getBySign(strtolower($expansion->abbreviation)); //naopak odebrat jedno pismeno
                if ($local != null) {
                    $local->idExpansionMKM = $expansion->idExpansion;
                    $local->save();
                } else {
                    echo $expansion->idExpansion . " " . $expansion->abbreviation . " " . $expansion->enName . "</br>";

                }

            }
        }
    }

}
