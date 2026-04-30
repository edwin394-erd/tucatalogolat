<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination;

    public $model;
    public $columns;
    public $column_names;
    public $search = '';
    public $sortBy = 'id';
    public $sortDirection = 'asc';
    public $filter_field;
    public $filter_value;
    public $table_type;
    public $route_name;
    public $searching_exceptions = ['foto'];
    public $titulo = '';



    public function mount($model, $columns, $column_names = null, $filter_field = null, $filter_value = null, $table_type = null, $searching_exceptions = [], $titulo = '')
    {
        // Asigna las propiedades del componente
        $this->model = $model;
        $this->columns = $columns;
        $this->column_names = $column_names ?? $columns;
        $this->titulo = $titulo;
        $this->filter_field = $filter_field;
        $this->filter_value = $filter_value;
        $this->table_type = $table_type;

        $this->route_name = $model === 'Product' ? 'products' : ($model === 'Category' ? 'categories' : ($model === 'Descuento' ? 'descuentos' : ($model === 'Subscription' ? 'subscripciones' : ($model === 'Plan' ? 'planes' : 'items'))));

        // $this->route_name = $model === 'Product' ? 'products' : ($model === 'Category' ? 'categories' : ($model === 'Descuento' ? 'descuentos' : 'items'));
        $this->searching_exceptions = $searching_exceptions;
    }

    /**
     * Resetea la página de la paginación cuando se actualiza la búsqueda.
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Ordena la tabla por la columna especificada.
     */
    public function sortBy($column)
    {
        // Si la columna es la misma, cambia la dirección del orden
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Si la columna es diferente, establece la dirección a ascendente por defecto
            $this->sortDirection = 'asc';
        }
        $this->sortBy = $column;
    }

    /**
     * Elimina un registro de la tabla por su ID.
     */
    public function delete($id)
    {
        // Construye el nombre de la clase del modelo dinámicamente
        $modelClass = '\\App\\Models\\' . $this->model;
        $item = $modelClass::find($id);

        if ($item) {
            $item->delete();
            $this->render();
            session()->flash('message', 'Registro eliminado correctamente.');
            // Redirige a la URL anterior. No es necesario `navigate: true` aquí.
        }
        return $this->redirect(route($this->route_name), navigate: true);
    }

    /**
     * Renderiza la vista del componente.
     */
    public function render()
    {
        $modelClass = '\\App\\Models\\' . $this->model;
        $query = $modelClass::orderBy('created_at', 'desc');

        // Aplica el filtro si está configurado
        if ($this->filter_field && $this->filter_value) {
            $query->where($this->filter_field, $this->filter_value);
        }

        // Aplica la búsqueda si hay un término
        if ($this->search) {
            // Búsqueda en múltiples columnas usando una cláusula "where" anidada
            $query->where(function($q) {
                foreach ($this->columns as $column) {
                   if (in_array($column, $this->searching_exceptions)) continue;

                    if ($column === 'category_id') {
                        $q->orWhereHas('category', function($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                    } elseif ($column === 'user_id') {
                        $q->orWhereHas('user', function($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                    } elseif ($column === 'plan_id') {
                        $q->orWhereHas('plan', function($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                    }

                    else {
                        $q->orWhere($column, 'like', '%' . $this->search . '%');
                    }
                }
            });
        }

        // Obtiene los elementos paginados y ordenados
        $items = $query->orderBy($this->sortBy, $this->sortDirection)
                       ->paginate(8);
        
        return view('livewire.table', [
            'items' => $items,
            'column_names' => $this->column_names,
            'columns' => $this->columns,
            'sortBy' => $this->sortBy,
            'sortDirection' => $this->sortDirection,
            'table_type' => $this->table_type,
        ]);
    }
}