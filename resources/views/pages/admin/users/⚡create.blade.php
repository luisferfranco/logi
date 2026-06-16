<?php

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Enum\EstadoUsuario;
use Mary\Traits\Toast;

new class extends Component
{
  use Toast;

  public $nombre, $email, $empleado;
  public array $selectedRoles = [];
  public $showEmpleado = false;

  public function crear() {
    $this->validate([
      'nombre' => 'required',
      'email' => 'required|email|unique:users,email',
      'selectedRoles' => 'array',
    ]);

    $user = User::create([
      'nombre'                => $this->nombre,
      'email'                 => $this->email,
      'empleado'              => $this->empleado,
      'estado'                => EstadoUsuario::PENDIENTE,
      'password'              => \Str::random(16),
      'codigo_invitacion'     => \Str::random(40),
      'expiracion_invitacion' => now()->addDays(2),
    ]);

    if (! empty($this->selectedRoles)) {
      $user->assignRole($this->selectedRoles);
    }

    // Enviar la notificación de creación de cuenta por
    // correo electrónico
    $user->notify(new \App\Notifications\NotificacionInvitacion($user));

    // Resetear campos y cerrar modal
    $this->reset(['nombre', 'email', 'empleado']);
    $this->crearModal = false;

    // Refrescar lista de usuarios
    $this->users = \App\Models\User::all();

    $this->success(
      title: 'Usuario creado',
      description: 'El usuario ha sido creado exitosamente. Se ha enviado una invitación por correo electrónico.',
      timeout: 5000,
      icon: 'o-envelope',
    );
  }

  public function updatedEmail($value) {
    $this->showEmpleado = str_ends_with($value, '@fertinal.com');
  }

  public function with(): array
  {
    return [
      'availableRoles' => Role::all(),
    ];
  }

};
?>

<x-card>
  <h1 class="text-xl font-bold">Crear un nuevo usuario</h1>
  <p class="text-base-content/50">Complete los campos para crear un nuevo usuario.</p>
  <form
    wire:submit='crear'
    class="grid grid-cols-1 md:grid-cols-2 mt-4 gap-4"
    >
    <div class="md:col-span-2">
      <x-input
        label="Nombre completo"
        wire:model="nombre"
        required
        />
    </div>

    <x-input
      label="Correo electrónico"
      type="email"
      wire:model.live="email"
      class=""
      required
      />

    @if ($showEmpleado)
      <div class="py-2 px-4 bg-base-200 rounded-xl">
        <p>Para los empleados de Fertinal, por favor introduce su número de empleado.</p>
        <x-input
          label="Número de empleado"
          wire:model="empleado"
          class=""
          />
      </div>
    @endif

    <div class="md:col-span-2">
      <div class="grid gap-3 p-4 bg-base-200 rounded-xl">
        <div class="font-semibold">Roles</div>
        <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
          @foreach ($availableRoles as $role)
            <div>
              <x-checkbox
                label="{{ $role->name }}"
                wire:model="selectedRoles"
                value="{{ $role->name }}"
                />
              @foreach ($role->permissions as $permiso)
                <div class="flex items-center ml-6 text-xs text-base-content/50">
                  <x-icon
                    name="o-check"
                    class="text-success w-4 h-4"
                    />
                  {{ $permiso->name }}
                </div>
              @endforeach
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="flex md:col-span-2 justify-end gap-2 mt-4">
      <x-button
        label="Cancelar"
        class="btn-ghost"
        link="{{ route('admin.users.index') }}"
        />
      <x-button
        label="Crear usuario"
        class="btn-primary"
        type="submit"
        spinner="save"
        />
    </div>
  </form>
</x-card>
