<?php
/**
 * Created by PhpStorm.
 * User: spina
 * Date: 12/03/2019
 * Time: 23:47
 */

namespace App\Repositories;


interface CategoryRepositoryInterface
{
    public function getAll();

    public function getById($id);

    public function getCategory($category);
}
