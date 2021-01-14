<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\Payment;

use Sylius\RefundPlugin\Event\UnitsRefunded;
use Webmozart\Assert\Assert;

final class CompositeProvider implements ProviderInterface
{
    private iterable $providers;

    public function __construct(iterable $providers)
    {
        $this->providers = $providers;
    }

    public function __invoke(UnitsRefunded $event)
    {
        foreach ($this->providers as $provider) {
            Assert::isInstanceOf($provider, ProviderInterface::class);

            $paymentId = ($provider)($event);

            if (null !== $paymentId) {
                return $paymentId;
            }
        }

        return null;
    }
}
