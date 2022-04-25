<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\Command\Handler;

use Payum\Core\Registry\RegistryInterface;
use Payum\Core\Request\Refund;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Setono\SyliusQuickpayRefundBridgePlugin\Command\RefundUnits;
use Setono\SyliusQuickpayRefundBridgePlugin\Command\Validator\RefundUnitsCommandValidatorInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Core\Repository\PaymentRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Webmozart\Assert\Assert;

final class RefundUnitsHandler implements MessageHandlerInterface, LoggerAwareInterface
{
    private LoggerInterface $logger;

    private RegistryInterface $payum;

    private RefundUnitsCommandValidatorInterface $commandValidator;

    private PaymentRepositoryInterface $paymentRepository;

    public function __construct(
        RegistryInterface $payum,
        RefundUnitsCommandValidatorInterface $commandValidator,
        PaymentRepositoryInterface $paymentRepository
    ) {
        $this->logger = new NullLogger();
        $this->payum = $payum;
        $this->commandValidator = $commandValidator;
        $this->paymentRepository = $paymentRepository;
    }

    public function __invoke(RefundUnits $command): void
    {
        try {
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
        } catch (\Throwable $e) {
            $this->logger->error(self::exceptionMessage($e));

            throw $e;
        }
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    private static function exceptionMessage(\Throwable $e): string
    {
        return sprintf(
            "[%s] %s\n\nTrace\n---\n%s\n\n",
            get_class($e),
            $e->getMessage(),
            $e->getTraceAsString()
        );
    }
}
