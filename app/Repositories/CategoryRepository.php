<?php
/**
 * Created by PhpStorm.
 * User: spina
 * Date: 12/03/2019
 * Time: 21:20
 */

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{

    private $categories;

    public function __construct()
    {
        $this->categories = [
            new Category(1, 'Card', 'Cards'),
            new Category(2, 'Booster', 'Boosters'),
            new Category(3, 'BoosterBox', 'Booster Boxes'),
            new Category(4, 'Collect', 'Collecting'),
            new Category(5, 'Play', 'Playing'),

        ];
    }

    public function getAll()
    {
        return $this->categories;
    }

    public function getById($id)
    {
        return $this->categories[$id - 1]; // fuct up to time categories are sorted by id :)
    }

    public function getCategory($category)
    {
        foreach ($this->categories as $cat) {
            if ($cat->getCategory() == $category)
                return $cat;
        }
        return null;
    }

}
