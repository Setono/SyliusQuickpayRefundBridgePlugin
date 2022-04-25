<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\Command;

use Sylius\RefundPlugin\Command\RefundUnits as BaseRefundUnits;

class RefundUnits
{
    private BaseRefundUnits $baseCommand;

    private int $amount;

    /** @var int|string|null */
    private $paymentId;

    /**
     * @param int|string|null $paymentId
     */
    public function __construct(BaseRefundUnits $baseCommand, int $amount, $paymentId = null)
    {
        if (!is_string($paymentId) && !is_int($paymentId) && null !== $paymentId) {
            throw new \InvalidArgumentException('The $payment id must be either string, int or null');
        }

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

    /**
     * @return int|string|null
     */
    public function paymentId()
    {
        return $this->paymentId;
    }
}
