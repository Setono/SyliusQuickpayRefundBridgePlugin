<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\Command\Factory;

use Setono\SyliusQuickpayRefundBridgePlugin\Command\RefundUnits;
use Symfony\Component\HttpFoundation\Request;

interface RefundUnitsCommandFactoryInterface
{
    public function fromRequest(Request $request): RefundUnits;
}
