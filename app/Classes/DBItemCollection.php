<?php

namespace App\Classes;


class DBItemCollection extends \ArrayObject
{
    public function export(bool $full = false): array
    {
        return array_map(function ($item) use ($full) {
            return $item->export($full);
        }, (array)$this);
    }
}
