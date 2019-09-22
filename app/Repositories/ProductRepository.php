<?php
/**
 * Created by PhpStorm.
 * User: spina
 * Date: 12/03/2019
 * Time: 22:27
 */

namespace App\Repositories;

use App\Http\Requests\CardAddJsonRequest;
use App\Http\Requests\ProductCreateRequest;
use App\Models\Product;
use App\Models\Booster;
use App\Models\BoosterBox;
use App\Models\Card;
use App\Models\Collect;
use App\Models\Play;
use App\Models\Image;
use App\Models\Price;
use App\Repositories\EditionRepository;
use Illuminate\Http\Request;
use PhpParser\ErrorHandler\Collecting;


class ProductRepository extends ModelRepository implements ProductRepositoryInterface
{
    protected $booster;
    protected $boosterBox;
    protected $card;
    protected $collect;
    protected $play;

    public function __construct(
        Product $product,
        Booster $booster,
        BoosterBox $boosterBox,
        Card $card,
        Collect $collect,
        Play $play
    )
    {
        $this->model = $product;
        $this->booster = $booster;
        $this->boosterBox = $boosterBox;
        $this->card = $card;
        $this->collect = $collect;
        $this->play = $play;
    }

    public function createProduct(ProductCreateRequest $request, CategoryRepositoryInterface $categoryRepository)
    {
        //if product name exists -> error

        $product = new Product([
            'name' => $request->name,
            'categoryId' => $request->category,
            'base_price' => $request->price,
            'lang' => $request->lang
        ]);

        $product->save();

        $category = $categoryRepository->getById($request->category);
        if ($request->image != null) {
            $fileName = $request->name . '.' . $request->image->getClientOriginalExtension();
            //var_dump($categoryRepository->getById($request->category));
            $storagePath = 'image/' . $category->getCategory();
            $path = $request->image->storeAs('app/public/' . $storagePath, $fileName);
            //echo $path;
            $image = new Image([
                'path' => $storagePath . '/' . $fileName,
                'alt' => $request->name,
            ]);
            $product->image()->save($image);
        }
        $c = lcfirst($category->getCategory());
        $cat = $this->$c->create(['id' => $product->id]);
        $product->$c()->save($cat);
    }

    public function addFromNet(PriceRepositoryInterface $priceRepository, CardAddJsonRequest $request)
    {
        $categoryId = 0;
        switch ($request->object) {
            case 'card':
                $categoryId = 1;
                break;
        }
        if (!isset($request->release_at))
            $request->release_at = "";

        $price = ceil(
            isset($request->prices['eur']) ?
                ($request->prices['eur'] * 25) :
                (isset($request->prices['usd']) ?
                    ($request->prices['usd'] * 22) :
                    0));

        $product = new Product([
            'name' => $request->name,
            'categoryId' => $categoryId,
            'base_price' => $price * 1.2,
            'release_date' => $request->release_at,
        ]);

        $product->save();

        $price = $priceRepository->new($price);

        $product->price()->save($price);

//saving image
        $img_path =
            "image/" .
            $request->set .
            "/" .
            str_replace(':', '',
                str_replace('"', '',
                    preg_replace('/\s/', '',
                        strtok(
                            strtok($request->name, '//'),
                            '?')
                    ))) .
            ".png";
        $img_path2 = substr($img_path, 0, strpos($img_path, 'png')) . 'jpg';
        //echo $img_path2;
        if (file_exists(storage_path('app/public/' . $img_path2)))
            $img_path = $img_path2;
        if (file_exists(storage_path('app/public/' . $img_path)))
            \DB::table('images')->insert([
                'alt' => $request->name,
                'path' => $img_path,
                'product_id' => $product->id,
            ]);
        else {
            if (isset($request->image_uris))
                $url = $request->image_uris['normal'];
            else
                $url = $request->card_faces[0]['image_uris']['normal']; // not sure
            if (!file_exists(storage_path("app/public/image/" . $request->set))) {

                //var_dump(storage_path("app/public/image/" . $set->code));
                \Storage::makeDirectory("app/public/image/" . $request->set, 1777, true);
            }

            $img_path = "image/" .
                $request->set .
                "/" .
                str_replace(':', '',
                    str_replace('"', '',
                        preg_replace('/\s/', '',
                            strtok(
                                strtok($request->name, '/'),
                                '?')
                        ))) .
                ".jpg";
            $contents = file_get_contents($url);
            file_put_contents(storage_path("app/public/" . $img_path), $contents);
            //echo $img_path;
            \DB::table('images')->insert([
                'alt' => $request->name,
                'path' => $img_path,
                'product_id' => $product->id,
            ]);
        }

        return $product;
    }

