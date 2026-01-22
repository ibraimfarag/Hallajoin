<?php

namespace Modules\Booking\Gateways;

class StripeGateway extends StripeCheckoutGateway
{
    protected $id = 'stripe';

    public $name = 'Stripe Checkout';

    protected $gateway;
}
