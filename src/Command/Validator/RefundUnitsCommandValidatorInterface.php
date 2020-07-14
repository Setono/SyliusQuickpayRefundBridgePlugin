<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\Command\Validator;

use Setono\SyliusQuickpayRefundBridgePlugin\Command\RefundUnits;

interface RefundUnitsCommandValidatorInterface
{
    public function validate(RefundUnits $command): void;
}
