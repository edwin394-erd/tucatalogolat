<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Plan;

class PlanForm extends Component
{
    public $ItemId;
    public $name;
    public $description;
    public $price;
    public $max_products;
    public $duration_in_days;
    public $is_active;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'max_products' => 'required|integer|min:0',
        'duration_in_days' => 'required|integer|min:1',
        'is_active' => 'required|boolean',
    ];

    public function mount($ItemId = null)
    {
        $this->ItemId = $ItemId;

        if ($this->ItemId) {
            $plan = Plan::find($this->ItemId);
            if ($plan) {
                $this->name = $plan->name;
                $this->description = $plan->description;
                $this->price = $plan->price;
                $this->max_products = $plan->max_products;
                $this->duration_in_days = $plan->duration_in_days;
                $this->is_active = $plan->is_active;
            }
        } else {
            $this->name = '';
            $this->description = '';
            $this->price = '';
            $this->max_products = '';
            $this->duration_in_days = '';
            $this->is_active = true;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->ItemId) {
            $plan = Plan::find($this->ItemId);
            $plan->update([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'max_products' => $this->max_products,
                'duration_in_days' => $this->duration_in_days,
                'is_active' => $this->is_active,
            ]);
            session()->flash('message', 'Plan actualizado correctamente.');
        } else {
            Plan::create([
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'max_products' => $this->max_products,
                'duration_in_days' => $this->duration_in_days,
                'is_active' => $this->is_active,
            ]);
            session()->flash('message', 'Plan creado correctamente.');
        }

        return redirect()->route('planes');
    }

    public function render()
    {
        return view('livewire.plan-form');
    }
}