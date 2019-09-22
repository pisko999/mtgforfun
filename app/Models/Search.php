<?php


namespace App\Models;


use App\Repositories\EditionRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\RaritiesRepository;

class Search
{
    public $editions;
    public $colors = array("white", "blue", "black", "red", "green", "multi", "colorless");
    public $foils = array(-1 => "All", 0 => "Only non-foil", 1 => "Only foil");
    public $mkm = array(0 => "All", 1 => "Only on mkm", 2 => "Only not on mkm");
    public $rarities;
    public $lang;

    protected $editionRepository;
    protected $raritiesRepository;
    protected $productRepository;

    public function __construct(
        EditionRepositoryInterface $editionRepository,
        RaritiesRepository $raritiesRepository,
        ProductRepositoryInterface $productRepository)
    {
        $this->editionRepository = $editionRepository;
        $this->raritiesRepository = $raritiesRepository;
        $this->productRepository = $productRepository;

        $this->editions = $this->editionRepository->getArrayForSelect();

        $this->rarities = array_merge(array(0 => "All"), $this->raritiesRepository->getRaritiesToLong());
        $this->lang = array_merge(array(0 => "All"), $this->productRepository->getLanguages());
    }

}
