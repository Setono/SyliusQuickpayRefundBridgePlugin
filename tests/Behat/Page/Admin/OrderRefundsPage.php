<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusQuickpayRefundBridgePlugin\Behat\Page\Admin;

use Behat\Mink\Session;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Symfony\Component\Routing\RouterInterface;
use Tests\Sylius\RefundPlugin\Behat\Page\Admin\OrderRefundsPageInterface as BaseOrderRefundsPageInterface;

final class OrderRefundsPage extends SymfonyPage implements OrderRefundsPageInterface
{
    /** @var BaseOrderRefundsPageInterface */
    private $orderRefundsPage;

    public function __construct(
        Session $session,
        $minkParameters,
        RouterInterface $router,
        BaseOrderRefundsPageInterface $orderRefundsPage
    ) {
        parent::__construct($session, $minkParameters, $router);

        $this->orderRefundsPage = $orderRefundsPage;
    }

    public function getRouteName(): string
    {
        return $this->orderRefundsPage->getRouteName();
    }

    public function canChooseQuickpayPaymentToRefund(): bool
    {
        return $this->hasElement('quickpay_payments');
    }

    public function countRefundableUnitsWithProduct(string $productName): int
    {
        return $this->orderRefundsPage->countRefundableUnitsWithProduct($productName);
    }

    public function getRefundedTotal(): string
    {
        return $this->orderRefundsPage->getRefundedTotal();
    }

    public function getUnitWithProductRefundedTotal(int $unitNumber, string $productName): string
    {
        return $this->orderRefundsPage->getUnitWithProductRefundedTotal($unitNumber, $productName);
    }

    public function pickUnitWithProductToRefund(string $productName, int $unitNumber): void
    {
        $this->orderRefundsPage->pickUnitWithProductToRefund($productName, $unitNumber);
    }

    public function pickPartOfUnitWithProductToRefund(string $productName, int $unitNumber, string $amount): void
    {
        $this->orderRefundsPage->pickPartOfUnitWithProductToRefund($productName, $unitNumber, $amount);
    }

    public function pickAllUnitsToRefund(): void
    {
        $this->orderRefundsPage->pickAllUnitsToRefund();
    }

    public function pickOrderShipment(?string $shippingMethodName = null): void
    {
        $this->orderRefundsPage->pickOrderShipment();
    }

    public function pickPartOfOrderShipmentToRefund(string $amount): void
    {
        $this->orderRefundsPage->pickPartOfOrderShipmentToRefund($amount);
    }

    public function choosePaymentMethod(string $paymentMethodName): void
    {
        $this->orderRefundsPage->choosePaymentMethod($paymentMethodName);
    }

    public function comment(string $comment): void
    {
        $this->orderRefundsPage->comment($comment);
    }

    public function refund(): void
    {
        $this->orderRefundsPage->refund();
    }

    public function isUnitWithProductAvailableToRefund(string $productName, int $unitNumber): bool
    {
        return $this->orderRefundsPage->isUnitWithProductAvailableToRefund($productName, $unitNumber);
    }

    public function eachRefundButtonIsDisabled(): bool
    {
        return $this->orderRefundsPage->eachRefundButtonIsDisabled();
    }

    public function isOrderShipmentAvailableToRefund(): bool
    {
        return $this->orderRefundsPage->isOrderShipmentAvailableToRefund();
    }

    public function hasBackButton(): bool
    {
        return $this->orderRefundsPage->hasBackButton();
    }

    public function canChoosePaymentMethod(): bool
    {
        return $this->orderRefundsPage->canChoosePaymentMethod();
    }

    public function isPaymentMethodVisible(string $paymentMethodName): bool
    {
        return $this->orderRefundsPage->isPaymentMethodVisible($paymentMethodName);
    }

    public function getOriginalPaymentMethodName(): string
    {
        return $this->orderRefundsPage->getOriginalPaymentMethodName();
    }

    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'quickpay_payments' => '#quickpay-payment',
        ]);
    }
}
