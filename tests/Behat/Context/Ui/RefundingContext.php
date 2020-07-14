<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusQuickpayRefundBridgePlugin\Behat\Context\Ui;

use Behat\Behat\Context\Context;
use Tests\Setono\SyliusQuickpayRefundBridgePlugin\Behat\Page\Admin\OrderRefundsPageInterface;
use Webmozart\Assert\Assert;

final class RefundingContext implements Context
{
    /** @var OrderRefundsPageInterface */
    private $orderRefundsPage;

    public function __construct(OrderRefundsPageInterface $orderRefundsPage)
    {
        $this->orderRefundsPage = $orderRefundsPage;
    }

    /**
     * @Then I should be able to choose the QuickPay payment to refund
     */
    public function shouldBeAbleToChooseTheQuickpayPaymentToRefund(): void
    {
        Assert::true($this->orderRefundsPage->canChooseQuickpayPaymentToRefund());
    }
}
