<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
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

    // Log the user in for the acceptance flow
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

};?>

<div class="relative bg-base-100 rounded-lg max-w-lg shadow-lg w-full">
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
          <div x-data="{ show: @entangle('showTos') }" x-effect="if(show) $nextTick(() =&gt; $refs.modalStart?.focus())">
            <div x-ref="modalStart" tabindex="0" class="sr-only" aria-hidden="true"></div>
            <div class="flex flex-col justify-center">
              <img src="/img/ferti-v.svg" class="h-16 w-auto mb-4">
              <p class="text-base-content/50 text-sm font-semibold mb-6 text-center">
                Para activar su cuenta deberá aceptar los términos y condiciones del servicio.
              </p>
            </div>
          <h3 class="text-xl font-bold mb-4">Términos del servicio</h3>
          <div class="prose max-w-5xl space-y-4">
            <p>Por favor, lee y acepta los Términos y Condiciones para continuar.</p>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Labore culpa harum quos qui possimus voluptatum est asperiores fuga? Libero rerum consequuntur dicta architecto suscipit veniam voluptate perferendis cum nemo at a, ut nisi maxime accusamus! Enim architecto, magni explicabo nam tenetur aperiam, similique officiis velit molestiae eaque quidem alias! Sapiente, illo magnam! Quas atque nemo voluptates ratione tenetur cumque voluptate consequuntur vel, sequi ducimus rem harum. Similique culpa expedita vero necessitatibus perferendis mollitia recusandae illo accusamus sint! Numquam veniam, esse incidunt necessitatibus deserunt dolorem. Earum illum corporis culpa laborum! Quia, id provident. Suscipit sapiente, atque numquam doloribus accusamus rem, voluptatum laboriosam quidem tempore repellat sed voluptates! Facere, repellendus provident esse quia in necessitatibus eligendi excepturi aliquid qui quas doloremque aspernatur maxime incidunt officia quo non delectus deserunt tenetur molestias eum aut inventore. Facere corrupti similique, delectus maxime ipsam perspiciatis quo consectetur praesentium eligendi doloremque esse exercitationem sit deleniti laboriosam, atque quis quasi sequi fuga nam laborum voluptas dolores pariatur. Sed reiciendis ad error laboriosam culpa quod blanditiis quo cumque illum, quaerat commodi tempore mollitia a veniam ea soluta et recusandae voluptatibus excepturi molestias, praesentium est veritatis iste? Optio tempora alias tempore repudiandae accusantium consequatur nam sunt cum ea officiis, autem corrupti culpa reprehenderit nulla dolore repellat totam exercitationem quas molestiae tenetur saepe eius maiores! Hic dolore aliquam quidem laborum ipsa dolorum quia culpa consequatur fugit vitae asperiores, qui cupiditate, exercitationem consequuntur deleniti aliquid sequi illo temporibus impedit! Minus, porro vitae fuga corporis sit voluptatum dolore et similique facere, laboriosam iste.</p>
            <p>Libero nesciunt quisquam eaque magnam iste sed, veniam reprehenderit voluptatem nam mollitia dolores dicta odit assumenda. Quisquam minus recusandae voluptatem tempore natus. Non accusantium eos accusamus vero iusto saepe quos incidunt aliquid ratione dolore qui molestiae omnis nihil animi, maiores sit consequuntur architecto tenetur! Labore eum natus fuga enim doloribus voluptate saepe illo maiores incidunt neque nisi impedit laudantium quos eveniet eos aut unde expedita corporis, tenetur, quas totam excepturi? Ratione aperiam, eveniet ex suscipit quo nesciunt, voluptatum laboriosam maiores aliquam exercitationem illo unde numquam sed dicta laudantium debitis qui necessitatibus molestiae harum modi dolor optio. Ducimus voluptate a tempora vero quasi odio molestiae sequi, officiis ex recusandae adipisci aliquam reiciendis, voluptatum aperiam voluptates magni corporis! Voluptate quam ipsum sint amet est modi, similique aut nesciunt rem blanditiis ullam quisquam quibusdam velit quia. Totam, itaque inventore voluptatibus a magnam beatae, iste ratione recusandae enim optio quam deleniti explicabo sapiente minima? Exercitationem autem animi minus dicta nesciunt repellendus quasi dolor. Quidem voluptatibus deleniti fugiat inventore expedita, atque incidunt, aut earum quaerat debitis nostrum eum fugit eius quam dignissimos odio. Molestias fugit sequi quas culpa reiciendis pariatur doloribus vero? Ad inventore soluta praesentium et nesciunt adipisci libero necessitatibus eaque eligendi. Distinctio, architecto illum harum maiores vel fugit sit adipisci suscipit tenetur inventore temporibus dicta quod tempora asperiores incidunt voluptas nihil placeat aliquam officia! Temporibus quis dolorum praesentium quibusdam! Ipsum nam amet, voluptatem optio dolores ipsa commodi doloribus modi quod saepe illum magnam et. Alias praesentium officia molestiae ut pariatur deleniti rem aspernatur.</p>
            <p>Magni debitis labore ex fugiat tempora dolores explicabo odio, ut blanditiis! Delectus omnis exercitationem eum eos non tempore deleniti itaque asperiores praesentium officia, reprehenderit eligendi, vitae error tenetur culpa repellat provident, temporibus necessitatibus cumque. Vitae doloremque sint soluta dolorum! Ullam, in nulla eum reprehenderit a corrupti autem! Eius quo quaerat porro, accusantium commodi dolores, explicabo placeat repellat impedit sint error molestiae unde, ab sunt deleniti veritatis ducimus consequatur corrupti nam velit vero! Quidem quis delectus nesciunt quaerat! Labore provident iusto distinctio veniam. Id consectetur voluptatum ea libero quaerat nobis. Eaque nemo vero amet quaerat pariatur adipisci labore vel praesentium optio asperiores accusamus voluptatem quos ea ab, consequatur dolores doloribus tempore cum esse eligendi animi ducimus corporis quasi! Dolores tempore blanditiis autem natus omnis doloremque deserunt harum quo praesentium maxime magni officia cupiditate nihil enim, eum repellat molestiae placeat consectetur dolor. Asperiores aliquam quos, explicabo exercitationem officiis enim dolor amet quas similique eligendi rem! Ratione temporibus est autem, architecto asperiores exercitationem ut aperiam rem placeat quibusdam modi tempora commodi odit deserunt dicta tenetur quos neque suscipit aut. Quod quaerat minima nostrum cupiditate illum. Aut doloribus placeat nisi error explicabo, aliquam aliquid! Suscipit iste adipisci, incidunt minus corrupti molestiae non vel eveniet! Voluptas, officia tenetur! Neque libero sed odio modi autem possimus ea quae fugit perspiciatis consequatur animi id, nemo laboriosam! Cum vero recusandae fuga pariatur placeat consequuntur unde earum impedit, quo dicta tempora corporis neque maiores quas expedita quam perferendis assumenda laudantium quibusdam alias deserunt nostrum facilis? Eligendi, nam. Dicta, autem.</p>
            <p>Aliquam repellat qui excepturi quae doloremque velit itaque, porro facilis assumenda iusto cupiditate officiis, facere eligendi nobis odio reiciendis esse laudantium, minus temporibus? Distinctio, expedita deserunt! Harum quam officia qui quidem nesciunt id quisquam assumenda distinctio, tempora ex repellendus laborum consequatur impedit optio iste quia accusantium? Eaque nemo, possimus, doloremque nesciunt nostrum ipsam eius voluptatum harum quasi nisi porro labore natus sapiente, tenetur dolorum. Numquam in modi adipisci obcaecati amet quo nihil doloribus repellat deleniti culpa. Numquam distinctio laboriosam doloremque porro sit voluptates, error nobis rem tempora, labore illo. Vero placeat molestias soluta molestiae itaque quibusdam eligendi aut id a unde quo facilis, nostrum illo. Eum commodi dolor incidunt aspernatur facilis. Culpa quibusdam sapiente est laboriosam? Cupiditate dignissimos accusantium deleniti eos magni officiis maiores impedit neque sint deserunt animi eveniet vel dolores mollitia architecto illum, laudantium laboriosam veniam recusandae eligendi blanditiis. Vitae nostrum laboriosam rem voluptate ratione maiores tenetur dolorem modi illo non recusandae, laudantium ipsam perferendis, porro cumque at quisquam adipisci quos quasi tempore libero. A laboriosam, exercitationem dolorem laudantium quo laborum maxime accusantium quibusdam natus reiciendis quaerat debitis accusamus maiores numquam consectetur eaque at quae! Quibusdam sapiente alias dolore iste repellat totam hic dignissimos suscipit omnis eius incidunt quo, aspernatur doloribus quos animi exercitationem culpa dolorum voluptates eligendi velit minima, ex blanditiis! Modi cumque impedit ullam aperiam obcaecati? Asperiores impedit, accusantium cupiditate officia facere in labore iusto sint perferendis velit autem consequatur sapiente hic doloribus eligendi tempora! Nam eum aliquid mollitia earum iste magnam dignissimos nesciunt fuga dolor.</p>
            <p>Temporibus impedit accusantium, sit animi tenetur itaque dicta, excepturi tempore consequatur quisquam porro corrupti aspernatur? Deleniti architecto cupiditate repellendus sequi in, omnis doloribus cum suscipit temporibus nisi? Laudantium dolorem, saepe optio nostrum deserunt sint voluptates dolorum perferendis, quibusdam tenetur, nesciunt ducimus inventore natus provident sit delectus consequuntur architecto. Hic animi iure labore sequi saepe ea sint quos officia quis corporis totam provident, nihil libero laboriosam reiciendis necessitatibus. Voluptatibus nostrum dicta dignissimos corrupti voluptas, quia iure nisi vero facilis quo id dolorum perferendis deleniti modi distinctio explicabo numquam mollitia ex aliquid ullam, obcaecati commodi? Ipsam laborum reprehenderit nobis nisi quisquam cupiditate laudantium, saepe velit? Accusantium laboriosam cupiditate culpa non qui ut excepturi, maxime ab ad saepe minima quia reprehenderit vero placeat, quidem nulla vitae. Incidunt recusandae laboriosam libero vel maxime at distinctio quos provident vero animi sunt aspernatur harum, fugit unde sed. Vel fuga mollitia eligendi. Itaque inventore in quos, eos, est libero eveniet repellendus accusamus asperiores magnam molestias dolor odit atque nulla. Nulla quae dolorum incidunt explicabo culpa, ipsam illo at exercitationem aut corrupti ullam sint esse optio temporibus! Qui voluptatibus minima vitae ipsa alias animi eligendi, dicta, eaque tempora asperiores aliquam! Fugiat doloremque ut tempore repellat doloribus, cumque, ullam commodi molestiae facilis iste, culpa iusto blanditiis? Perspiciatis facilis dolorum inventore eligendi provident nam vitae, saepe ipsa, illum, quaerat est beatae iste modi quidem suscipit nulla excepturi voluptatibus laudantium? Eaque distinctio et velit omnis necessitatibus quisquam magni commodi, praesentium aliquid perferendis soluta facilis in hic sit veniam pariatur labore nesciunt.</p>
          </div>

            <div class="mt-4">
              <x-checkbox
                wire:model.live="acceptTos"
                label="Acepto los Términos y Condiciones"
                />
            </div>

            <x-slot:actions>
              <x-button
                label="Aceptar"
                class="btn-primary uppercase tracking-widest"
                wire:click.prevent="acceptTosAction"
                :disabled="!$acceptTos"
                />
              <x-button
                class="btn-ghost btn-error"
                label="Cancelar"
                @click="window.close()"
                />
            </x-slot:actions>
          </div>
        </x-modal>

        <x-modal
          wire:model="showPassword"
          class="backdrop-blur"
          persistent
          >

          <div class="flex flex-col justify-center">
            <img src="/img/ferti-v.svg" class="h-16 w-auto mb-4">
            <p class="text-base-content/50 text-sm font-semibold mb-6 text-center">
              Por favor escriba su password y confirme el mismo, una vez hecho esto, su cuenta será activada
            </p>
          </div>

          <div class="space-y-2">
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
