<?php

use Livewire\Component;
use Mary\Traits\Toast;

new class extends Component
{
    use Toast;

    public bool $drawer = false;
    public bool $modal = false;
    public bool $acceptTerms = true;
    public bool $notifications = true;
    public bool $featureFlag = false;
    public string $radioChoice = 'option-a';
    public string $selectedOption = 'option-2';
    public string $selectedTab = 'general';
    public string $currentStep = '1';
    public string $color = '#2563eb';
    public int $range = 55;
    public string $date = '';
    public string $datetime = '';
    public string $textarea = 'Escribe aquí para probar textarea, colorpicker y otros controles.';
    public string $inputText = '';
    public string $passwordText = '';
    public string $search = '';
    public string $rating = '3';

    public array $selectOptions = [
        ['id' => 'option-1', 'name' => 'Opción uno'],
        ['id' => 'option-2', 'name' => 'Opción dos'],
        ['id' => 'option-3', 'name' => 'Opción tres'],
    ];

    public array $radioOptions = [
        ['id' => 'option-a', 'name' => 'Option A', 'hint' => 'Primera opción disponible'],
        ['id' => 'option-b', 'name' => 'Option B', 'hint' => 'Segunda opción disponible'],
    ];

    public array $breadcrumbs = [
        ['label' => 'Inicio', 'link' => '/'],
        ['label' => 'Componentes', 'link' => '/ui'],
        ['label' => 'MaryUI preview'],
    ];

    public array $headers = [
        ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
        ['key' => 'name', 'label' => 'Nombre', 'sortable' => false],
        ['key' => 'email', 'label' => 'Email', 'sortable' => false],
        ['key' => 'status', 'label' => 'Estado', 'sortable' => false],
    ];

    public array $rows = [
        ['id' => 1, 'name' => 'Mary', 'email' => 'mary@example.com', 'status' => 'Activo'],
        ['id' => 2, 'name' => 'Giovanna', 'email' => 'giovanna@example.com', 'status' => 'Pendiente'],
        ['id' => 3, 'name' => 'Marina', 'email' => 'marina@example.com', 'status' => 'Bloqueado'],
    ];

    public function showToast(): void
    {
        $this->success('Notificación de prueba', 'MaryUI preview activa.', position: 'toast-bottom');
    }

    public function nextStep(): void
    {
        $this->currentStep = match ($this->currentStep) {
            '1' => '2',
            '2' => '3',
            default => '3',
        };
    }

    public function previousStep(): void
    {
        $this->currentStep = match ($this->currentStep) {
            '3' => '2',
            '2' => '1',
            default => '1',
        };
    }
}
?>

