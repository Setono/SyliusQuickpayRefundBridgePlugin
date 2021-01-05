<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

final class SetonoSyliusQuickpayRefundBridgeExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        // add QuickPay to supported gateways https://github.com/Sylius/RefundPlugin#payment-requirements
        $gateways = $container->getParameter('sylius_refund.supported_gateways');
        $gateways[] = 'quickpay';
        $container->setParameter('sylius_refund.supported_gateways', $gateways);
    }
}
