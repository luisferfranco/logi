<?php

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Layout;

new
#[Layout('layouts.auth')]
class extends Component
{
  public $user;
  public $mensaje;

  public function mount() {
    $auth = Socialite::driver('google')->user();

    if (!str_ends_with($auth->email, '@fertinal.com')) {
      $this->mensaje = "Solo se permiten cuentas @fertinal.com, en un momento serás redirigido a la página de inicio.";

      $this->js("setTimeout(() => { Livewire.navigate('/login'); }, 3000);");
      return;
    }

    $this->user = User::where('email', $auth->email)->first();
    if (!$this->user) {
      $this->mensaje = "Tu usuario no tiene acceso a la aplicación, por favor contacta al administrador del sistema para que te otorgue acceso. En un momento serás redirigido a la página de inicio.";

      $this->js("setTimeout(() => { Livewire.navigate('/login'); }, 3000);");
      return;
    }
    // id, name, email, avatar

    $this->user->update([
      'nombre'      => $auth->name,
      'avatar'      => $auth->avatar,
      'id_externo'  => $auth->id,
    ]);

    Auth::loginUsingId($this->user->id);
    $this->redirectRoute('dashboard');
    return;
  }
};
?>

<div>
  {{ $mensaje }}
</div>