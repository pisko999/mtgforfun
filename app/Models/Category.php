<?php

namespace App\Models;


class Category
{
    public $id;
    protected $category;
    protected $text;

    public function __construct($id, $category, $text)
    {
        $this->id = $id;
        $this->category = $category;
        $this->text = $text;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getText()
    {
        return $this->text;
    }
}
