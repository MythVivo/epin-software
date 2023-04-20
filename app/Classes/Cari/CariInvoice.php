<?php

namespace App\Classes\Cari;

use App\Classes\DBItem;
use App\Classes\DBAdapter;

class CariInvoice extends DBItem
{
    public CariHesap $source;
    public CariHesap $target;
    public float $sourceAmount;
    public float $targetAmount;
    public string $description;
    public int $link = 0;
    public int $adminId = 0;
    public int $state = 0;

    protected static $DBMAP = ["table" => "cari_invoice", "fields" => ["id" => "id", "source.id" => "source_id", "target.id" => "target_id", "sourceAmount" => "source_amount", "targetAmount" => "target_amount", "description" => "description", "state" => "state"]];

    private static array $invoiceList = [];
    public function getName(): string
    {
        return $this->description;
    }
    public static function BindInvoices()
    {
    }
    public static function Create($args)
    {
        $instance = parent::Create($args);
        if ($instance)
            self::$invoiceList[] = $instance;
        return $instance;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function Approve(): bool
    {
        if ($this->Update(['state' => 1])); {
            $this->Reload();
            return true;
        }
        return false;
    }
}
