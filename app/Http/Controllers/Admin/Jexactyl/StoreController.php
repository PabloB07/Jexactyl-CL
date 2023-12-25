<?php

namespace Pterodactyl\Http\Controllers\Admin\Jexactyl;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Exceptions\Model\DataValidationException;
use Pterodactyl\Exceptions\Repository\RecordNotFoundException;
use Pterodactyl\Http\Requests\Admin\Jexactyl\StoreFormRequest;
use Pterodactyl\Contracts\Repository\SettingsRepositoryInterface;

class StoreController extends Controller
{
    /**
     * StoreController constructor.
     */
    public function __construct(
        private AlertsMessageBag $alert,
        private SettingsRepositoryInterface $settings
    ) {
    }

    /**
     * Render the Jexactyl store settings interface.
     */
    public function index(): View
    {
        $prefix = 'jexactyl::store:';

        $currencies = [];
        foreach (config('store.currencies') as $key => $value) {
            $currencies[] = ['code' => $key, 'name' => $value];
        }

        return view('admin.jexactyl.store', [
            'enabled' => $this->settings->get($prefix . 'enabled', false),
            'paypal_enabled' => $this->settings->get($prefix . 'paypal:enabled', false),
            'stripe_enabled' => $this->settings->get($prefix . 'stripe:enabled', false),
            'mpago_enabled' => $this->settings->get($prefix . 'mpago:enabled', false),
            'mpago_discord_webhook' => $this->settings->get($prefix . 'mpago:discord:webhook', 0),
            'mpago_discord_enabled' => $this->settings->get($prefix . 'mpago:discord:enabled', false),
            'store_images_one' => $this->settings->get($prefix . 'images:one', 0),
            'store_images_two' => $this->settings->get($prefix . 'images:two', 0),
            'store_images_three' => $this->settings->get($prefix . 'images:three', 0),

            'selected_currency' => $this->settings->get($prefix . 'currency', 'BRL'),
            'currencies' => $currencies,

            'earn_enabled' => $this->settings->get('jexactyl::earn:enabled', false),
            'earn_amount' => $this->settings->get('jexactyl::earn:amount', 1),

            'cpu' => $this->settings->get($prefix . 'cost:cpu', 100),
            'memory' => $this->settings->get($prefix . 'cost:memory', 50),
            'disk' => $this->settings->get($prefix . 'cost:disk', 25),
            'slot' => $this->settings->get($prefix . 'cost:slot', 250),
            'port' => $this->settings->get($prefix . 'cost:port', 20),
            'backup' => $this->settings->get($prefix . 'cost:backup', 20),
            'database' => $this->settings->get($prefix . 'cost:database', 20),

            'limit_cpu' => $this->settings->get($prefix . 'limit:cpu', 100),
            'limit_memory' => $this->settings->get($prefix . 'limit:memory', 4096),
            'limit_disk' => $this->settings->get($prefix . 'limit:disk', 10240),
            'limit_port' => $this->settings->get($prefix . 'limit:port', 1),
            'limit_backup' => $this->settings->get($prefix . 'limit:backup', 1),
            'limit_database' => $this->settings->get($prefix . 'limit:database', 1),
        ]);
    }

    /**
     * Handle settings update.
     *
     * @throws DataValidationException
     * @throws RecordNotFoundException
     */
    public function update(StoreFormRequest $request): RedirectResponse
    {
        foreach ($request->normalize() as $key => $value) {
            $this->settings->set('jexactyl::' . $key, $value);
        }

        $this->alert->success('Se você tiver ativado um gateway de pagamento, lembre-se de configurá-los. <a href="https://nextpanel.com.br">Documentação</a>')->flash();

        return redirect()->route('admin.jexactyl.store');
    }
}
