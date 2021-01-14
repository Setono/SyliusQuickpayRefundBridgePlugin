<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\EventListener;

use Setono\SyliusQuickpayRefundBridgePlugin\Command\Factory\RefundUnitsCommandFactoryInterface;
use Sylius\RefundPlugin\Event\UnitsRefunded;
use Symfony\Component\Messenger\MessageBusInterface;

final class RefundListener
{
    private RefundUnitsCommandFactoryInterface $commandFactory;

    private MessageBusInterface $commandBus;

    public function __construct(
        RefundUnitsCommandFactoryInterface $commandFactory,
        MessageBusInterface $commandBus
    ) {
        $this->commandFactory = $commandFactory;
        $this->commandBus = $commandBus;
    }

    public function __invoke(UnitsRefunded $event): void
    {
        $command = $this->commandFactory->fromEvent($event);
        $this->commandBus->dispatch($command);
    }
}
