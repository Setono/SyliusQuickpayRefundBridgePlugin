<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\Command\Handler;

use Payum\Core\Registry\RegistryInterface;
use Payum\Core\Request\Refund;
use Setono\SyliusQuickpayRefundBridgePlugin\Command\RefundUnits;
use Setono\SyliusQuickpayRefundBridgePlugin\Command\Validator\RefundUnitsCommandValidatorInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Core\Repository\PaymentRepositoryInterface;
use Webmozart\Assert\Assert;

final class RefundUnitsHandler
{
    private RegistryInterface $payum;

    private RefundUnitsCommandValidatorInterface $commandValidator;

    private PaymentRepositoryInterface $paymentRepository;

    public function __construct(
        RegistryInterface $payum,
        RefundUnitsCommandValidatorInterface $commandValidator,
        PaymentRepositoryInterface $paymentRepository
    ) {
        $this->payum = $payum;
        $this->commandValidator = $commandValidator;
        $this->paymentRepository = $paymentRepository;
    }

    public function __invoke(RefundUnits $command): void
    {
        $this->commandValidator->validate($command);

        if (null === ($paymentId = $command->paymentId())) {
            return;
        }

        $payment = $this->paymentRepository->find($paymentId);

        if (!($payment instanceof PaymentInterface)) {
            return;
        }

        if (!isset($payment->getDetails()['quickpayPaymentId'])) {
            return;
        }

        if (null === $paymentMethod = $payment->getMethod()) {
            return;
        }

        Assert::isInstanceOf($paymentMethod, PaymentMethodInterface::class);

        $gatewayConfig = $paymentMethod->getGatewayConfig();

        if (null === $gatewayConfig || $gatewayConfig->getFactoryName() !== 'quickpay') {
            return;
        }

        $params = [
            'quickpayPaymentId' => $payment->getDetails()['quickpayPaymentId'],
            'amount' => $command->amount(),
        ];

        $gatewayFactory = $this->payum->getGatewayFactory('quickpay');
        $gateway = $gatewayFactory->create($gatewayConfig->getConfig());
        $gateway->execute(new Refund($params));
    }
}
