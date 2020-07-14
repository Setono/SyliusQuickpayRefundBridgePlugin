<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SetonoSyliusQuickpayRefundBridgePlugin extends Bundle
{
    use SyliusPluginTrait;
}
