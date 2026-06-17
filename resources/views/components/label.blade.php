@props(['value', 'required' => false])

<label class="text-xs font-bold block text-base-content/75 uppercase tracking-wide">
  {{ $value ?? $slot }} @if($required) <span class="font-bold text-error">*</span> @endif
</label>