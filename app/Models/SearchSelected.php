<?php


namespace App\Models;


class SearchSelected
{
public $editionId;
public $colors;
public $rarity;
public $foil;
public $mkm;
public $text;
public $lang;
public $onlyStock;

public function hydrate($request)
{

    $this->editionId = $request->edition;
    $this->colors = $request->color;
    $this->rarity = isset($request->rarity) ? $request->rarity : 0;
    $this->foil = $request->foil;
    $this->mkm = $request->mkm;
    $this->text = $request->searchText;
    $this->lang = $request->lang;
    $this->onlyStock = $request->onlyStock;

}
}
