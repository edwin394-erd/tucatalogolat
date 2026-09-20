<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $secret = config('services.telegram.webhook_secret');
        if (blank($secret) || ! hash_equals($secret, (string) $request->header('X-Telegram-Bot-Api-Secret-Token'))) {
            abort(403);
        }

        $callback = $request->input('callback_query');
        if (! is_array($callback)) {
            return response()->json(['ok' => true]);
        }

        [$action, $subscriptionId] = array_pad(explode(':', (string) data_get($callback, 'data')), 3, null);
        if ($action !== 'subscription' || ! in_array($subscriptionId, ['approve', 'deny'], true)) {
            return response()->json(['ok' => true]);
        }

        $parts = explode(':', (string) data_get($callback, 'data'));
        $id = isset($parts[2]) ? (int) $parts[2] : 0;
        $subscription = Subscription::with('user', 'plan')->find($id);

        if (! $subscription || $subscription->payment_status !== 'pending') {
            $this->answerCallback($callback, 'Esta solicitud ya fue revisada.');
            return response()->json(['ok' => true]);
        }

        if ($subscriptionId === 'approve') {
            $subscription->update([
                'status' => 'active',
                'payment_status' => 'approved',
                'starts_at' => now(),
                'expires_at' => now()->addDays($subscription->billing_period === 'annual' ? 365 : 30),
                'payment_reviewed_at' => now(),
            ]);
            $text = "Solicitud #{$subscription->id} aprobada para {$subscription->user->name}.";
        } else {
            $subscription->update([
                'status' => 'pending',
                'payment_status' => 'rejected',
                'payment_reviewed_at' => now(),
            ]);
            $text = "Solicitud #{$subscription->id} denegada para {$subscription->user->name}.";
        }

        $this->answerCallback($callback, $text);
        $this->editMessage($callback, $text);

        return response()->json(['ok' => true]);
    }

    private function answerCallback(array $callback, string $text): void
    {
        $this->telegram('answerCallbackQuery', [
            'callback_query_id' => data_get($callback, 'id'),
            'text' => $text,
        ]);
    }

    private function editMessage(array $callback, string $text): void
    {
        $message = data_get($callback, 'message');
        if (! is_array($message)) {
            return;
        }

        $this->telegram('editMessageCaption', [
            'chat_id' => data_get($message, 'chat.id'),
            'message_id' => data_get($message, 'message_id'),
            'caption' => data_get($message, 'caption', '') . "\n\n" . $text,
        ]);

        $this->telegram('editMessageReplyMarkup', [
            'chat_id' => data_get($message, 'chat.id'),
            'message_id' => data_get($message, 'message_id'),
            'reply_markup' => json_encode(['inline_keyboard' => []]),
        ]);
    }

    private function telegram(string $method, array $payload): void
    {
        $token = config('services.telegram.bot_token');
        if (blank($token)) {
            return;
        }

        try {
            Http::timeout(10)->post("https://api.telegram.org/bot{$token}/{$method}", $payload)->throw();
        } catch (\Throwable $exception) {
            Log::warning('Telegram callback handling failed', ['message' => $exception->getMessage()]);
        }
    }
}
