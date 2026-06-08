<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new
#[Layout('layouts.auth')]
class extends Component
{
    //
};
?>

<div>
  <h1 class="font-bold text-2xl mt-6">Landing Page</h1>
  <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Numquam ipsa illo accusamus, fuga incidunt reprehenderit neque doloremque vero tenetur temporibus autem. Laudantium, vitae soluta nesciunt obcaecati blanditiis at? Culpa, laudantium?
  </p>
  <x-button
    class="btn-primary mt-4 w-full"
    label="Login"
    link="{{ route('login') }}"
    />
</div>