    private function getByIds($ids)
    {
        $products = $this->model->whereIn('id', $ids);
        return $products;
    }

    public function getByIdsAll($ids)
    {
        $products = $this->getByIds($ids)->get();
        return $products;

    }

    public function getByIdsPaginate($ids, $nbrPerPage)
    {
        $products = $this->getByIds($ids)->paginate($nbrPerPage, ['*'], 'page', 1);
        return $products;
    }

    public function getProductsByCategoryPaginate($category, $n)
    {
        $s = lcfirst($category->getCategory());
        //echo $s;
        if (!isset($s))
            return array();
        if ($s == "collect" || $s == "play")
            return $this->$s->with('Product', 'Product.Stock')->where(function ($q) {
                return $q->whereHas('product', function ($q) {
                    return $q->whereHas('stock', function ($q){
                        return $q->where('quantity','>',0);
                    });
                });
            })->paginate($n);

        return $this->$s->with('Product', 'product.stock', 'edition', 'product.image', 'product.stock.image')->where(function ($q) {
            return $q->whereHas('product', function ($q) {
                return $q->whereHas('stock', function ($q){
                    return $q->where('quantity','>',0);
                });
            });
        })->paginate($n);
    }

    public function getLanguages()
    {
        $result = $this->model->select('lang')->distinct()->get();
        $r = array();
        foreach ($result as $res) {
            $r = array_merge($r, [$res->lang => $res->lang]);
        }

        return $r;
    }


    public function getProductsByNamePaginate($name, $n)
    {
        $products = $this->getProductsByName($name)->paginate($n)->appends(request()->except('page'));

        return $products;
    }

    public function getProductsByName($name)
    {
        $products = $this->model->where('name', 'like', '%' . $name . '%');
        /*
                $ids = array();
                foreach ($products as $product)
                    array_push($ids, $product->id);
                $cards = $this->card->whereIn('id',  $ids);
                $boosters = $this->booster->whereIn('product_id',  $ids);
                $products = array();
                array_push($products,$cards,$boosters);
        */
        return $products;

    }

    public function getProductsByExactName($name)
    {
        $product = $this->model->whereName($name)->get();
        return $product;
    }

    public function getProductByExactName($name)
    {
        $product = $this->model->whereName($name)->first();
        return $product;
    }

    public function getProductByExactNameAndExpansionMKM($name, $category, $expansionMKMId)
    {
        $type = null;
        $product = null;
        switch ($category) {
            case "Magic Single":
                $type = 'card';
                break;
            case "Magic Booster":
                $type = 'booster';
                break;
            case "Magic Dispaly":
                $type = 'boosterBox';
                break;
        }
        if ($type != null)
            $product = $this->model->whereName($name)->where(function ($q) use ($expansionMKMId, $type) {
                return $q->whereHas($type, function ($q) use ($expansionMKMId) {
                    return $q->whereHas('edition', function ($q) use ($expansionMKMId) {
                        return $q->where('IdExpansionMKM', '=',$expansionMKMId);
                    });
                });
            });
        if ($product != null)
            return $product->first();
        return false;
    }

    public function getProductsByExactNameAndEdition($name, $editionCode)
    {
        $products = $this->getProductsByExactName($name);
        $editionCode = strtolower($editionCode);
        foreach ($products as $product) {
            //var_dump();
            switch ($product->categoryId) {
                case 1:
                    if ($product->card->edition->sign == $editionCode)
                        return $product;
                    break;
                case 2:
                    if ($product->booster->edition->sign == $editionCode)
                        return $product;
                    break;
                case 3:
                    if ($product->boosterBox->edition->sign == $editionCode)
                        return $product;
                    break;

            }
        }
        return null;
    }

    public function getPrints($name, $edition_id, EditionRepositoryInterface $editionRepository)
    {

        $products = $this->getProductsByExactName($name);
        $edition_ids = array();
        //var_dump($cards);
        foreach ($products as $product) {
            $card = $product->card;
            if ($card->edition->id != $edition_id)
                array_push($edition_ids, $card->edition->id);
            //var_dump($editions);
        }
        $editions = $editionRepository->getByIds($edition_ids);
        return $editions;
    }

}
