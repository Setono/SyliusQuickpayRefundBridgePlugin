<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\Command\Factory;

use Setono\SyliusQuickpayRefundBridgePlugin\Command\RefundUnits;
use Setono\SyliusQuickpayRefundBridgePlugin\Payment\ProviderInterface;
use Sylius\RefundPlugin\Command\RefundUnits as BaseRefundUnits;
use Sylius\RefundPlugin\Event\UnitsRefunded;

final class RefundUnitsCommandFactory implements RefundUnitsCommandFactoryInterface
{
    private ProviderInterface $paymentProvider;

    public function __construct(ProviderInterface $paymentProvider)
    {
        $this->paymentProvider = $paymentProvider;
    }

    public function fromEvent(UnitsRefunded $event): RefundUnits
    {
        $baseCommand = new BaseRefundUnits(
            $event->orderNumber(),
            $event->units(),
            $event->shipments(),
            $event->paymentMethodId(),
            $event->comment()
        );

        $quickpayPaymentId = ($this->paymentProvider)($event);

        return new RefundUnits($baseCommand, $event->amount(), $quickpayPaymentId);
    }
}
