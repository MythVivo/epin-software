<?php

namespace App\Integrations\Payment;

interface PaymentInterface
{
    public function send($amount);
    public function return();
}
abstract class AbstractPayment implements PaymentInterface
{
}
