<form wire:submit="save">
  <div class="mb-6">
    <x-label value="Nombre del Rol" />
    <x-input
      type="text"
      wire:model="nombreRol"
      placeholder="Ej: supervisor, auditor"
      class="outline-none!"
      required
      />
  </div>

  <div class="overflow-x-auto border rounded-xl mb-6">
    <table class="min-w-full divide-y divide-base-300 text-left">
      <thead class="bg-base-200">
          <tr>
            <th class="px-4 py-2 text-xs font-bold text-base-content/75 uppercase tracking-wide border-b">
              Módulo / Entidad
            </th>
            @foreach($acciones as $accion)
              <th class="px-4 py-2 text-center text-xs font-bold text-base-content/75 uppercase tracking-wide border-b">
                {{ ucfirst($accion) }}
              </th>
            @endforeach
          </tr>
      </thead>

      <tbody class="bg-base-100 divide-y divide-base-300">
        @foreach($this->permisosPorFamilia as $familia => $permisos)
          <tr class="hover:bg-base-200">
            <td class="px-4 py-2 whitespace-nowrap text-sm font-bold text-base-content border-r">
              {{ ucfirst($familia) }}
            </td>

            @foreach($permisos as $permiso)
              <td class="px-4 py-2 whitespace-nowrap text-center border-r">
                <div class="flex items-center justify-center">
                  <x-toggle
                    wire:model="permisosSeleccionados"
                    value="{{ $permiso->name }}"
                    />
                </div>
              </td>
            @endforeach
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="flex justify-end gap-1">
    <x-button
      link="{{ route('admin.roles.index') }}"
      label="Cancelar"
      class="btn-ghost"
      />
    <x-button
      type="submit"
      label="{{ $textoBoton }}"
      class="btn-primary"
      />
  </div>
</form>
