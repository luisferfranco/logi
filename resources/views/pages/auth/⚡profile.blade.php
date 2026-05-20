<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

new class extends Component
{
  use WithFileUploads;
  use Toast;

  public $user;
  public $name = '';
  public $photo;
  public $password = '';
  public $password_confirmation = '';

  public function mount()
  {
    $this->user = auth()->user();
    $this->name = $this->user->name;
  }

  public function updateProfile()
  {
    $rules = [
      'name' => 'required|string|max:255',
      'photo' => 'nullable|image|max:1024',
    ];

    if ($this->password) {
      $rules['password'] = 'required|string|min:8|confirmed';
    }

    $this->validate($rules);

    $this->user->name = $this->name;

    if ($this->password) {
      $this->user->password = Hash::make($this->password);
    }

    if ($this->photo) {
      $path = $this->photo->storePublicly('avatars', 'public');
      $this->user->avatar = Storage::disk('public')->url($path);
    }

    $this->user->save();

    $this->password = '';
    $this->password_confirmation = '';
    $this->photo = null;

    $this->success(
      title: 'Perfil actualizado',
      description: 'Tus datos se guardaron correctamente.',
      icon: 'tabler.check',
      timeout: 3000
    );
  }
};
?>

<div class="">

  <div class="space-y-6">
    <div>
      <h1 class="text-2xl font-bold">Mi perfil</h1>
      <p class="text-sm text-base-content/60">Actualiza tu información personal y contraseña.</p>
    </div>

    <form wire:submit="updateProfile"">
      <div class="grid gap-6 lg:grid-cols-[220px_1fr] items-start">

        <div>
          <div class="space-y-3 w-full flex justify-center">
            <div class="flex justify-center w-52 h-52 rounded-full overflow-hidden border border-base-200 bg-base-200">
              @if ($photo)
                <img src="{{ $photo->temporaryUrl() }}" alt="Vista previa" class="h-full w-full object-cover" />
              @else
                <img src="{{ $user->avatar_url }}" alt="Foto de perfil" class="h-full w-full object-cover" />
              @endif
            </div>

          </div>
          <div>
            <label class="font-bold text-xs uppercase tracking-wide">Foto de perfil</label>
            <x-file
              type="file"
              accept="image/*"
              wire:model="photo"
              class="file-input file-input-bordered w-full"
              />
            <p class="text-xs text-base-content/50">Sube una imagen JPG, PNG o GIF de hasta 1 MB.</p>
          </div>
        </div>

        <div class="space-y-5">
          <div class="grid gap-4 sm:grid-cols-2">
            <div>
              <label class="text-xs uppercase font-bold tracking-wide">Nombre</label>
              <x-input
                type="text"
                wire:model.defer="name"
                class="outline-none!"
                required
              />
            </div>

            <div>
              <label class="text-xs uppercase font-bold tracking-wide">Correo electrónico</label>
              <x-input
                type="email"
                value="{{ $user->email }}"
                class="input input-bordered bg-base-200 text-base-content cursor-not-allowed"
                disabled
                />
            </div>

            <div>
              <label class="text-xs uppercase font-bold tracking-wide">Empresa</label>
              <x-input
                type="text"
                value="{{ $user->empresa?->nombre ?? 'Sin empresa' }}"
                class="input input-bordered bg-base-200 text-base-content cursor-not-allowed"
                disabled
                />
            </div>

            <div>
              <label class="text-xs uppercase font-bold tracking-wide">Rol</label>
              <x-input
                type="text"
                value="{{ $user->roles->pluck('name')->join(', ') ?: 'Sin rol' }}"
                class="input input-bordered bg-base-200 text-base-content cursor-not-allowed"
                disabled
                />
            </div>

            <div>
              <label class="text-xs uppercase font-bold tracking-wide">Contraseña nueva</label>
              <x-input
                type="password"
                wire:model.defer="password"
                class="outline-none!"
                autocomplete="new-password"
                />
            </div>

            <div>
              <label class="text-xs uppercase font-bold tracking-wide">Confirmar contraseña</label>
              <x-input
                type="password"
                wire:model.defer="password_confirmation"
                class="outline-none!"
                autocomplete="new-password"
                />
            </div>
          </div>
        </div>
      </div>

      <div class="flex justify-end gap-3">
        <x-button
          type="submit"
          label="Guardar cambios"
          class="btn-primary uppercase tracking-widest"
          wire:loading.attr="disabled"
          wire:target="updateProfile"
        />
        <x-button
          label="Cancelar"
          class="btn-ghost btn-error uppercase tracking-widest"
          link="{{ route('dashboard') }}"
          />
      </div>
    </form>
  </div>
</div>
