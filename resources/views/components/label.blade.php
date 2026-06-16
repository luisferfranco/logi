@props(['value'])

<label class="text-xs font-bold block text-base-content/75 uppercase tracking-wide">
  {{ $value ?? $slot }}
</label>