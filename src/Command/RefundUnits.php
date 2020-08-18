<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\Command;

use Sylius\RefundPlugin\Command\RefundUnits as BaseRefundUnits;

class RefundUnits
{
    /** @var BaseRefundUnits */
    private $baseCommand;

    /** @var int|null */
    private $paymentId;

    public function __construct(
        BaseRefundUnits $baseCommand,
        ?int $paymentId = null
    ) {
        $this->paymentId = $paymentId;
        $this->baseCommand = $baseCommand;
    }

    public function baseCommand(): BaseRefundUnits
    {
        return $this->baseCommand;
    }

    public function paymentId(): ?int
    {
        return $this->paymentId;
    }
}
