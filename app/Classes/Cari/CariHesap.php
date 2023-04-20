<?php

namespace App\Classes\Cari;

use App\Classes\Cari\CariInvoice;
use App\Classes\DBAdapter;
use Illuminate\Support\Facades\DB;
use App\Classes\UIItem;

class CariHesap extends UIItem
{

    protected $tableData;
    public CariGroup $group;
    public int $userId = 0;
    public string $name;
    protected float $balance = 0;
    public int $currency = 1;
    public int $lastInvoice = 0;
    protected static $DBMAP = ["table" => "cari_hesap", "fields" => ["id" => "id", "balance" => "balance", "currency" => "currency", "group.id" => "group_id", "name" => "name", "lastInvoice" => "last_invoice"]];

    public function getName(): string
    {
        return $this->name;
    }
    public function getBalance(bool $live = true): float
    {
        if ($live)
            $this->Reload();
        return $this->balance;
    }
    private function setBalance(float $offset, CariInvoice $invoice = null, bool $isOffset = false): bool
    {
        $fieldName = $this::$DBMAP['fields']['balance'];
        $value = ($isOffset ? DB::Raw($fieldName . (string)(($offset > 0 ? '+' : '') . $offset)) : (string)$offset);
        $fieldsToUpdate = [$fieldName => $value];
        if ($invoice)
            $fieldsToUpdate[$this::$DBMAP['fields']['lastInvoice']] = $invoice->getId();
        if ($this->Update($fieldsToUpdate)) {
            $this->Reload();
            return true;
        }
        return false;
    }
    private function increaseBalance(float $offset, CariInvoice $invoice = null): bool
    {
        return $this->setBalance(($offset < 0 ? $offset * -1 : $offset), $invoice, true);
    }
    private function decreaseBalance(float $offset, CariInvoice $invoice = null): bool
    {
        return $this->setBalance(($offset > 0 ? $offset * -1 : $offset), $invoice, true);
    }
    public static function Transfer(CariHesap $source, CariHesap $target, float $amount, string $description, bool $credible = true): ?CariInvoice
    {

        if (!$source || !$target) throw new CariException("Target Or Source Not Set");
        if ($source->id == $target->id) throw new CariException("Target And Source is same");
        if ($amount < 0)  throw new CariException("Amount below Zero");
        if (!$credible && $amount > $source->balance) throw new CariException("Insufficient Balance On Source");

        $invoice = CariInvoice::Create(["source" => $source, "target" => $target, "sourceAmount" => $amount, "targetAmount" => $amount, "description" => $description]);

        if ($invoice && $source->decreaseBalance($amount, $invoice)) {
            if ($target->increaseBalance($amount, $invoice)) {
                $invoice->Approve();
                return $invoice;
            } else {
                $source->increaseBalance($amount, $invoice);
            }
        }

        throw new CariException("Transfer Error");
        return null;
    }
    public function Send(CariHesap $target, float $amount, bool $credible = false): ?CariInvoice
    {
        return self::Transfer($this, $target, $amount, $credible);
    }
    public function Receive(CariHesap $source, float $amount, bool $credible = false): ?CariInvoice
    {
        return self::Transfer($source, $this, $amount, $credible);
    }
}
