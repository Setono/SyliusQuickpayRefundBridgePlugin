<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Webmozart\Assert\Assert;

final class SetonoSyliusQuickpayRefundBridgeExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        /**
         * Add QuickPay to supported gateways https://github.com/Sylius/RefundPlugin#payment-requirements
         *
         * @var array $gateways
         */
        $gateways = $container->getParameter('sylius_refund.supported_gateways');

        Assert::isArray($gateways);
        $gateways[] = 'quickpay';

        $container->setParameter('sylius_refund.supported_gateways', $gateways);
    }
}
