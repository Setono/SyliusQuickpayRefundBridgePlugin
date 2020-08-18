<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusQuickpayRefundBridgePlugin\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Setono\SyliusQuickpayRefundBridgePlugin\DependencyInjection\SetonoSyliusQuickpayRefundBridgeExtension;

/**
 * See examples of tests and configuration options here: https://github.com/SymfonyTest/SymfonyDependencyInjectionTest
 */
final class SetonoSyliusQuickpayRefundBridgeExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions(): array
    {
        return [
            new SetonoSyliusQuickpayRefundBridgeExtension(),
        ];
    }

    /**
     * @test
     */
    public function after_loading_quickpay_is_in_the_supported_gateways_parameter(): void
    {
        $this->setParameter('sylius_refund.supported_gateways', ['offline']);

        $this->load();

        $this->assertContainerBuilderHasParameter('sylius_refund.supported_gateways', ['offline', 'quickpay']);
    }
}