<div class="space-y-5">
    <x-header title="MaryUI preview" subtitle="Un repaso rápido de componentes y estados" separator progress-indicator>
        <x-slot:middle class="!justify-end gap-3">
            <x-theme-toggle />
            <x-button label="Toast" icon="o-bell" class="btn-neutral" wire:click="showToast" />
        </x-slot:middle>

        <x-slot:actions>
            <x-button label="Drawer" icon="o-bars-3" @click="$wire.drawer = true" />
        </x-slot:actions>
    </x-header>

    <div class="grid gap-5 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
        <div class="grid gap-5">
            <x-card title="Botones, badges y alerts" separator shadow>
                <div class="grid gap-5">
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="space-y-3">
                            <div class="flex flex-wrap items-center gap-3">
                                <x-button label="Primario" class="btn-primary" />
                                <x-button label="Secundario" class="btn-secondary" />
                                <x-button label="Ghost" class="btn-ghost" />
                                <x-button label="Link" class="btn-link" />
                            </div>
                            <div class="flex flex-wrap items-center gap-3">
                                <x-button icon="o-heart" class="btn-circle btn-primary" />
                                <x-button label="Responsive" icon="o-phone" responsive class="btn-accent" />
                                <x-button label="Disabled" disabled />
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex flex-wrap gap-2">
                                <x-badge class="badge-primary">Primary</x-badge>
                                <x-badge class="badge-secondary">Secondary</x-badge>
                                <x-badge class="badge-accent">Accent</x-badge>
                                <x-badge class="badge-outline">Outline</x-badge>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <x-badge class="badge-error">Error</x-badge>
                                <x-badge class="badge-warning">Warning</x-badge>
                                <x-badge class="badge-success">Success</x-badge>
                                <x-badge class="badge-info">Info</x-badge>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <x-alert title="Info" description="Mensaje informativo para el estado del sistema." icon="o-information-circle" class="alert-info" />
                            <x-alert title="Success" description="Todo está funcionando correctamente." icon="o-check-circle" class="alert-success" />
                            <x-alert title="Warning" description="Advertencia visible sin acción." icon="o-exclamation-triangle" class="alert-warning" dismissible />
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card title="Formularios y controles" separator shadow>
                <div class="grid gap-5">
                    <div class="grid gap-4 lg:grid-cols-2">
                        <div class="space-y-4">
                            <x-input label="Texto" placeholder="Escribe algo..." wire:model.live="inputText" icon="o-pencil" />
                            <x-input label="Email" placeholder="mary@example.com" wire:model.live="inputText" icon="o-envelope" />
                            <x-input type="password" label="Contraseña" placeholder="Password" wire:model.live="passwordText" icon="o-lock-closed" />
                            <x-textarea label="Descripción" placeholder="Descripción de la prueba..." wire:model.live="textarea" rows="4" />
                        </div>

                        <div class="space-y-4">
                            <x-select label="Seleccionar" :options="$selectOptions" wire:model.live="selectedOption" placeholder="Elige una opción" />
                            <x-radio label="Opciones" :options="$radioOptions" wire:model.live="radioChoice" />
                            <x-checkbox label="Acepto términos" wire:model.live="acceptTerms" />
                            <x-toggle label="Notificaciones" wire:model.live="notifications" />
                        </div>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-3">
                        <x-range label="Progreso" wire:model.live="range" min="0" max="100" />
                        <x-colorpicker label="Color" wire:model.live="color" />
                        <x-datepicker label="Fecha" placeholder="Selecciona fecha" wire:model.live="date" />
                    </div>
                </div>
            </x-card>

            <x-card title="Paneles y navegación" separator shadow>
                <div class="grid gap-5">
                    <x-tabs wire:model.live="selectedTab">
                        <x-tab name="general" label="General">
                            <p class="text-sm text-base-content/70">Los tabs permiten separar contenido por secciones sin recargar.</p>
                        </x-tab>
                        <x-tab name="settings" label="Ajustes">
                            <p class="text-sm text-base-content/70">Este contenido se muestra cuando el tab de ajustes está activo.</p>
                        </x-tab>
                        <x-tab name="preview" label="Vista previa">
                            <p class="text-sm text-base-content/70">Perfecto para demos de UI con múltiples bloques de información.</p>
                        </x-tab>
                    </x-tabs>

                    <div class="grid gap-3">
                        <x-steps wire:model.live="currentStep">
                            <x-step step="1" text="Inicio">
                                <p class="text-sm">Paso 1, información básica.</p>
                            </x-step>
                            <x-step step="2" text="Opciones">
                                <p class="text-sm">Paso 2, configuración adicional.</p>
                            </x-step>
                            <x-step step="3" text="Listo">
                                <p class="text-sm">Paso 3, revisión final antes de lanzar.</p>
                            </x-step>
                        </x-steps>

                        <div class="flex flex-wrap gap-3">
                            <x-button label="Siguiente" class="btn-sm btn-primary" wire:click="nextStep" />
                            <x-button label="Anterior" class="btn-sm btn-outline" wire:click="previousStep" />
                        </div>
                    </div>

                    <x-collapse title="Más información" open separator>
                        <x-slot:heading>Sección expandible</x-slot:heading>
                        <x-slot:content>
                            <p class="text-sm text-base-content/70">El componente collapse ayuda a mostrar u ocultar contenido adicional cuando se necesita.</p>
                        </x-slot:content>
                    </x-collapse>
                </div>
            </x-card>

            <x-card title="Tabla" separator shadow>
                <x-table :headers="$headers" :rows="$rows" />
            </x-card>
        </div>

        <div class="grid gap-5">
            <x-card title="Resumen rápido" shadow>
                <div class="grid gap-4">
                    <div class="grid gap-3">
                        <x-breadcrumbs :items="$breadcrumbs" />
                        <div class="flex flex-wrap gap-3">
                            <x-avatar placeholder="MJ" title="Mary UI" subtitle="Preview" />
                            <x-avatar placeholder="UI" title="Componentes" subtitle="Demo" class="w-14 h-14" />
                        </div>
                    </div>

                    <div class="grid gap-3">
                        <div class="flex items-center justify-between gap-3">
                            <span>Progreso global</span>
                            <span class="font-semibold">75%</span>
                        </div>
                        <x-progress value="75" max="100" />
                        <div class="grid place-items-center py-4">
                            <x-progress-radial value="75" unit="%" class="text-primary" />
                        </div>
                    </div>

                    <div class="grid gap-3">
                        <x-dropdown label="Más acciones" class="btn-outline">
                            <li><a>Acción 1</a></li>
                            <li><a>Acción 2</a></li>
                            <li><a>Acción 3</a></li>
                        </x-dropdown>
                        <div class="grid gap-2">
                            <x-button label="Abrir modal" class="btn-primary" @click="$wire.modal = true" />
                            <x-button label="Guardar" icon="o-bookmark" class="btn-success" wire:click="showToast" />
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card title="Controles adicionales" shadow>
                <div class="grid gap-4">
                    <x-file label="Subir archivo" />
                    <x-password label="Password" placeholder="Contraseña" wire:model.live="passwordText" />
                    <div class="flex flex-wrap gap-3 items-center">
                        <x-rating wire:model.live="rating" class="rating-lg" />
                        <div class="text-sm">Valor: {{ $rating }}</div>
                    </div>
                </div>
            </x-card>

            <x-card title="Accesos directos" shadow>
                <div class="grid gap-4">
                    <x-button label="Acceso rápido" icon="o-rocket-launch" class="btn-primary btn-block" />
                    <x-button label="Cancelar" class="btn-ghost btn-block" />
                    <div class="flex flex-wrap gap-2">
                        <span class="kbd">Ctrl</span>
                        <span class="kbd">K</span>
                        <span class="kbd">D</span>
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    <x-modal wire:model="modal" title="Modal de prueba" subtitle="Este modal muestra el componente con estado Livewire" separator>
        <div class="space-y-4">
            <p>Este es un modal de ejemplo con contenido de prueba para validar visualmente la UI.</p>
            <x-input label="Búsqueda en modal" placeholder="Buscar..." wire:model.live="search" icon="o-magnifying-glass" />
        </div>
        <x-slot:actions>
            <x-button label="Cerrar" class="btn-ghost" @click="$wire.modal = false" />
            <x-button label="Guardar cambios" class="btn-primary" @click="$wire.modal = false" />
        </x-slot:actions>
    </x-modal>

    <x-drawer wire:model="drawer" title="Drawer demo" right separator with-close-button class="lg:w-1/3">
        <div class="space-y-4">
            <p class="text-sm text-base-content/70">Este drawer se usa para comprobar el comportamiento del componente.</p>
            <x-input label="Control" placeholder="Texto del drawer" wire:model.live="inputText" />
            <x-button label="Cerrar" class="btn-primary" @click="$wire.drawer = false" />
        </div>
    </x-drawer>
</div>
