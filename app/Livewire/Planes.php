<?php

namespace App\Livewire;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Planes extends Component
{
    use WithFileUploads;

    public $model;
    public $proof;

    protected $rules = [
        'proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
    ];

    protected $messages = [
        'proof.required' => 'Debes seleccionar el comprobante de pago.',
        'proof.file' => 'El comprobante seleccionado no es un archivo válido.',
        'proof.mimes' => 'El comprobante debe estar en formato JPG, PNG o PDF.',
        'proof.max' => 'El comprobante no puede superar los 5 MB.',
    ];

    public function updatedProof(): void
    {
        $this->validateOnly('proof');
    }

    public function subscribe($planId)
    {
        $plan = Plan::find($planId);
        if (!$plan) {
            $this->dispatch('alert', type: 'error', message: 'Plan no encontrado.');
            return;
        }

        $user = auth()->user();
        if (!$user) {
            $this->dispatch('alert', type: 'error', message: 'Usuario no autenticado.');
            return;
        }

        $currentSubscription = $user->subscriptions()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->latest('expires_at')
            ->first();

        if ($currentSubscription?->plan_id === $plan->id) {
            $this->dispatch('alert', type: 'info', message: 'Ya tienes este plan activo.');
            return;
        }

        $this->validate();

        $existingRequest = $user->subscriptions()
            ->where('plan_id', $plan->id)
            ->where('payment_status', 'pending')
            ->latest()
            ->first();

        if ($existingRequest) {
            $this->dispatch('alert', type: 'info', message: 'Ya tienes un comprobante pendiente de revisión para este plan.');
            return;
        }

        $proofPath = $this->proof->store('subscription-proofs', 'public');
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_proof_path' => $proofPath,
            'payment_submitted_at' => now(),
        ]);

        $this->notifyTelegram($subscription);
        $this->reset('proof');
        $this->dispatch('alert', type: 'success', message: 'Comprobante enviado. Te avisaremos cuando sea revisado.');
    }

    private function notifyTelegram(Subscription $subscription): void
    {
        $token = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');
        $proofPath = Storage::disk('public')->path($subscription->payment_proof_path);

        if (blank($token) || blank($chatId) || ! is_file($proofPath)) {
            Log::warning('Telegram subscription notification skipped', ['subscription_id' => $subscription->id]);
            return;
        }

        $user = $subscription->user;
        $plan = $subscription->plan;
        $message = "Nuevo comprobante de suscripción\n\n"
            . "Usuario: {$user->name} ({$user->email})\n"
            . "Plan: {$plan->name}\n"
            . "Precio: {$plan->price}\n"
            . "Solicitud: #{$subscription->id}";
        $keyboard = json_encode(['inline_keyboard' => [[
            ['text' => 'Aceptar', 'callback_data' => "subscription:approve:{$subscription->id}"],
            ['text' => 'Denegar', 'callback_data' => "subscription:deny:{$subscription->id}"],
        ]]], JSON_THROW_ON_ERROR);
        $isImage = in_array(strtolower(pathinfo($proofPath, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'], true);
        $method = $isImage ? 'sendPhoto' : 'sendDocument';
        $field = $isImage ? 'photo' : 'document';

        try {
            Http::timeout(15)
                ->attach($field, fopen($proofPath, 'r'), basename($proofPath))
                ->post("https://api.telegram.org/bot{$token}/{$method}", [
                    'chat_id' => $chatId,
                    'caption' => $message,
                    'reply_markup' => $keyboard,
                ])
                ->throw();
        } catch (\Throwable $exception) {
            Log::warning('Telegram subscription notification failed', [
                'subscription_id' => $subscription->id,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    public function hola()
    {
        session()->flash('message', 'Hola desde Livewire!');
        return redirect()->route('planes');
    }
    
    public function render()
    {
        $currentSubscription = auth()->user()?->subscriptions()
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->latest('expires_at')
            ->first();

        $latestPaymentRequest = auth()->user()?->subscriptions()
            ->with('plan')
            ->whereIn('payment_status', ['pending', 'rejected'])
            ->whereNotNull('payment_submitted_at')
            ->latest('payment_submitted_at')
            ->first();

        return view('livewire.planes')
        ->extends('layouts.auth2')
        ->section('content')
        ->with('model', $this->model)
        ->with('currentSubscription', $currentSubscription)
        ->with('latestPaymentRequest', $latestPaymentRequest)
        ->with('binance', config('services.binance'));
    }
}
