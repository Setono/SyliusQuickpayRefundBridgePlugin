<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\Command;

use Sylius\RefundPlugin\Command\RefundUnits as BaseRefundUnits;

class RefundUnits
{
    private BaseRefundUnits $baseCommand;

    private int $amount;

    private ?int $paymentId;

    public function __construct(BaseRefundUnits $baseCommand, int $amount, ?int $paymentId = null)
    {
        $this->baseCommand = $baseCommand;
        $this->amount = $amount;
        $this->paymentId = $paymentId;
    }

    public function baseCommand(): BaseRefundUnits
    {
        return $this->baseCommand;
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function paymentId(): ?int
    {
        return $this->paymentId;
    }
}
