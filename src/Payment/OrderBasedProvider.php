<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\Payment;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\RefundPlugin\Event\UnitsRefunded;

final class OrderBasedProvider implements ProviderInterface
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function __invoke(UnitsRefunded $event)
    {
        $order = $this->orderRepository->findOneByNumber($event->orderNumber());

        if (!$order instanceof OrderInterface) {
            return null;
        }

        $payment = $order->getLastPayment(PaymentInterface::STATE_COMPLETED);

        if (null === $payment) {
            return null;
        }

        if (null === ($method = $payment->getMethod())) {
            return null;
        }

        if ($method->getId() !== $event->paymentMethodId()) {
            return null;
        }

        return $payment->getId();
    }
}
