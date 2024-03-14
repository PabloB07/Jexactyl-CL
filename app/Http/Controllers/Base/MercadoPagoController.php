<?php

namespace Pterodactyl\Http\Controllers\Base;

use MercadoPago\SDK;
use MercadoPago\Payment;
use Illuminate\Http\Request;
use Pterodactyl\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Contracts\Repository\SettingsRepositoryInterface;

class MercadoPagoController extends Controller
{
    public function __construct(
        private SettingsRepositoryInterface $settings
    ) {
    }

    public function index(Request $request): Response
    {
        $topic = $request->input('topic');

        if ($topic === 'merchant_order') {
            $msg = 'Ignorado';
            $status = '200';
        } elseif ($topic === 'payment') {
            $msg = 'Ignorado';
            $status = '200';
        } else {
            try {
                $notificationId = $request->input('data.id') ?? $request->input('id') ?? $request->input('payment_id') ?? 'error12011';
                if ($notificationId == 'error12011') {
                    $msg = 'Error de proceso de pago: ID de pago no encontrado.'; // caso ID não exista, mostra essa mensagem
                } elseif ($notificationId == '123456') {
                    $msg = $this->TestePayment(); // Redireciona para o Evento de teste
                    $status = '200';
                } else {
                    $result = $this->EventPayment($notificationId); // Redireciona para o Evento de pagamento
                    $msg = $result['message'];
                    $status = $result['status'];
                }
            } catch (\Exception $e) {
                Log::channel('mpago')->error("Erro {$e}");
                $msg = "Falhou - {$e}";
                $status = '401';
            }
        }

        return new Response($msg, $status);
    }

    private function TestePayment(): string
    {
        return 'Metodo de teste solicitado.';
    }

    private function EventPayment($notificationId): array
    {
        SDK::setAccessToken(config('gateways.mpago.access_token'));

        $payment = Payment::find_by_id($notificationId) ?? 'Desconhecido';
        $credit_amount = $payment->metadata->credit_amount ?? 'Desconhecido';

        $ErrorManager = 'Nada';

        // Verificação Payment
        if ($payment == 'Desconhecido') { // Lida com falhas no carregamento do id do payment
            $ErrorManager = 'PaymentError';
            $statusType = '400';
        }

        $status = $payment->status ?? 'Desconhecido';

        // Verificação Payment status
        if ($status == 'Desconhecido') { // Lida com falhas no carregamento do status do payment
            $ErrorManager = 'PaymentStatusError';
            $statusType = '400';
        }

        $metadata_token = $payment->metadata->internal_token ?? 'Desconhecido';

        // Verificação Payment Metadata Token
        if ($metadata_token == 'Desconhecido') {
            $ErrorManager = 'MetadataTokenError';
            $statusType = '400';
        }

        $data = DB::table('mpago')->where('internal_token', $metadata_token)->first() ?? 'Inexistente';

        if ($data == 'Inexistente') {
            if ($metadata_token !== 'Desconhecido') {
                try {
                    DB::table('mpago')->insert([
                        'internal_status' => 'Criado',
                        'internal_token' => $metadata_token,
                    ]);
                    $data = DB::table('mpago')->where('internal_token', $metadata_token)->first();
                } catch (\Exception $e) {
                    Log::channel('mpago')->error("Database ERRO: {$e}");
                    $ErrorManager = 'DatabaseVerifyError';
                    $statusType = '400';
                }
            } else {
                $ErrorManager = 'MetadataTokenError';
                $statusType = '400';
            }
        }

        $internalStatus = $data->internal_status ?? 'Desconhecido';

        if ($internalStatus == 'Desconhecido') {
            $ErrorManager = 'InternalDatabaseError';
            $statusType = '400';
        }

        if ($ErrorManager == 'PaymentError') {
            $Message = 'Pagamento Não encontrado.';
            $statusType = '400';
        }

        if ($ErrorManager == 'PaymentStatusError') {
            $Message = 'O Status do Pagamento é Invalido.';
            $statusType = '400';
        }

        if ($ErrorManager == 'MetadataTokenError') {
            $Message = 'O Token da metadata é invalido, póssivel tentativa de Fraude.';
            $statusType = '400';
        }

        if ($ErrorManager == 'DatabaseVerifyError') {
            $Message = 'Ocorreu uma falha ao Tentar Validar o Pagamento.';
            $statusType = '400';
        }

        if ($ErrorManager == 'InternalDatabaseError') {
            $Message = 'Erro interno: Entre em contato com um Administrador para verificar sua Compra.';
            $statusType = '400';
        }

        if ($ErrorManager === 'Nada') {
            $Message = 'Nada';
            // Inicia O Setamento do pagamento
            if ($status === 'created') {
                $Message = 'O pagamento foi Criado.';
                $statusType = '200';
            }

            if ($status === 'pending') {
                DB::table('mpago')->where('internal_token', $metadata_token)->update(['internal_status' => 'Pendente']);
                $Message = 'O pagamento está pendente.';
                $statusType = '200';
            }

            if ($status === 'rejected') {
                $Message = 'O pagamento foi rejeitado.';
                $statusType = '200';
            }

            if ($status === 'cancelled') {
                DB::table('mpago')->where('internal_token', $metadata_token)->update(['internal_status' => 'Cancelado']);
                $Message = 'O pagamento foi cancelado.';
                $statusType = '200';
            }

            if ($status === 'refunded') {
                $Message = 'O pagamento foi reembolsado.';
                $statusType = '200';
            }

            if ($status === 'charged_back') {
                $Message = 'O pagamento foi estornado.';
                $statusType = '200';
            }

            if ($status === 'in_process') {
                $Message = 'O pagamento está em processo de validação.';
                $statusType = '200';
            }

            if ($status === 'in_mediation') {
                $Message = 'O pagamento está em mediação.';
                $statusType = '200';
            }

            if ($Message === 'Nada') {
                if ($internalStatus === 'Finalizado') {
                    $Message = 'Este Pago ya fue Finalizado.';
                    $statusType = '200';
                } elseif ($internalStatus == 'Cancelado') {
                    $Message = 'Este Pago fue Cancelado.';
                    $statusType = '200';
                } else {
                    if ($status === 'approved') {
                        $bal = User::query()->select('store_balance')->where('id', '=', $payment->metadata->user_id)->first()->store_balance;
                        User::query()->where('id', '=', $payment->metadata->user_id)->update(['store_balance' => $bal + $payment->metadata->credit_amount]);
                        DB::table('mpago')->where('internal_token', $metadata_token)->update(['internal_status' => 'Finalizado']);
                        $Message = 'Sucesso - Créditos Adicionados';
                        $statusType = '200';
                    } else {
                        $Message = "Erro desconhecido , status - $status";
                        $statusType = '500';
                    }
                }
            }
        }

        try {
            if ($this->settings->get('jexactyl::store:mpago:discord:enabled') === 'true' && $this->settings->get('jexactyl::store:mpago:discord:webhook')) {
                $Content = [
                    'pagamento' => [
                        'payment_locale' => $payment->metadata->payment_locale ?? 'Jexactyl',
                        'user_email' => $payment->metadata->user_email ?? 'Desconhecido(ERRO)',
                        'user_id' => $payment->metadata->user_id ?? 'Desconhecido(ERRO)',
                        'valor' => $credit_amount ?? 'Desconhecido(ERRO)',
                        'payment_id' => $notificationId ?? 'Desconhecido(ERRO)',
                        'metadata_token' => $metadata_token ?? 'Desconhecido(ERRO)',
                        'message' => $Message ?? 'Desconhecido(ERRO)',
                    ],
                ];
                $this->sendDiscordWebhook($Content);
            }
        } catch (\Exception $e) {
            Log::channel('mpago')->error("Discord ERRO: {$e}");
        }

        // Retorna as duas variantes como um array associativo
        return [
            'message' => $Message,
            'status' => $statusType,
        ];
    }

