<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusQuickpayRefundBridgePlugin\Behat\Page\Admin;

use Tests\Sylius\RefundPlugin\Behat\Page\Admin\OrderRefundsPageInterface as BaseOrderRefundsPageInterface;

interface OrderRefundsPageInterface extends BaseOrderRefundsPageInterface
{
    public function canChooseQuickpayPaymentToRefund(): bool;
}
