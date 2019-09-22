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

class MKMController extends Controller
{
    protected $editionRepository;
    protected $cardRepository;

    public function __construct(
        EditionRepositoryInterface $editionRepository,
        CardRepositoryInterface $cardRepository
    )
    {
        $this->editionRepository = $editionRepository;
        $this->cardRepository = $cardRepository;
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function connect()
    {

        $mkm = new MKMService();

        //$answer = $conn->call("account");
        //\Debugbar::info($answer);
        //$answer = $mkm->getSingles(1);
        //\Debugbar::info($answer->single[15]);

        $answer = $mkm->getStock();
        //$articles = $answer->article;
        //foreach ($articles as $article) {
        //    $mkm->decreaseStock($article->idArticle, $article->count);
        //}
        //$answer = $mkm->getProductList();
        //$answer = $mkm->getProduct(210101);
        //\Debugbar::info($answer);

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
        \Debugbar::info($answer);
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
