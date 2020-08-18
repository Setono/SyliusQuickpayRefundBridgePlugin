<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\Event;

use Sylius\RefundPlugin\Event\UnitsRefunded as BaseUnitsRefunded;

class UnitsRefunded
{
    /** @var BaseUnitsRefunded */
    private $baseEvent;

    /** @var int|null */
    private $paymentId;

    public function __construct(
        BaseUnitsRefunded $baseEvent,
        ?int $paymentId = null
    ) {
        $this->baseEvent = $baseEvent;
        $this->paymentId = $paymentId;
    }

    public function baseEvent(): BaseUnitsRefunded
    {
        return $this->baseEvent;
    }

    public function paymentId(): ?int
    {
        return $this->paymentId;
    }
}
