<?php

use App\Enum\UserStatus;
use App\Models\User;
use Livewire\Component;

new class extends Component
{
  public User $user;
  public $statusOptions = [];
  public $status;

  public function mount(User $user) {
    $this->user = $user;
    $this->status = $user->status->value;

    $this->statusOptions = array_map(function (UserStatus $status) {
      return ['value' => $status->value, 'label' => $status->label()];
    }, UserStatus::cases());
  }

  public function updatedStatus($value) {
    $this->user->status = $value;
    $this->user->save();
  }
};
?>

<x-select
  wire:model.live="status"
  :options="$statusOptions"
  option-value="value"
  option-label="label"
  class="w-full max-w-45"
  />
