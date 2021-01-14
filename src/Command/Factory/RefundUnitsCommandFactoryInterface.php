<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\Command\Factory;

use Setono\SyliusQuickpayRefundBridgePlugin\Command\RefundUnits;
use Sylius\RefundPlugin\Event\UnitsRefunded;

interface RefundUnitsCommandFactoryInterface
{
    public function fromEvent(UnitsRefunded $event): RefundUnits;
}
