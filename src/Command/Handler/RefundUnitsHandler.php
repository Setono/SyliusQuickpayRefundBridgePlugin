<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\Command\Handler;

use Setono\SyliusQuickpayRefundBridgePlugin\Command\RefundUnits;
use Setono\SyliusQuickpayRefundBridgePlugin\Command\Validator\RefundUnitsCommandValidatorInterface;
use Setono\SyliusQuickpayRefundBridgePlugin\Event\UnitsRefunded;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\RefundPlugin\Event\UnitsRefunded as BaseUnitsRefunded;
use Sylius\RefundPlugin\Refunder\RefunderInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Webmozart\Assert\Assert;

final class RefundUnitsHandler
{
    private RefunderInterface $orderUnitsRefunder;

    private RefunderInterface $orderShipmentsRefunder;

    private MessageBusInterface $eventBus;

    private OrderRepositoryInterface $orderRepository;

    private RefundUnitsCommandValidatorInterface $refundUnitsCommandValidator;

    public function __construct(
        RefunderInterface $orderUnitsRefunder,
        RefunderInterface $orderShipmentsRefunder,
        MessageBusInterface $eventBus,
        OrderRepositoryInterface $orderRepository,
        RefundUnitsCommandValidatorInterface $refundUnitsCommandValidator
    ) {
        $this->orderUnitsRefunder = $orderUnitsRefunder;
        $this->orderShipmentsRefunder = $orderShipmentsRefunder;
        $this->eventBus = $eventBus;
        $this->orderRepository = $orderRepository;
        $this->refundUnitsCommandValidator = $refundUnitsCommandValidator;
    }

    public function __invoke(RefundUnits $command): void
    {
        $this->refundUnitsCommandValidator->validate($command);

        $baseCommand = $command->baseCommand();
        $orderNumber = $baseCommand->orderNumber();

        /** @var OrderInterface $order */
        $order = $this->orderRepository->findOneByNumber($orderNumber);

        $refundedTotal = 0;
        $refundedTotal += $this->orderUnitsRefunder->refundFromOrder($baseCommand->units(), $orderNumber);
        $refundedTotal += $this->orderShipmentsRefunder->refundFromOrder($baseCommand->shipments(), $orderNumber);

        Assert::notNull($order->getCurrencyCode());

        $baseEvent = new BaseUnitsRefunded(
            $orderNumber,
            $baseCommand->units(),
            $baseCommand->shipments(),
            $baseCommand->paymentMethodId(),
            $refundedTotal,
            $order->getCurrencyCode(),
            $baseCommand->comment(),
        );

        $this->eventBus->dispatch($baseEvent);
        $this->eventBus->dispatch(new UnitsRefunded($baseEvent, $command->paymentId()));
    }
}
