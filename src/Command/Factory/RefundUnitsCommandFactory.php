<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\Command\Factory;

use Setono\SyliusQuickpayRefundBridgePlugin\Command\RefundUnits;
use Sylius\RefundPlugin\Creator\RefundUnitsCommandCreatorInterface;
use Symfony\Component\HttpFoundation\Request;

final class RefundUnitsCommandFactory implements RefundUnitsCommandFactoryInterface
{
    /** @var RefundUnitsCommandCreatorInterface */
    private $baseRefundUnitsCommandFactory;

    public function __construct(RefundUnitsCommandCreatorInterface $baseRefundUnitsCommandFactory)
    {
        $this->baseRefundUnitsCommandFactory = $baseRefundUnitsCommandFactory;
    }

    public function fromRequest(Request $request): RefundUnits
    {
        $quickpayPaymentId = $request->request->get('sylius_refund_quickpay_payment');

        if (null !== $quickpayPaymentId) {
            $quickpayPaymentId = (int) $quickpayPaymentId;
        }

        return new RefundUnits($this->baseRefundUnitsCommandFactory->fromRequest($request), $quickpayPaymentId);
    }
}
