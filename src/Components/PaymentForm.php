<?php

namespace alecgarcia\LunarAuthorizeNet\Components;

use alecgarcia\LunarAuthorizeNet\AuthorizeNet;
use Livewire\Component;
use Lunar\Models\Cart;

class PaymentForm extends Component
{
    /**
     * The instance of the cart.
     */
    public Cart $cart;

    /**
     * The return URL on a successful transaction
     */
    public string $returnUrl;

    /**
     * The policy for handling payments.
     */
    public string $policy;

    protected $listeners = [
        'cardDetailsSubmitted',
    ];

    public function mount(): void
    {
        $this->policy = config('lunar-authorizenet.policy', 'automatic');
    }

    public function rules(): array
    {
        return [
            'identifier' => 'string|required',
        ];
    }

    public function render()
    {
        return view('alecgarcia::authorizenet.components.payment-form');
    }
}
