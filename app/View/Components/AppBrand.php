<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppBrand extends Component
{
  /**
   * Create a new component instance.
   */
  public function __construct()
  {
    //
  }

  /**
   * Get the view / contents that represent the component.
   */
  public function render(): View|Closure|string
  {
    return <<<'HTML'
        <a href="/" wire:navigate>
          <!-- Hidden when collapsed -->
          <div {{ $attributes->class(["hidden-when-collapsed"]) }}>
            <div class="flex justify-center">
              <img src="/img/ferti-v.svg" class="w-auto h-10" />
            </div>
          </div>

          <!-- Display when collapsed -->
          <div class="display-when-collapsed hidden mx-5 mt-5 mb-1 h-7 overflow-hidden">
            <img src="/img/ferti-v.svg" />
          </div>
        </a>
      HTML;
  }
}
