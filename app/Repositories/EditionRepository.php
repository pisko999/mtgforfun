<?php
/**
 * Created by PhpStorm.
 * User: spina
 * Date: 14/03/2019
 * Time: 12:56
 */

namespace App\Repositories;

use App\Http\Requests\EditionAddRequest;
use App\Models\Edition;

class EditionRepository extends ModelRepository implements EditionRepositoryInterface
{
    public function __construct(Edition $edition)
    {
        $this->model = $edition;
    }

    public function getByName($name)
    {
        return $this->model->whereName($name)->first();
    }

    public function getBySign($sign)
    {
        return $this->model->whereSign($sign)->first();
    }

    public function getOnlyWithMKM()
    {
        return $this->model->where("idExpansionMKM", "!=", NULL)->get();
    }

    public function getByMKMId($idEdition)
    {
        $edition = $this->model->where("idExpansionMKM", $idEdition)->first();
        return $edition;
    }

    public function getTypes()
    {
        $e = $this->model->select('type')->distinct()->get();
        //foreach ($e as $r)
        //echo($r['type']);
        return $e;
    }

    public function getByType($editionsType)
    {
        //var_dump($editionsType);
        return $this->model->whereType($editionsType)->orderBy('release_date', 'desc')->get();
    }

    public function getByIds($ids)
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    public function getById($id)
    {
        return $this->model->whereId($id)->first();
    }

    public function add(EditionAddRequest $request)
    {
        $edition = new Edition([
            'name' => $request->name,
            'cards_count' => $request->card_count,
            'sign' => $request->code,
            'type' => $request->set_type,
            'release_date' => $request->released_at,
        ]);
        $edition->save();

        return $edition;
    }

    public function getByCode($sign)
    {
        $edition = $this->model->whereSign($sign)->first();
        return $edition;
    }

    public function getByTypeWithCountCards($editionsType)
    {
        return $this->model
            ->withCount(['cards' => function ($q) {
                $q->where('foil', 0);
            }])
            ->whereType($editionsType)
            ->orderBy('release_date', 'desc')
            ->get();

    }

    public function getStandartEditions()
    {
        $standart_types = array("core", "expansion");

        return $this->model
            ->select('id', 'name')
            ->where('release_date', '>', date("Y") - 2 . "-09-01")
            ->whereIn('type', $standart_types)
            ->orderBy('release_date', 'desc')
            ->get();
    }

    public function getBuyListEditions()
    {
        $standart_types = array("core", "expansion");

        return $this->model
            ->select('id', 'name')
            ->where('release_date', '>', date("Y") - 1 . "-09-01")
            ->whereIn('type', $standart_types)
            ->orderBy('release_date', 'desc')
            ->get();
    }

    public function getArrayForSelect()
    {
        $modern_types = array("core", "expansion");

        $mh1 = $this->model
            ->select('id', 'name')
            ->where('sign', 'mh1')
            ->first();

        $standart = $this->getStandartEditions();


        $modern = $this->model
            ->select('id', 'name')
            ->where('release_date', '>', "2003-07-01")
            ->where('release_date', '<', date("Y") - 2 . "-09-01")
            ->whereIn('type', $modern_types)
            ->orderBy('release_date', 'desc')
            ->get();
        $legacy = $this->model
            ->select('id', 'name')
            ->where('release_date', '<', "2003-07-01")
            ->whereIn('type', $modern_types)
            ->orderBy('release_date', 'desc')
            ->get();

        $master = $this->model
            ->select('id', 'name')
            ->where('type', 'masters')
            ->orderBy('release_date', 'desc')
            ->get();

        $funny = $this->model
            ->select('id', 'name')
            ->where('type', 'funny')
            ->orderBy('release_date', 'desc')
            ->get();

        $masterpieces = $this->model
            ->select('id', 'name')
            ->where('type', 'masterpiece')
            ->orderBy('release_date', 'desc')
            ->get();

        $draft_inovations = $this->model
            ->select('id', 'name')
            ->where('type', 'draft_innovation')
            ->where('sign', '<>', 'mh1')
            ->orderBy('release_date', 'desc')
            ->get();

        $promos = $this->model
            ->select('id', 'name')
            ->where('type', 'promo')
            ->orderBy('release_date', 'desc')
            ->get();

        foreach ($standart as $edition)
            $standartArray[$edition->id] = $edition->name;

        $modernArray[$mh1->id] = $mh1->name;
        foreach ($modern as $edition)
            $modernArray[$edition->id] = $edition->name;

        foreach ($legacy as $edition)
            $legacyArray[$edition->id] = $edition->name;

        foreach ($master as $edition)
            $masterArray[$edition->id] = $edition->name;

        foreach ($funny as $edition)
            $funnyArray[$edition->id] = $edition->name;

        foreach ($masterpieces as $edition)
            $masterpiecesArray[$edition->id] = $edition->name;

        foreach ($draft_inovations as $edition)
            $draft_inovationsArray[$edition->id] = $edition->name;

        foreach ($promos as $edition)
            $promosArray[$edition->id] = $edition->name;


        $selectArray = array(0 => "All");
        $selectArray["standard"] = $standartArray;
        $selectArray["modern"] = $modernArray;
        $selectArray["legacy"] = $legacyArray;
        $selectArray["masters"] = $masterArray;
        $selectArray["funny"] = $funnyArray;
        $selectArray["masterpieces"] = $masterpiecesArray;
        $selectArray["draft_inovations"] = $draft_inovationsArray;
        $selectArray["promos"] = $promosArray;


        return $selectArray;

    }
}
