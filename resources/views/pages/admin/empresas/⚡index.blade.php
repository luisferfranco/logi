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
    $this->users = Empresa::find($id)->users()->orderBy('name')->get();
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

  public function modalClose() {
    $this->resetForm();
    $this->modalOpen = false;
  }
};
?>

<div>
  <x-input
    placeholder="Buscar..."
    wire:model.live.debounce="search"
    clearable
    icon="tabler.zoom"
    />

  <div class="my-2 flex gap-1">
    <x-button
      wire:click="openCreate"
      label="Crear Empresa"
      icon="tabler.circle-plus"
      class="btn-primary"
      />
    <x-button
      wire:click="$toggle('showDeleted')"
      :label="$showDeleted ? 'Ver activas' : 'Ver eliminadas'"
      :icon="$showDeleted ? 'tabler.eye' : 'tabler.eye-off'"
      class="btn-ghost"
      />
  </div>

  <x-table :headers="$headers" :rows="$empresas" with-pagination :per-page="$perPage" :per-page-values="$perPageValues">
    @scope('actions', $empresa)
      <div class="flex gap-1">
        @if(method_exists($empresa, 'trashed') && $empresa->trashed())
          <x-button
            wire:click="restore({{ $empresa->id }})"
            icon="tabler.u-turn-left"
            spinner="1"
            class="btn-ghost btn-sm btn-success"
            />
        @else
          <x-button
            wire:click="openEdit({{ $empresa->id }})"
            icon="tabler.pencil"
            class="btn-ghost btn-sm btn-info"
            />
          <x-button
            wire:click="confirmDelete({{ $empresa->id }})"
            icon="tabler.trash"
            spinner="1"
            class="btn-ghost btn-sm text-error"
            />
        @endif
      </div>
    @endscope
  </x-table>

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
      <x-button
        label="Cancelar"
        wire:click="modalClose"
        />
      <x-button label="Guardar" class="btn-primary" wire:click="save" spinner="1" />
    </x-slot:actions>
  </x-modal>

  {{-- Confirm delete modal --}}
  <x-modal wire:model="confirmingDelete" title="Confirmar eliminación" persistent>
    <p>¿Estás seguro de que quieres eliminar esta empresa?</p>

    <x-slot:actions>
      <x-button label="Cancelar" @click="$set('confirmingDelete', false)" />
      <x-button label="Eliminar" class="btn-error" wire:click="delete" spinner="1" />
    </x-slot:actions>
  </x-modal>

</div>