    private function sendDiscordWebhook($Content)
    {
        $metadata_token = $Content['pagamento']['metadata_token'];
        $data = DB::table('mpago')->where('internal_token', $metadata_token)->first();
        $internalStatus = $data->internal_status ?? 'Desconhecido';

        if ($internalStatus == 'Desconocido') {
            $description = 'Error Desconocido.';
            $color = '16711680'; // Vermelho
        } elseif ($internalStatus == 'Criado') {
            $description = 'Nuevo Pago fue creado.';
            $color = '16776960'; // Amarelo
        } elseif ($internalStatus == 'Cancelado') {
            $description = 'Pago Cancelado.';
            $color = '16711680'; // Vermelho
        } elseif ($internalStatus == 'Pendente') {
            $description = 'Pago Pendiente.';
            $color = '16776960'; // Amarelo
        } elseif ($internalStatus == 'Finalizado') {
            $description = 'Pago concluido.';
            $color = '65280'; // Verde
        } else {
            $description = 'Pagos ?';
            $color = '16711680'; // Vermelhor
        }

        $name = config('app.name', 'Jexactyl') . ' -  Mercado Pago IPN';

        if (!str_starts_with(config('app.logo'), 'https://') && !str_starts_with(config('app.logo'), 'http://')) {
            $icon = 'https://avatars.githubusercontent.com/u/91636558';
        } else {
            $icon = config('app.logo');
        }

        $webhookData = [
            'username' => $name,
            'avatar_url' => $icon,
            'embeds' => [
                [
                    'title' => 'Mercado Pago IPN',
                    'color' => $color,
                    'description' => $description,
                    'fields' => [
                        [
                            'name' => 'Local de pago:',
                            'value' => $Content['pagamento']['payment_locale'],
                        ],
                        [
                            'name' => 'ID do Usuario:',
                            'value' => $Content['pagamento']['user_id'],
                        ],
                        [
                            'name' => 'Email de usuario:',
                            'value' => $Content['pagamento']['user_email'],
                        ],
                        [
                            'name' => 'Valor de creditos:',
                            'value' => $Content['pagamento']['valor'],
                        ],
                        [
                            'name' => 'Payment ID:',
                            'value' => $Content['pagamento']['payment_id'],
                        ],
                        [
                            'name' => 'Token_Metadata:',
                            'value' => $metadata_token,
                        ],
                        [
                            'name' => 'Mensaje:',
                            'value' => $Content['pagamento']['message'],
                        ],
                    ],
                    'footer' => ['text' => $name, 'icon_url' => $icon],
                    'timestamp' => date('c'),
                ],
            ],
        ];

        try {
            Http::withBody(json_encode($webhookData), 'application/json')->post($this->settings->get('jexactyl::store:mpago:discord:webhook'));
        } catch (\Exception $e) {
            Log::channel('mpago')->error("Discord ERRO: {$e}");
        }
    }
}
