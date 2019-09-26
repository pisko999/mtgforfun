<?php
/**
 * Created by PhpStorm.
 * User: spina
 * Date: 14/03/2019
 * Time: 12:55
 */

namespace App\Repositories;


use App\Http\Requests\EditionAddRequest;

interface EditionRepositoryInterface
{
    public function getByName($name);

    public function getBySign($sign);

    public function getOnlyWithMKM();

    public function getByMKMId($idEdition);

    public function getTypes();

    public function getByType($editionsType);

    public function getByIds($ids);

    public function getById($id);

    public function add(EditionAddRequest $request);

    public function getByCode($sign);

    public function getByTypeWithCountCards($editionsType);

    public function getStandartEditions();

    public function getBuyListEditions();

    public function getArrayForSelect();
}
