<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\Payment;

use Sylius\RefundPlugin\Event\UnitsRefunded;

interface ProviderInterface
{
    /** @return mixed */
    public function __invoke(UnitsRefunded $event);
}
