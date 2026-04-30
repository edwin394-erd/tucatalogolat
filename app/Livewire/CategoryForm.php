<?php

namespace App\Livewire;

use Livewire\Component;

class CategoryForm extends Component
{
    public $ItemId;
    public $name;
    public $description;

   

    public function rules(): array
{
    return [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];
}

public function messages(): array
{
    return [
        'name.required' => __('messages.category_name_required'),
        'name.string' => __('messages.category_name_string'),
        'name.max' => __('messages.category_name_max'),
        'description.string' => __('messages.category_description_string'),
    ];
}

    public function mount($ItemId = null)
    {
        $this->ItemId = $ItemId;

        if ($this->ItemId) {
            $category = \App\Models\Category::find($this->ItemId);
            if ($category) {
                $this->name = $category->name;
                $this->description = $category->description;
            }
        }else {
            $this->name = '';
            $this->description = '';
        }
    }

    public function render()
    {
        return view('livewire.category-form');
    }

    public function save()
    {

        $this->validate();

        if ($this->ItemId) {
            $category = \App\Models\Category::find($this->ItemId);
            if ($category) {
                $category->update([
                    'name' => $this->name,
                    'description' => $this->description,
                ]);
            }
            session()->flash('message', __('messages.category_updated'));
        } else {
            \App\Models\Category::create([
                'name' => $this->name,
                'description' => $this->description,
                'catalogo_id' => auth()->user()->catalogo->id,
        ]);

        session()->flash('message', __('messages.category_created'));
        
        $this->redirectRoute('categories');
    }
    

}
}
