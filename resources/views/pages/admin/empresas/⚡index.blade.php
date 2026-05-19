<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Empresa;
use App\Models\User;
use Mary\Traits\Toast;

new class extends Component
{
  use WithPagination;
  use Toast;
  // Do not store paginator in a public property (Livewire cannot serialize it)
  public $users = [];
  public $headers = [];
  public $modalOpen = false;
  public $confirmingDelete = false;
  public $deleteId = null;
  public $showDeleted = false;
  public $search = '';
  public $perPage = 10;
  public $perPageValues = [10, 20, 50];

  public $form = [
    'id' => null,
    'nombre' => '',
    'direccion' => '',
    'telefono' => '',
    'email' => '',
    'representante_id' => null,
  ];

  public function mount()
  {
    $this->users = User::orderBy('name')->get();
    $this->headers = [
      ['key' => 'nombre', 'label' => 'Nombre', 'class' => 'w-64'],
      ['key' => 'representante.name', 'label' => 'Representante'],
      ['key' => 'telefono', 'label' => 'Teléfono'],
      ['key' => 'email', 'label' => 'Email'],
    ];
  }

  protected function rules()
  {
    return [
      'form.nombre' => 'required|string|max:255',
      'form.direccion' => 'nullable|string|max:255',
      'form.telefono' => 'nullable|string|max:50',
      'form.email' => 'nullable|email|max:255',
      'form.representante_id' => 'nullable|exists:users,id',
    ];
  }

  public function with(): array
  {
    $query = Empresa::query();

    if ($this->showDeleted) {
      $query = $query->onlyTrashed();
    }

    if ($this->search) {
      $query = $query->where('nombre', 'like', "%{$this->search}%");
    }

    return [
      'empresas' => $query->orderBy('nombre')->paginate($this->perPage),
    ];
  }

  public function updatedSearch()
  {
    $this->resetPage();
  }

  public function updatedPerPage()
  {
    $this->resetPage();
  }

  public function updatedShowDeleted()
  {
    $this->resetPage();
  }

  protected function resetForm()
  {
    $this->form = [
      'id' => null,
      'nombre' => '',
      'direccion' => '',
      'telefono' => '',
      'email' => '',
      'representante_id' => null,
    ];
  }

  public function openCreate()
  {
    $this->resetForm();
    $this->modalOpen = true;
  }

  public function openEdit($id)
  {
    $empresa = Empresa::findOrFail($id);
    $this->form = [
      'id' => $empresa->id,
      'nombre' => $empresa->nombre,
      'direccion' => $empresa->direccion,
      'telefono' => $empresa->telefono,
      'email' => $empresa->email,
      'representante_id' => $empresa->representante_id,
    ];
    $this->modalOpen = true;
  }

  public function save()
  {
    try {
      $validated = $this->validate();

      if ($this->form['id']) {
        $empresa = Empresa::findOrFail($this->form['id']);
        $empresa->update($validated['form']);
        $this->success('Empresa actualizada.');
      } else {
        Empresa::create($validated['form']);
        $this->success('Empresa creada.');
      }

      $this->modalOpen = false;
      $this->resetPage();
    } catch (\Illuminate\Validation\ValidationException $e) {
      $this->error('Corrige los errores del formulario.');
      throw $e;
    } catch (\Exception $e) {
      $this->error('Ocurrió un error inesperado.');
      report($e);
    }
  }

  public function confirmDelete($id)
  {
    $this->confirmingDelete = true;
    $this->deleteId = $id;
  }

  public function delete()
  {
    if ($this->deleteId) {
      $empresa = Empresa::findOrFail($this->deleteId);
      $empresa->delete();
      $this->success('Empresa eliminada.');
      $this->resetPage();
    }
    $this->confirmingDelete = false;
    $this->deleteId = null;
  }

  public function restore($id)
  {
    $empresa = Empresa::withTrashed()->findOrFail($id);
    $empresa->restore();
    $this->success('Empresa restaurada.');
    $this->resetPage();
  }
};
?>

<div>
  <x-header title="Empresas" separator>
    <x-slot:middle class="justify-start">
      <x-input placeholder="Buscar..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
    </x-slot:middle>
    <x-slot:actions>
      <x-button label="Crear Empresa" icon="o-plus" class="btn-primary" wire:click="openCreate" />
      <x-button :label="$showDeleted ? 'Ver activas' : 'Ver eliminadas'" :icon="$showDeleted ? 'o-eye' : 'o-eye-slash'" class="btn-ghost ml-2" wire:click="$toggle('showDeleted')" />
    </x-slot:actions>
  </x-header>

  <x-card shadow>
    <x-table :headers="$headers" :rows="$empresas" with-pagination :per-page="$perPage" :per-page-values="$perPageValues">
      @scope('actions', $empresa)
        @if(method_exists($empresa, 'trashed') && $empresa->trashed())
          <x-button icon="o-arrow-u-turn-left" wire:click="restore({{ $empresa->id }})" spinner="1" class="btn-ghost btn-sm" />
        @else
          <x-button icon="o-pencil" wire:click="openEdit({{ $empresa->id }})" class="btn-ghost btn-sm" />
          <x-button icon="o-trash" wire:click="confirmDelete({{ $empresa->id }})" spinner="1" class="btn-ghost btn-sm text-error" />
        @endif
      @endscope
    </x-table>
  </x-card>

  {{-- Modal: Create / Edit --}}
  <x-modal
    wire:model="modalOpen"
    title="{{ $form['id'] ? 'Editar Empresa' : 'Crear Empresa' }}"
    separator
    class="backdrop-blur"
    >
    <div class="space-y-3">
      <x-input label="Nombre" wire:model.defer="form.nombre" />
      <x-input label="Dirección" wire:model.defer="form.direccion" />
      <div class="grid lg:grid-cols-2 gap-3">
        <x-input label="Teléfono" wire:model.defer="form.telefono" />
        <x-input label="Email" wire:model.defer="form.email" type="email" />
      </div>

      <x-select label="Representante" :options="$users" option-value="id" option-label="name" wire:model.defer="form.representante_id" placeholder="-- Ninguno --" />
    </div>

    <x-slot:actions>
      <x-button label="Cancelar" @click="$set('modalOpen', false)" />
      <x-button label="Guardar" class="btn-primary" wire:click="save" spinner="1" />
    </x-slot:actions>
  </x-modal>

  {{-- Confirm delete modal --}}
  <x-modal wire:model="confirmingDelete" title="Confirmar eliminación" persistent>
    <p>¿Estás seguro de que quieres eliminar esta empresa? Esto usará soft-delete.</p>

    <x-slot:actions>
      <x-button label="Cancelar" @click="$set('confirmingDelete', false)" />
      <x-button label="Eliminar" class="btn-error" wire:click="delete" spinner="1" />
    </x-slot:actions>
  </x-modal>

</div>