<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Mary\Traits\Toast;

new
#[Layout('layouts.empty')]
class extends Component
{
    use Toast;

    public $code;
    public $user = null;
    public $invalidMessage = null;

    public $showTos = false;
    public $showPassword = false;
    public $acceptTos = false;
    public $password = '';
    public $password_confirmation = '';

    public function mount()
    {
        $this->code = request()->query('code');
        if (! $this->code) {
            $this->invalidMessage = 'Código de invitación inválido.';
            return;
        }

        $user = User::where('invitation_code', $this->code)->first();
        if (! $user) {
            $this->invalidMessage = 'Código de invitación inválido.';
            return;
        }

        if (! $user->invitation_expires || $user->invitation_expires->isPast()) {
            $this->invalidMessage = 'El enlace ha expirado. Solicita una nueva invitación.';
            return;
        }

        Auth::loginUsingId($user->id);
        $this->user = $user;
        $this->showTos = true;
    }

    public function acceptTosAction()
    {
        $this->validate(['acceptTos' => 'accepted']);

        $this->user->tos_accepted = now();
        $this->user->save();

        $this->showTos = false;
        $this->showPassword = true;
    }

    public function setPassword()
    {
        $this->validate(['password' => 'required|string|min:8|confirmed']);

        $this->user->password = Hash::make($this->password);
        $this->user->status = 'activo';
        $this->user->tos_accepted = $this->user->tos_accepted ?? now();
        $this->user->invitation_code = null;
        $this->user->invitation_expires = now()->subSecond();
        $this->user->save();

        $this->showPassword = false;

        return redirect()->route('dashboard');
    }
};

?>

