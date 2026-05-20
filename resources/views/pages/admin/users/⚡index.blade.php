<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Empresa;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use App\Services\UserInvitationService;
use App\Enum\UserStatus;

new class extends Component
{
  use WithPagination;
  use Toast;

  public $modalOpen = false;
  public $confirmingDelete = false;
  public $deleteId = null;
  public $showDeleted = false;
  public $search = '';
  public $perPage = 10;
  public $perPageValues = [10, 20, 50];

  public $empresas = [];
  public $availableRoles = [];
  public $headers = [];

  public $form = [
    'id' => null,
    'name' => '',
    'email' => '',
    'empresa_id' => null,
    'role' => null,
  ];

  public function mount()
  {
    if (!auth()->user() || (!auth()->user()->hasRole('su') && !auth()->user()->hasRole('admin'))) {
      abort(403);
    }

    $this->empresas = Empresa::orderBy('nombre')->get();

    $this->headers = [
      ['key' => 'name', 'label' => 'Nombre', 'class' => 'w-64'],
      ['key' => 'email', 'label' => 'Email'],
      ['key' => 'empresa.nombre', 'label' => 'Empresa'],
      ['key' => 'status', 'label' => 'Estado'],
    ];

    $user = auth()->user();
    if ($user && $user->hasRole('su')) {
      $this->availableRoles = ['admin', 'fertinal', 'transportista'];
    } elseif ($user && $user->hasRole('admin')) {
      $this->availableRoles = ['fertinal', 'transportista'];
    } else {
      $this->availableRoles = [];
    }
  }

  protected function rules()
  {
    $uniqueEmail = Rule::unique('users', 'email');
    if ($this->form['id']) {
      $uniqueEmail = $uniqueEmail->ignore($this->form['id']);
    }

    return [
      'form.name' => 'required|string|max:255',
      'form.email' => ['required', 'email', 'max:255', $uniqueEmail],
      'form.empresa_id' => 'nullable|exists:empresas,id',
      'form.role' => ['required', 'in:'.implode(',', $this->availableRoles)],
    ];
  }

  public function with(): array
  {
    $query = User::query()->with('empresa');

    if ($this->showDeleted) {
      $query = $query->onlyTrashed();
    }

    if ($this->search) {
      $query = $query->where('name', 'like', "%{$this->search}%");
    }

    return [
      'users' => $query->orderBy('name')->paginate($this->perPage),
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
      'name' => '',
      'email' => '',
      'empresa_id' => null,
      'role' => null,
    ];
  }

  public function openCreate()
  {
    $this->resetForm();
    $this->modalOpen = true;
  }

  public function openEdit($id)
  {
    $user = User::withTrashed()->findOrFail($id);
    $this->form = [
      'id' => $user->id,
      'name' => $user->name,
      'email' => $user->email,
      'empresa_id' => $user->empresa_id,
      'role' => $user->roles()->pluck('name')->first(),
    ];
    $this->modalOpen = true;
  }

  public function save()
  {
    try {
      $validated = $this->validate();

      if ($this->form['id']) {
        $user = User::withTrashed()->findOrFail($this->form['id']);
        $user->update($validated['form']);
        if (!empty($validated['form']['role'])) {
          $user->syncRoles([$validated['form']['role']]);
        }
        $this->success('Usuario actualizado.');
      } else {

        // Crear el usuario, con un password random
        $random = Str::random(12);
        $user = User::create([
          'name' => $validated['form']['name'],
          'email' => $validated['form']['email'],
          'password' => Hash::make($random),
          'empresa_id' => $validated['form']['empresa_id'] ?? null,
          'status' => UserStatus::INVITADO->value,
        ]);
        if (!empty($validated['form']['role'])) {
          $user->assignRole($validated['form']['role']);
        }

        // Enviar la invitación por correo
        $this->sendInvitation($user->id);
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

  public function sendInvitation($id) {
    $user = User::findOrFail($id);
    try {
      (new UserInvitationService())->sendInvitation($user);
      $this->success(
        title: 'Invitación enviada',
        description: 'La invitación fue enviada correctamente',
        icon: 'tabler.mail',
        timeout: 3000
        );
    } catch (\Exception $e) {
      report($e);
      $this->error(
        title: 'Fallo al enviar invitación',
        description: 'Ocurrió un error al enviar la invitación',
        icon: 'tabler.alert-circle',
        timeout: 3000
      );
    }
  }

  public function confirmDelete($id) {
    $this->confirmingDelete = true;
    $this->deleteId = $id;
  }

  public function delete() {
    if ($this->deleteId) {
      $user = User::findOrFail($this->deleteId);
      $user->delete();
      $this->success(
        title: 'Usuario eliminado',
        description: 'El usuario fue eliminado correctamente',
        icon: 'tabler.trash',
        timeout: 3000
      );
      $this->resetPage();
    }
    $this->confirmingDelete = false;
    $this->deleteId = null;
  }

  public function restore($id) {
    $user = User::withTrashed()->findOrFail($id);
    $user->restore();
    $this->success(
      title: 'Usuario restaurado',
      description: 'El usuario fue restaurado correctamente',
      icon: 'tabler.refresh',
      timeout: 3000
    );
    $this->resetPage();
  }

  public function toggleSuspendido($id) {
    $user = User::findOrFail($id);
    $user->status = $user->status === UserStatus::SUSPENDIDO ? UserStatus::ACTIVO : UserStatus::SUSPENDIDO;
    $user->save();
    $this->success(
      title: 'Usuario ' . ($user->status === UserStatus::SUSPENDIDO ? 'suspendido' : 'activado'),
      description: 'El usuario fue ' . ($user->status === UserStatus::SUSPENDIDO ? 'suspendido' : 'activado') . ' correctamente',
      icon: $user->status === UserStatus::SUSPENDIDO ? 'tabler.user-x' : 'tabler.user-check',
      timeout: 3000
    );
    $this->resetPage();
  }
};
?>

<div>
  <x-input
    placeholder="Buscar..."
    wire:model.live.debounce="search"
    clearable
    icon="tabler.zoom"
    class="outline-none!"
    />

  <div class="flex gap-1 my-2">
    <x-button
      label="Crear Usuario"
      icon="tabler.user-plus"
      class="btn-primary"
      wire:click="openCreate"
      />
    <x-button
      :label="$showDeleted ? 'Ver activos' : 'Ver eliminados'"
      :icon="$showDeleted ? 'tabler.eye' : 'tabler.eye-off'"
      class="btn-ghost ml-2"
      wire:click="$toggle('showDeleted')"
      />
  </div>

  <x-table :headers="$headers" :rows="$users" with-pagination :per-page="$perPage" :per-page-values="$perPageValues">
    @scope('cell_status', $user)
      <div class="text-center">
        <x-badge
          class="badge-{{ $user->status->color() }} uppercase"
          value="{{ $user->status->label() }}"
          />
      </div>
    @endscope

    @scope('actions', $user)
      <div class="flex gap-1">
        <x-button
          icon="tabler.pencil"
          wire:click="openEdit({{ $user->id }})"
          class="btn-ghost btn-sm btn-info"
          />
        @if ($user->status == \App\Enum\UserStatus::INVITADO)
          <x-button
            icon="tabler.mail-fast"
            wire:click='sendInvitation({{ $user->id }})'
            class="btn-ghost btn-sm btn-success"
            tooltip="Enviar invitación"
            />
        @else
          <x-button
            icon="tabler.{{ $user->status == \App\Enum\UserStatus::ACTIVO ? 'user-off' : 'user-check' }}"
            wire:click="toggleSuspendido({{ $user->id }})"
            spinner="1"
            class="btn-ghost btn-sm {{ $user->status == \App\Enum\UserStatus::ACTIVO ? 'btn-error' : 'btn-success' }}"
            tooltip="{{ $user->status == \App\Enum\UserStatus::ACTIVO ? 'Suspender' : 'Activar' }}"
            />
        @endif
        <x-button
          icon="tabler.trash"
          wire:click="confirmDelete({{ $user->id }})"
          spinner="1"
          class="btn-ghost btn-sm text-error"
          tooltip="Eliminar"
          />
      </div>
    @endscope
  </x-table>

  <x-modal wire:model="modalOpen" title="{{ $form['id'] ? 'Editar Usuario' : 'Crear Usuario' }}" separator>
    <div class="space-y-3">
      <x-input label="Nombre" wire:model.defer="form.name" />
      <x-input label="Email" wire:model.defer="form.email" type="email" />
      <x-select label="Empresa" :options="$empresas" option-value="id" option-label="nombre" wire:model.defer="form.empresa_id" placeholder="-- Ninguna --" />

      <div>
        <label class="label">Rol</label>
        <select class="select select-bordered w-full" wire:model.defer="form.role">
          <option value="">-- Selecciona rol --</option>
          @foreach($availableRoles as $r)
            <option value="{{ $r }}">{{ ucfirst($r) }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <x-slot:actions>
      <x-button label="Cancelar" @click="$set('modalOpen', false)" />
      <x-button label="Guardar" class="btn-primary" wire:click="save" spinner="1" />
    </x-slot:actions>
  </x-modal>

  <x-modal wire:model="confirmingDelete" title="Confirmar eliminación" persistent>
    <p>¿Estás seguro de que quieres eliminar este usuario? Esto usará soft-delete.</p>

    <x-slot:actions>
      <x-button label="Cancelar" @click="$set('confirmingDelete', false)" />
      <x-button label="Eliminar" class="btn-error" wire:click="delete" spinner="1" />
    </x-slot:actions>
  </x-modal>

</div>