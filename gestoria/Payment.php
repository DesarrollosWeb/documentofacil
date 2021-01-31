<?php

use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;

include_once 'constants.php';
include_once 'vendor/autoload.php';

class Payment
{
    private static ?Payment $instance = null;

    public function __construct()
    {
        //do nothing
    }

    public static function getInstance(): Payment
    {
        if (static::$instance == null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Creates a payment intent for Stripe
     * @param array $info payment info
     * @return string|PaymentIntent returns a payment intent or an erro messas
     */
    public static function create_payment_intent(array $info)
    {
        Stripe::setApiKey(STRIPE_API_SECRET);
        try {
            return PaymentIntent::create([
                'description' => $info["description"],
                'amount' => filter_var($info["amount"], FILTER_SANITIZE_NUMBER_FLOAT),
                'currency' => 'eur',
                'metadata' => ['integration_check' => 'accept_a_payment'],
            ]);
        } catch (ApiErrorException $e) {
            return $e->getMessage();
        }
    }
}