<div class="relative bg-base-100 rounded-lg max-w-lg shadow-lg w-full">
  <style>
    .modal-box .modal-action { position: sticky; bottom: 0; z-index: 10; background: var(--base-100); border-top: 1px solid rgba(0,0,0,0.06); padding: 1rem; }
  </style>
  <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-base-100 p-4 rounded-lg">
    <img src="/img/ferti-v.svg" class="h-24 w-auto">
  </div>
  <div class="p-8 pt-18">
    @if($invalidMessage)
      <div class="p-6">
        <h2 class="text-xl font-bold">Enlace inválido o expirado</h2>
        <p class="mt-4">{{ $invalidMessage }}</p>
      </div>
    @else
      <div>
        <x-modal
          wire:model="showTos"
          class="backdrop-blur"
          persistent
          >
          <div x-data="{ show: @entangle('showTos') }" x-effect="if(show) $nextTick(() =&gt; $refs.modalStart?.focus())" class="flex flex-col w-full h-[75vh]">
            <div x-ref="modalStart" tabindex="0" class="sr-only" aria-hidden="true"></div>

            <!-- Sticky logo header -->
            <div class="sticky top-0 z-10 bg-base-100 pt-4 pb-2 flex justify-center items-end">
              <img src="/img/ferti-v.svg" alt="Logo" class="h-16 w-auto mb-2">
            </div>

            <!-- Scrollable content -->
            <div class="overflow-y-auto px-6 py-4 space-y-4 flex-1">
              <p class="text-base-content/50 text-sm font-semibold">
                Para activar tu cuenta deberás aceptar los términos y condiciones del servicio.
              </p>
              <h3 class="text-xl font-bold">Términos del servicio</h3>

              <div class="prose max-w-none space-y-4">
                <p>Por favor, lee y acepta los Términos y Condiciones para continuar.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore culpa harum quos qui possimus voluptatum est asperiores fuga?</p>
                <h3 class="font-bold my-2">1. Terminos de Uso</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus, sequi consequatur dolorum numquam perspiciatis fugit quisquam earum. Ratione nam perspiciatis maxime, quae error nostrum ex consequatur nemo itaque libero minus delectus temporibus ab quos doloribus accusamus dignissimos corporis expedita magnam quasi facilis vero quod? Quaerat necessitatibus mollitia aliquam amet harum eos rerum dignissimos aperiam? Enim facilis alias ab sint libero doloribus quo eaque magni, eius accusamus autem dolor atque tempora nesciunt dignissimos! Recusandae repudiandae suscipit a. Labore sequi molestiae, nam itaque quo corporis eum repellat, debitis quisquam non numquam dolores ipsam sit minima maxime, saepe excepturi quae explicabo consequuntur totam?</p>
                <p>Odit, quisquam. Aspernatur dolorem corrupti dolor corporis harum. Similique nostrum quidem odio accusantium, perferendis iure amet atque, rerum culpa minus voluptatibus saepe quas itaque nulla nisi laborum aut eaque, mollitia ducimus quod eos! Id reiciendis veniam, eligendi autem quod sequi et laboriosam possimus illum officiis? Provident et, qui quam molestiae deleniti illo voluptates, nam ad omnis similique ex maiores beatae officia ut praesentium placeat ipsa harum exercitationem quis a tempora? Corrupti quos animi ipsam perspiciatis maxime iste nihil recusandae atque quae! Adipisci numquam dicta, nihil cum quisquam a, cupiditate iste quis tenetur explicabo culpa minus quos veniam vitae voluptas optio?</p>
                <h3 class="font-bold my-2">2. Exclusiones</h3>
                <p>Laborum, dicta sapiente asperiores neque et sunt ipsum dolore est veniam, repellendus delectus. Nihil enim ducimus esse minus, repellat iusto deserunt sint ipsa quas. Labore consequuntur vel corrupti omnis voluptas, ea nulla magni minus mollitia voluptatum, excepturi quia obcaecati sunt, aperiam rerum nobis praesentium ullam. A non maxime laudantium voluptatum! Excepturi autem fugiat animi neque alias? Adipisci minima fugiat suscipit quae. Possimus quasi sunt ea praesentium similique laudantium dolorem officia dolorum animi eum voluptatem eaque architecto adipisci magni placeat, aut facere hic nulla voluptas quibusdam cumque quas officiis veritatis. Quis nostrum voluptatum provident iste, deserunt ea esse reprehenderit fugiat molestias!</p>
                <p>Enim quisquam itaque tempora voluptatem dignissimos maiores labore. Odit commodi ducimus quaerat. Officiis saepe modi sequi exercitationem amet architecto sint eius quo ad! Quas illo ratione voluptate reprehenderit atque odit nulla tempore nesciunt molestias doloribus impedit ipsa adipisci, nihil praesentium voluptas error! Molestiae rem sequi assumenda corrupti numquam distinctio minima necessitatibus aliquid alias porro ratione provident sit fuga, fugit id nisi quasi, in neque asperiores? A saepe voluptate veritatis, ipsam pariatur corporis architecto dolorum nesciunt animi itaque nam, doloribus enim ex sed doloremque commodi voluptates quam ipsum harum repellendus eum aliquam veniam. Dignissimos pariatur, expedita ullam perferendis distinctio nam asperiores?</p>
                <h3 class="font-bold my-2">3. Renuncia de Responsabilidad</h3>
                <p>Rerum mollitia molestias blanditiis dolores laborum ex expedita? Expedita, beatae iure. Dolor eos eveniet laudantium porro earum impedit ad possimus nulla nemo illum repellendus soluta quidem voluptatibus, reprehenderit animi quos, alias dicta voluptate exercitationem quis expedita magnam. Ad neque maiores quis? Aspernatur sapiente quod doloremque. Perferendis, et eum. Corporis quis pariatur, sit consequatur assumenda, tempora, aliquid cupiditate harum voluptas tenetur id placeat minima! Placeat excepturi nulla numquam. Doloribus natus atque odio eos voluptatem excepturi quos repellendus! Aspernatur tempore et provident placeat minima. Suscipit veniam, eum facere, sequi consequatur aliquam, deserunt nihil corporis dolor ipsam quia odit ratione? Enim, magnam accusamus.</p>
              </div>
            </div>

            <!-- Sticky footer with checkbox and actions -->
            <div class="sticky bottom-0 z-10 bg-base-100 border-t p-4 flex items-center justify-between gap-4">
              <div class="flex items-center">
                <x-checkbox
                  wire:model.live="acceptTos"
                  label="Acepto los Términos y Condiciones"
                  />
              </div>

              <div>
                <x-button
                  label="Aceptar"
                  class="btn-primary uppercase tracking-widest"
                  wire:click.prevent="acceptTosAction"
                  :disabled="!$acceptTos"
                />
              </div>
            </div>
          </div>
        </x-modal>

        <x-modal
          wire:model="showPassword"
          class="backdrop-blur"
          persistent
          >

          <div class="flex flex-col w-full">
            <div class="overflow-y-auto px-6 py-4 flex-1">
              <div class="flex flex-col justify-center">
                <img src="/img/ferti-v.svg" class="h-16 w-auto mb-4">
                <p class="text-base-content/50 text-sm font-semibold mb-6 text-center">
                  Por favor escriba su password y confirme el mismo, una vez hecho esto, su cuenta será activada
                </p>
              </div>

              <div class="space-y-2 mt-4">
                <div>
                  <label class="text-xs uppercase font-bold tracking-wide">contraseña</label>
                  <x-input
                    wire:model="password"
                    type="password"
                    class="outline-none!"
                    required
                    />
                </div>

                <div>
                  <label class="text-xs uppercase font-bold tracking-wide">confirmar contraseña</label>
                  <x-input
                    type="password"
                    wire:model="password_confirmation"
                    class="outline-none!"
                    required
                    />
                </div>
              </div>
            </div>
          </div>

          <x-slot:actions>
            <x-button
              label="Guardar contraseña"
              class="btn-primary"
              wire:click.prevent="setPassword"
              />
            <x-button
              class="btn-ghost btn-error"
              label="Cancelar"
              @click="window.close()"
              />
          </x-slot:actions>
        </x-modal>

        <div class="p-6">
          <h2 class="text-lg font-semibold">Bienvenido</h2>
          <p class="mt-2">Completa los pasos solicitados en el modal para activar tu cuenta.</p>
        </div>
      </div>
    @endif
  </div>
</div>
