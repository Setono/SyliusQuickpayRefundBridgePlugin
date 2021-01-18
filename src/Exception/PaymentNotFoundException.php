<?php

declare(strict_types=1);

namespace Setono\SyliusQuickpayRefundBridgePlugin\Exception;

use function Safe\sprintf;

final class PaymentNotFoundException extends \InvalidArgumentException
{
    /**
     * @param int|string $id
     */
    public static function withId($id): self
    {
        return new self(sprintf('Payment with ID "%s" was not found.', $id));
    }
}
