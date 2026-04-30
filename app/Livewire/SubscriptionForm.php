<?php

namespace App\Livewire;

use Livewire\Component;

class SubscriptionForm extends Component
{
    public $ItemId;
    public $user_id;
    public $plan_id;
    public $estado;
    public $fecha_de_inicio;
    public $fecha_de_corte;
    
    protected $rules = [
        'user_id' => 'required|integer',
        'plan_id' => 'required|integer',
        // 'estado' => 'required|string|max:255',
        'fecha_de_inicio' => 'required|date',
        'fecha_de_corte' => 'required|date|after_or_equal:fecha_de_inicio',
    ];

    protected $messages = [
        'user_id.required' => 'El ID del usuario es obligatorio.',
        'user_id.integer' => 'El ID del usuario debe ser un número entero.',
        'estado.required' => 'El estado es obligatorio.',
        'estado.string' => 'El estado debe ser una cadena de texto.',
        'estado.max' => 'El estado no puede tener más de 255 caracteres.',
        'fecha_de_inicio.required' => 'La fecha de inicio es obligatoria.',
        'fecha_de_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
        'fecha_de_corte.required' => 'La fecha de corte es obligatoria.',
        'fecha_de_corte.date' => 'La fecha de corte debe ser una fecha válida.',
        'fecha_de_corte.after_or_equal' => 'La fecha de corte debe ser igual o posterior a la fecha de inicio.',
    ];

    public function mount($ItemId = null){
        $this->ItemId = $ItemId;

        if ($this->ItemId) {
            $subscription = \App\Models\Subscription::find($this->ItemId);
            if ($subscription) {
                $this->user_id = $subscription->user_id;
                $this->plan_id = $subscription->plan_id;
                $this->estado = $subscription->status;
                $this->fecha_de_inicio = optional($subscription->starts_at)->format('Y-m-d');
                $this->fecha_de_corte = optional($subscription->expires_at)->format('Y-m-d');
            }
        }else {
            $this->user_id = '';
            $this->plan_id = '';
            $this->estado = '';
            $this->fecha_de_inicio = date('Y-m-d');
            $this->fecha_de_corte = date('Y-m-d', strtotime('+1 month'));
        }
    }

    public function render()
    {
        $users = \App\Models\User::all();
        $plans = \App\Models\Plan::all();
        return view('livewire.subscription-form', compact('users', 'plans'));
    }

    public function save()
    {
        $this->validate();

        if ($this->ItemId) {
            $subscription = \App\Models\Subscription::find($this->ItemId);
            if ($subscription) {
                $subscription->update([
                    'user_id' => $this->user_id,
                    'plan_id' => $this->plan_id,
                    'starts_at' => $this->fecha_de_inicio,
                    'expires_at' => $this->fecha_de_corte,
                ]);
            }
            redirect()->route('subscripciones');
            session()->flash('message', 'Subscripción actualizada con éxito.');
        } else {
            \App\Models\Subscription::create([
                'user_id' => $this->user_id,
                'plan_id' => $this->plan_id,
                'starts_at' => $this->fecha_de_inicio,
                'expires_at' => $this->fecha_de_corte,
            ]);
                redirect()->route('subscripciones');
            session()->flash('message', 'Subscripción creada con éxito.');
        }
    }
}
