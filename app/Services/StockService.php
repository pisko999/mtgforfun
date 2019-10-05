<?php


namespace App\Services;


use App\Models\Stock;
use App\Repositories\StockRepositoryInterface;

class StockService
{
    protected $MKM = false;
    protected $stockRepository;
    protected $MKMService;

    public function __construct(StockRepositoryInterface $stockRepository)
    {
        $this->stockRepository = $stockRepository;
        if ($this->MKM)
            $this->MKMService = new MKMService();
    }

    public function add($product, $data)
    {
        $stock = $this->stockRepository->addItem($product, $data);

        if ($this->MKM)
            if ($product->idProductMKM != null) {
                if ($stock->idArticleMKM != null) {
                    $answer = $this->MKMService->increaseStock($stock->idArticleMKM, $data->quantity);
                } else {
                    $answer = $this->MKMService->addToStock($product->idProductMKM, $data->quantity, $data->price / 25, $data->state, 1, "", isset($product->card) ? $product->card->foil : 0);
                    if (isset($answer->inserted[0]->idArticle->idArticle))

                        $stock->idArticleMKM = $answer->inserted[0]->idArticle->idArticle;
                    $stock->save();
                }
            }
        return $stock;
    }

    public function edit($product, $data)
    {
        //prasarna opravit
        if($product->base_price != $data['price'])
        {
            $product->base_price = $data['price'];
            $product->save();
        }

        if ($data['stockId'] != '') {
            $stock = $this->stockRepository->getById($data['stockId']);

            if($data['quantity'] != 0) {
                if ($stock->price != $data['price']) {
                    $this->stockRepository->changePrice($stock, $data['price']);
                    if ($this->MKM)
                        $this->MKMService->changeArticleInStock($stock->idArticle, $stock->quantity, $data['price']);
                }
                if ($stock->state != $data['state']) {
                    $this->stockRepository->changeState($stock, $data['state']);
                    if ($this->MKM)
                        $this->MKMService->changeArticleInStock($stock->idArticle, $stock->quantity, $data['price'], $data['state']);
                }
            }

            $quantity = $stock->quantity - $data['quantity'];
            if ($quantity > 0) {

                $this->stockRepository->decreaseStock($stock, $quantity);
                if ($this->MKM)
                    $this->MKMService->decreaseStock($stock->idArticle, $quantity);
            } elseif ($quantity < 0) {
                $quantity = 0 - $quantity;
                $this->stockRepository->increaseStock($stock, $quantity);
                if ($this->MKM)
                    $this->MKMService->increaseStock($stock->idArticle, $quantity);
            }

        } else
            return $this->add($product, $data);
    }

    public function increase(Stock $stock, $quantity)
    {
        $stock->quantity += $quantity;
        $stock->save();

        if ($this->MKM)
            if ($stock->idArticleMKM != null) {
                $answer = $this->MKMService->increaseStock($stock->idArticleMKM, $quantity);

                if (isset($answer->error)) {

                    $answer2 = $this->MKMService->addToStock($stock->product->idProductMKM, $quantity, $stock->price / 25);
                    $stock->idArticleMKM = $answer2->inserted[0]->idArticle->idArticle;
                    $stock->save();
                }
            }

    }

    public function decrease(Stock $stock, $quantity)
    {
        if ($stock->quantity < $quantity)
            $quantity = $stock->quantity;

        $stock->quantity -= $quantity;
        $stock->save();

        if ($this->MKM)
            if ($stock->idArticleMKM != null) {
                $this->MKMService->decreaseStock($stock->idArticleMKM, $quantity);

            }
    }


}
