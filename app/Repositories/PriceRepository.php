<?php


namespace App\Repositories;

use App\Models\Price;
use App\Models\Product;

class PriceRepository implements PriceRepositoryInterface
{
    public function new($price)
    {

        $prices = new Price([
            'da' => ceil($price * 0.4),
            'de' => ceil($price * 0.5),
            'hp' => ceil($price * 0.8),
            'sp' => ceil($price * 0.9),
            'lp' => $price,
            'nm' => ceil($price * 1.2),
            'm' => ceil($price * 1.2),
        ]);
//\Debugbar::info($prices);

        return $prices;
    }
}
