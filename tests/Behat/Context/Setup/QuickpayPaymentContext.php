<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusQuickpayRefundBridgePlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\Common\Collections\Collection;
use function Safe\sprintf;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Payment\Repository\PaymentMethodRepositoryInterface;
use Symfony\Component\Intl\Countries;
use Webmozart\Assert\Assert;

final class QuickpayPaymentContext implements Context
{
    private SharedStorageInterface $sharedStorage;

    private PaymentMethodRepositoryInterface $paymentMethodRepository;

    private ExampleFactoryInterface $paymentMethodExampleFactory;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        ExampleFactoryInterface $paymentMethodExampleFactory
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->paymentMethodExampleFactory = $paymentMethodExampleFactory;
    }

    /**
     * @Given the store (also )allows paying with credit card via QuickPay
     */
    public function storeAllowsPayingWithCreditCardViaQuickpay(): void
    {
        $this->createQuickpayPaymentMethod('Credit card', ['creditcard']);
    }

    /**
     * @Given a QuickPay payment has been created
     */
    public function paymentHasBeenAuthorizedAndCaptured(): void
    {
        /** @var OrderInterface $order */
        $order = $this->sharedStorage->get('order');

        $payment = $order->getLastPayment();

        Assert::notNull($payment);

        $payment->setDetails($this->convertOrder($payment));
    }

    private function createQuickpayPaymentMethod(
        string $name,
        array $methods = []
    ): PaymentMethodInterface {
        $code = StringInflector::nameToLowercaseCode($name) . '_quickpay';

        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $this->paymentMethodExampleFactory->create(
            [
                'name' => ucfirst($name) . ' via Quickpay',
                'code' => $code,
                'description' => sprintf('Pay with %s via QuickPay.', ucfirst($name)),
                'gatewayName' => $code,
                'gatewayFactory' => 'quickpay',
                'gatewayConfig' => [
                    'apikey' => 'test_api_key',
                    'privatekey' => 'test_private_key',
                    'merchant' => 'test_merchant',
                    'agreement' => 'test_agreement',
                    'order_prefix' => bin2hex(random_bytes(5)) . '_',
                    'payment_methods' => implode(', ', $methods),
                    'auto_capture' => true,
                    'use_authorize' => true,
                ],
                'enabled' => true,
                'channels' => $this->sharedStorage->has('channel') ? [$this->sharedStorage->get('channel')] : [],
            ]
        );

        $this->sharedStorage->set('payment_method', $paymentMethod);
        $this->paymentMethodRepository->add($paymentMethod);

        return $paymentMethod;
    }

    private function convertOrder(PaymentInterface $payment): array
    {
        $order = $payment->getOrder();

        return [
            'amount' => $payment->getAmount(),
            'shipping_address' => $this->convertAddress($order->getShippingAddress()),
            'invoice_address' => $this->convertAddress($order->getBillingAddress()),
            'shipping' => ['amount' => $order->getShippingTotal()],
            'basket' => $this->convertItems($order->getItems()),
            'customer_email' => $order->getCustomer()->getEmail(),
            'quickpayPaymentId' => 123456789,
        ];
    }

    private function convertAddress(AddressInterface $address): array
    {
        return [
            'street' => $address->getStreet(),
            'name' => $address->getFullName(),
            'city' => $address->getCity(),
            'zip_code' => $address->getPostcode(),
            'region' => $address->getProvinceName() ?? $address->getProvinceCode(),
            'country_code' => Countries::getAlpha3Code($address->getCountryCode()),
            'phone_number' => $address->getPhoneNumber(),
            'email' => $address->getCustomer() ? $address->getCustomer()->getEmail() : null,
        ];
    }

    private function convertItems(Collection $items): array
    {
        return $items->map(
            static function (OrderItemInterface $item): array {
                return [
                    'qty' => $item->getQuantity(),
                    'item_no' => $item->getVariant()->getCode(),
                    'item_name' => sprintf(
                        '%s %s',
                        $item->getProductName(),
                        $item->getVariantName()
                    ),
                    'item_price' => $item->getUnitPrice(),
                    'vat_rate' => 0.25,
                ];
            }
        )->toArray();
    }
}
