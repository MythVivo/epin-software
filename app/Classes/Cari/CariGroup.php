<?php

namespace App\Classes\Cari;

use App\Classes\DBItem;
use App\Classes\UIItem;

class CariGroup extends UIItem
{
    protected string $name;
    protected static $DBMAP = ["table" => "cari_group", "fields" => ["id" => "id", "name" => "name"]];
    public function getName(): string
    {
        return $this->name;
    }
}
