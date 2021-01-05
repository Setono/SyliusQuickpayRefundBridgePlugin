<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\Command\Validator;

use Setono\SyliusQuickpayRefundBridgePlugin\Command\RefundUnits;
use Setono\SyliusQuickpayRefundBridgePlugin\Exception\PaymentNotFoundException;
use Setono\SyliusQuickpayRefundBridgePlugin\Exception\UnexpectedPaymentOrderException;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Repository\PaymentRepositoryInterface;
use Sylius\RefundPlugin\Validator\RefundUnitsCommandValidatorInterface as BaseRefundUnitsCommandValidatorInterface;
use Webmozart\Assert\Assert;

final class RefundUnitsCommandValidator implements RefundUnitsCommandValidatorInterface
{
    private BaseRefundUnitsCommandValidatorInterface $baseRefundUnitsCommandValidator;

    private PaymentRepositoryInterface $paymentRepository;

    public function __construct(
        BaseRefundUnitsCommandValidatorInterface $baseRefundUnitsCommandValidator,
        PaymentRepositoryInterface $paymentRepository
    ) {
        $this->baseRefundUnitsCommandValidator = $baseRefundUnitsCommandValidator;
        $this->paymentRepository = $paymentRepository;
    }

    public function validate(RefundUnits $command): void
    {
        $baseCommand = $command->baseCommand();
        $this->baseRefundUnitsCommandValidator->validate($baseCommand);

        Assert::notNull($command->paymentId(), 'Quickpay payment ID is required.');

        /** @var PaymentInterface|null $payment */
        $payment = $this->paymentRepository->find($command->paymentId());

        if (null === $payment) {
            throw PaymentNotFoundException::withId($command->paymentId());
        }

        if (null === $payment->getOrder() || $payment->getOrder()->getNumber() !== $baseCommand->orderNumber()) {
            throw UnexpectedPaymentOrderException::expectedOrder($baseCommand->orderNumber());
        }
    }
}
