<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\Payment;

use Sylius\RefundPlugin\Event\UnitsRefunded;
use Symfony\Component\HttpFoundation\RequestStack;

final class RequestBasedProvider implements ProviderInterface
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function __invoke(UnitsRefunded $event)
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return null;
        }

        /** @psalm-suppress InternalMethod */
        return $request->get('sylius_refund_quickpay_payment');
    }
}
