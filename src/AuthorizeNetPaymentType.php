<?php

namespace alecgarcia\LunarAuthorizeNet;

use Lunar\Base\DataTransferObjects\PaymentAuthorize;
use Lunar\Base\DataTransferObjects\PaymentCapture;
use Lunar\Base\DataTransferObjects\PaymentCheck;
use Lunar\Base\DataTransferObjects\PaymentChecks;
use Lunar\Base\DataTransferObjects\PaymentRefund;
use Lunar\PaymentTypes\AbstractPayment;
use Lunar\Models\Transaction;

class AuthorizeNetPaymentType extends AbstractPayment
{
    protected $authorizeNet;

    protected $policy;

    public function __construct()
    {
        $this->authorizeNet = '';

        $this->policy = config('lunar-authorizenet.policy', 'automatic');
    }

    /**
     * Authorize the payment for processing.
     */
    final public function authorize(): ?PaymentAuthorize
    {

    }

    /**
     * Capture a payment for a transaction.
     *
     * @param  int  $amount
     */
    public function capture(Transaction $transaction, $amount = 0): PaymentCapture
    {

    }

    /**
     * Refund a captured transaction
     *
     * @param  string|null  $notes
     */
    public function refund(Transaction $transaction, int $amount = 0, $notes = null): PaymentRefund
    {

    }

    protected function storeTransaction($transaction, $success = false)
    {
        $this->order->transactions()->create([
            'success' => $success,
            'type' => $transaction->transactionType == 'Payment' ? 'capture' : 'intent',
            'driver' => 'opayo',
            'amount' => $transaction->amount->totalAmount,
            'reference' => $transaction->transactionId,
            'status' => $transaction->status,
            'notes' => $transaction->statusDetail,
            'card_type' => $transaction->paymentMethod->card->cardType,
            'last_four' => $transaction->paymentMethod->card->lastFourDigits,
            'captured_at' => $success ? ($transaction->transactionType == 'Payment' ? now() : null) : null,
            'meta' => [
                'threedSecure' => [
                    'status' => $transaction->avsCvcCheck->status,
                    'address' => $transaction->avsCvcCheck->address,
                    'postalCode' => $transaction->avsCvcCheck->postalCode,
                    'securityCode' => $transaction->avsCvcCheck->securityCode,
                ],
            ],
        ]);
    }
}
