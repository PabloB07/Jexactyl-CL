<?php

namespace Pterodactyl\Http\Controllers\Api\Client\Store;

use Stripe\StripeClient;
use Illuminate\Http\JsonResponse;
use Stripe\Exception\ApiErrorException;
use Pterodactyl\Exceptions\DisplayException;
use Pterodactyl\Http\Controllers\Api\Client\ClientApiController;
use Pterodactyl\Http\Requests\Api\Client\Store\Gateways\StripeRequest;

class StripeController extends ClientApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws DisplayException|ApiErrorException
     */
    public function purchase(StripeRequest $request): JsonResponse
    {
        if (!$this->settings->get('jexactyl::store:stripe:enabled')) {
            throw new DisplayException('Não é possível comprar via Stripe: módulo não ativado');
        }

        if (config('gateways.stripe.secret') === '') {
            throw new DisplayException('Não é possível comprar via Stripe: Secret não configurado.');
        }

        if (config('gateways.stripe.webhook_secret') === '') {
            throw new DisplayException('Não é possível comprar via Stripe: WebHook Secret não configurado.');
        }

        $client = new StripeClient(config('gateways.stripe.secret'));
        $amount = $request->input('amount');
        $cost = number_format(config('gateways.cost', 1.00) / 100 * $amount, 2);
        $currency = config('gateways.currency', 'CLP');

        $checkout = $client->checkout->sessions->create([
            'success_url' => config('app.url') . '/store/credits',
            'cancel_url' => config('app.url'),
            'mode' => 'payment',
            'customer_email' => $request->user()->email,
            'metadata' => ['credit_amount' => $amount, 'user_id' => $request->user()->id],
            'line_items' => [
                [
                    'quantity' => 1,
                    'price_data' => [
                        'currency' => $currency,
                        'unit_amount' => str_replace('.', '', $cost),
                        'product_data' => [
                            'name' => $amount . ' Creditos | ' . $this->settings->get('settings::app:name'),
                        ],
                    ],
                ],
            ],
        ]);

        return new JsonResponse($checkout->url, 200, [], null, true);
    }
}
