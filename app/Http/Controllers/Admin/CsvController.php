<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CompleteIdsFromCsvRequest;
use App\Http\Requests\DeleteByCsvRequest;
use App\Http\Requests\ImportFromCsvRequest;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\StockRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CsvController extends Controller
{

    protected $stockRepository;
    protected $productRepository;
    private $usd = 22;

    public function __construct(
        StockRepositoryInterface $stockRepository,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->stockRepository = $stockRepository;
        $this->productRepository = $productRepository;
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function importFromCsvGet()
    {
        return view('admin.importFromCsv');
    }

    public function importFromCsvPost(ImportFromCsvRequest $request)
    {
        $messages = array();
        $messageInStock = array();
        $errorNoAdd = array();
        $errorNoProduct = array();
        $price = 0;

        if ($request->importedFile->isValid()) {

            $filename = $request->file('importedFile');

            $file = fopen($filename, 'r');
            $puredata = fread($file, filesize($filename));
            fclose($file);
            $data = explode("\n", $puredata);

            $cards = array();
            $i = 0;

            foreach ($data as $row) {
                if (strlen($row) == 0) {
                    $i++;
                } else {
                    //echo $row . "<br>";
                    $card = new cardsCsv($row);
                    //if
                    if ($card->purchasePrice <= .1)
                        $card->purchasePrice = 3;
                    elseif ($card->purchasePrice <= .2)
                        $card->purchasePrice = 5;
                    elseif ($card->purchasePrice <= .4)
                        $card->purchasePrice = 9;
                    else
                        $card->purchasePrice = round($card->purchasePrice * $this->usd, 0);

                    $cards[] = $card;


                }
            }


            foreach ($cards as $card) {
                $success = false;

                $product = $this->productRepository->getProductsByExactNameAndEdition($card->name, $card->code);

                if ($product == null) {
                    array_push($errorNoProduct, $card->name);
                    //array_push($messages, "<p style='color: darkgray;'>" .$card->name . " wasnt added.</p><br />");
                    continue;
                }

                $re['quantity'] = $card->quantity;
                $re['foil'] = $card->foil;
                $re['price'] = $card->purchasePrice;
                $re['state'] = $card->condition;
//var_dump($product);
                $success = $this->stockRepository->addItem($product, $re);
                /*
                if ($product->stock != null)

                    foreach ($product->stock as $stock) {

                        if ($success = $this->stockRepository->addItemToExists($stock, $re)) {
                            array_push($messageInStock, $product->name);
                            $price += $re['price'];
                            //array_push($messagesInStock, "<p style='color: blue;'>" . $product->name . " in stock.</p><br />");
                            break;
                        }

                    }

                if (!$success) {
                    $success = $this->stockRepository->addNewItem($product, $re);
                    $price += $re['price'];
                }
*/
                if (!$success)
                    array_push($errorNoAdd, $card->name);
                //array_push($messages, "<p style='color: red;'>" . $card->name . " wasnt added.</p><br />");
                else
                    array_push($messages, $product->name);
                //array_push($messages, "<p style='color: green;'>" . $product->name . " was successfully added.</p><br />");

                //var_dump($product);
            }
            //var_dump($cards);


        }

        return view('admin.importFromCsv', compact('messages', 'errorNoAdd', 'messageInStock', 'errorNoProduct', 'price'));
    }

    public function deleteByCsvGet()
    {
        return view('admin.deleteByCsv');
    }

    public function deleteByCsvPost(DeleteByCsvRequest $request)
    {
        $messages = array();
        $inStock = array();
        $messageInStock = array();
        $errorNotRemoved = array();
        $errorNoProduct = array();

        if ($request->importedFile->isValid()) {

            $filename = $request->file('importedFile');

            $file = fopen($filename, 'r');
            $puredata = fread($file, filesize($filename));
            fclose($file);
            $data = explode("\n", $puredata);

            $cards = array();
            $i = 0;

            foreach ($data as $row) {
                if (strlen($row) == 0) {
                    $i++;
                } else {
                    //echo $row . "<br>";
                    $card = new cardsCsv($row);
                    //if
                    if ($card->purchasePrice <= .1)
                        $card->purchasePrice = 3;
                    elseif ($card->purchasePrice <= .2)
                        $card->purchasePrice = 5;
                    elseif ($card->purchasePrice <= .4)
                        $card->purchasePrice = 9;
                    else
                        $card->purchasePrice = round($card->purchasePrice * $this->usd, 0);

                    $cards[] = $card;
                }
            }


            foreach ($cards as $card) {
                $success = false;

                $product = $this->productRepository->getProductsByExactNameAndEdition($card->name, $card->code);

                if ($product == null) {
                    array_push($errorNoProduct, $card);
                    //array_push($messages, "<p style='color: darkgray;'>" .$card->name . " wasnt added.</p><br />");
                    continue;
                }

                $re['quantity'] = $card->quantity;
                $re['foil'] = $card->foil;
                $re['price'] = $card->purchasePrice;
                $re['state'] = $card->condition;
//var_dump($product);
                if ($product->stock != null) {

                    foreach ($product->stock as $stock) {

                        if ($success = $this->stockRepository->removeItemFromExistsExact($stock, $re)) {
                            break;
                        }

                    }
                    if (!$success) {
                        $card->row = $row;
                        $card->product = $product;

                        array_push($messageInStock, $card);
                        array_push($inStock, $card);
                        //array_push($messagesInStock, "<p style='color: blue;'>" . $product->name . " in stock.</p><br />");
                    }
                }

                if (!$success) {
                    array_push($errorNotRemoved, $card);
                    //array_push($messages, "<p style='color: red;'>" . $card->name . " wasnt added.</p><br />");

                } else
                    array_push($messages, $card);
                //array_push($messages, "<p style='color: green;'>" . $product->name . " was successfully added.</p><br />");

                //var_dump($product);
            }
            //var_dump($cards);

            $request->session()->put('inStock', $inStock);


        }

        return view('admin.deleteByCsv', compact('messages', 'errorNotRemoved', 'messageInStock', 'errorNoProduct'));
    }

    public function deleteByCsv2Post(DeleteByCsvRequest $request)
    {
        $messages = array();
        $messageInStock = array();
        $errorNotRemoved = array();
        $errorNoProduct = array();
        $inStocks = $request->session()->get('inStock');
        foreach ($inStocks as $inStock) {
            $f = "f" . $inStock->product->id;
            $this->stockRepository->removeItemFromExists($inStock, $request[$f]);
            if ($inStock->quantity = 0)
                unset($inStocks[array_search($inStock, $inStocks)]);

        }

        return view('admin.deleteByCsv', compact('messages', 'errorNotRemoved', 'messageInStock', 'errorNoProduct'));
    }

    public function importStock()
    {
    }

    public function exportStock()
    {

        $stock = $this->stockRepository->getAll();
        $i = 0;
        $j = 1;
        if (!file_exists(storage_path("app/public/export")))
            Storage::makeDirectory("app/public/export", 1777, true);
        $cards = [];
        $filecontent = "";
        foreach ($stock as $item) {

            $product = $item->product;
            $edition = null;
            if ($product->card != null)
                $edition = $product->card->edition->sign;
            else if ($product->booster != null)
                $edition = $product->booster->edition->sign;
            else if ($product->boosterBox != null)
                $edition = $product->boosterBox->edition->sign;


            $string = $item->quantity . ",\"" .
                $product->name . "\"," .
                $edition . "," .
                round($item->price / $this->usd, 2) . "," .
                $item->foil . "," .
                $item->state . "," .
                $item->language . ",\n";

            $filecontent .= $string;
            //$card = new cardsCsv($string);
            //array_push($cards, $card);
            //echo $string;
//echo $product->name;
            $i++;
            if ($i > 99) {
                Storage::put('app/public/export/export' . $j . '.csv', $filecontent);
                $filecontent = "";
                $j++;
                $i = 0;

            }
        }
    }

    public function completeIdsFromCsvGet(){
        $files = Storage::files('/public/editions');
        //foreach ($files as $file)
         //\Debugbar::info($files);
        return view("admin.completeIds2", compact('files'));
    }
/*
    public function completeIdsFromCsvGet(){
        return view("admin.completeIds");
    }
*/
    public function completeIdsFromCsvPost(CompleteIdsFromCsvRequest $request)
    {
        $messages = [];
        $errorNoAdd = [];
        if (isset($request->importedFile) && $request->importedFile->isValid()) {

            $filename = $request->file('importedFile');

            $file = fopen($filename, 'r');
            $puredata = fread($file, filesize($filename));
            fclose($file);
            $data = explode("\n", $puredata);

            $cards = array();
            $i = 0;
            foreach ($data as $row) {
                if (strlen($row) == 0) {
                    $i++;
                } else {
                    $item = new radek($row);
                    $product = $this->productRepository->getProductByExactNameAndExpansionMKM($item->name, $item->category, $item->expansionId);
                    if($product != null)
                    {
                        $product->idProductMKM = $item->id;
                        $product->save();
                        $messages[] = $product->name . " added.";
                    }
                    else
                        $errorNoAdd[] = $item->id . ','.$item->name . ','. $item->expansionId;
                }
            }
        }
        elseif($request->soubor != null){
            $file = Storage::get($request->soubor);
            $data = explode("\n", $file);
            foreach($data as $row){
                $item = new radek($row);
                $product = $this->productRepository->getProductByExactNameAndExpansionMKM($item->name, $item->category, $item->expansionId);
                if($product != null)
                {
                    $product->idProductMKM = $item->id;
                    $product->save();
                    $messages[] = $product->name . " added.";
                }
                else
                    $errorNoAdd[] = $item->id . ','.$item->name . ','. $item->expansionId;
            }

            return true;
        }
        else
            $messages[] = "Not valid file.";
        return view("admin.completeIds", compact('messages', 'errorNoAdd'));

    }
}
class radek{
    public $id;
    public $name;
    public $categoryId;
    public $category;
    public $expansionId;
    public $metaId;
    public $dateAdded;

    public function __construct($string)
    {
        //\Debugbar::info($string);

        $this->hydrate($string);
    }

    private function hydrate($string)
    {
        $string = str_replace("\"", "", $string);
        $data = explode(',', $string);
        $this->id = $data[0];
        $this->name = $data[1];
        $this->categoryId = $data[2];
        $this->category = $data[3];
        $this->expansionId = $data[4];
        $this->metaId = $data[5];
        $this->dateAdded = $data[6];
    }
}

class cardsCsv
{
    public $quantity;
    public $name;
    public $code;
    public $purchasePrice;
    public $foil;
    public $condition;
    public $language;
    public $purchaseDate;
    public $product;
    public $row;

    public function __construct($string)
    {
        //\Debugbar::info($string);

        $this->hydrate($string);
    }

    private function hydrate($string)
    {
        $data = explode(',', $string);
        if (preg_match("\"[A-Za-z]+, [A-Za-z]+\"", $string)) {
            $data[1] = $data[1] . "," . $data[2];
            $data[2] = $data[3];
            $data[3] = $data[4];
            $data[4] = $data[5];
            $data[5] = $data[6];
            $data[6] = $data[7];
            $data[7] = $data[8];
            unset($data[8]);
        }
        //\Debugbar::info($data[1]);


        if (count($data) <> 8)
            return -1;
        if (is_numeric($data[5]))
            $data[5] = $this->getState($data[5]);
        //else
        //  $data[5] = $this->getStateInverse($data[5]);

        $this->quantity = $data[0];
        $this->name = str_replace("\"", "", $data[1]);
        $this->code = $data[2];
        $this->purchasePrice = $data[3];
        $this->foil = $data[4];
        $this->condition = $this->getState($data[5]);
        $this->language = $data[6];
        $this->purchaseDate = $data[7];
    }

    private function getState($n)
    {
        if (is_numeric($n) && ($n >= 0 && $n < 5))
            switch ($n) {
                case 0:
                    return "NM";
                case 1:
                    return "LP";
                case 2:
                    return "SP";
                case 3:
                    return "MP";
                case 4:
                    return "DA";
                default:
                    return "NM";
            };
        return "NM";
    }

    private function getStateInverse($n)
    {
        if (is_numeric($n) && ($n >= 0 && $n < 5))
            switch ($n) {
                case "NM":
                    return 0;
                case "LP":
                    return 1;
                case "SP":
                    return 2;
                case "MP":
                    return 3;
                case "DA":
                    return 4;
                default:
                    return 0;
            };
        return 0;
    }
}
