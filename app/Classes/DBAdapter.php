<?php

namespace App\Classes;

use App\Classes\DBItem;
use Illuminate\Support\Facades\DB;

abstract class DBAdapter
{
    public function toDB()
    {
    }
    public function getId()
    {
    }
    public static function FakeCast($class): DBItem
    {
        return $class;
    }
    public function Insert(): int
    {
        $dbRes = DB::table(get_called_class()::getTableName())->insert($this->toDB());
        return $dbRes ? DB::getPdo()->lastInsertId() : null;
    }
    public static function FindById(int $id)
    {
        $cc = get_called_class();
        $result = DB::table($cc::getTableName())->where('id', $id)->first();
        return $result ? $cc::InitFromData($result) : null;
    }
    protected function Reload(): bool
    {
        $result = DB::table(get_called_class()::getTableName())->where('id', $this->getId())->first();
        if ($result) {
            self::FakeCast($this)->fromDB($result);
            return true;
        } else
            return false;
    }
    static function SelectAll(?int $from = null, ?int $limit = null, ?bool $include_deleted = null, bool $objMode = true): ?DBItemCollection
    {
        $finalClassName = get_called_class();
        $dbObj = DB::table($finalClassName::getTableName());
        $dbObj = $include_deleted ? $dbObj : $dbObj->whereNull('deleted_at');
        $dbObj = $from === null && $limit === null ? $dbObj : $dbObj->limit($from, $limit);
        $result =  $dbObj->get();
        if ($objMode) {
            $resultObj = new DBItemCollection();
            foreach ($result as $item) {
                $resultObj[] =  $finalClassName::InitFromData($item);
            }
            return $resultObj;
        } else
            return $result;
    }
    public function Update(array $fields)
    {
        return DB::table(get_called_class()::getTableName())->where('id', $this->getId())->update($fields);
    }
    public function Delete()
    {
    }
}
