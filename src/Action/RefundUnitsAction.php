<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\Action;

use Psr\Log\LoggerInterface;
use Setono\SyliusQuickpayRefundBridgePlugin\Command\Factory\RefundUnitsCommandFactoryInterface;
use Sylius\RefundPlugin\Exception\InvalidRefundAmountException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class RefundUnitsAction
{
    private MessageBusInterface $commandBus;

    private SessionInterface $session;

    private UrlGeneratorInterface $router;

    private RefundUnitsCommandFactoryInterface $commandFactory;

    private LoggerInterface $logger;

    public function __construct(
        MessageBusInterface $commandBus,
        SessionInterface $session,
        UrlGeneratorInterface $router,
        RefundUnitsCommandFactoryInterface $commandFactory,
        LoggerInterface $logger
    ) {
        $this->commandBus = $commandBus;
        $this->session = $session;
        $this->router = $router;
        $this->commandFactory = $commandFactory;
        $this->logger = $logger;
    }

    public function __invoke(Request $request): Response
    {
        try {
            $this->commandBus->dispatch($this->commandFactory->fromRequest($request));

            $this->addFlash('success', 'sylius_refund.units_successfully_refunded');
        } catch (InvalidRefundAmountException $exception) {
            $this->addFlash('error', $exception->getMessage());

            $this->logger->error($exception->getMessage());
        } catch (HandlerFailedException $exception) {
            $previousException = $exception->getPrevious();

            $this->provideErrorMessage($previousException);

            $this->logger->error($previousException === null ? 'An error occurred' : $previousException->getMessage());
        }

        return new RedirectResponse($this->router->generate(
            'sylius_refund_order_refunds_list', ['orderNumber' => $request->attributes->get('orderNumber')]
        ));
    }

    private function provideErrorMessage(?\Throwable $previousException): void
    {
        if ($previousException instanceof InvalidRefundAmountException) {
            $this->addFlash('error', $previousException->getMessage());

            return;
        }

        $this->addFlash('error', 'sylius_refund.error_occurred');
    }

    private function addFlash(string $type, string $message): void
    {
        if (!$this->session instanceof Session) {
            return;
        }

        $this->session->getFlashBag()->add($type, $message);
    }
}
