@php
switch ($width) {
    case 'sm':
        $div = 10;
        $toggle = 6;
        break;
    case 'lg':
        $div = 20;
        $toggle = 10;
        break;

    default:
    $div = 10;
        $toggle = 6;
        break;
}
@endphp
<div>
    <style>
        /* CHECKBOX TOGGLE SWITCH */
        /* @apply rules for documentation, these do not work as inline style */
        .toggle-checkbox:checked {
          @apply: right-0 border-green-400;
          right: 0;
          border-color: #68D391;
        }
        .toggle-checkbox:checked + .toggle-label {
          @apply: bg-green-400;
          background-color: #68D391;
        }
        .toggle-checkbox {
          border-color: #ff0000;
          background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 16 16'%3e%3cpath stroke='white' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 8h8'/%3e%3c/svg%3e");

            background-color: #ff0000;
        }
        </style>
    <div class="relative inline-block w-{{ $div }} mr-2 align-middle
    select-none transition duration-200 ease-in">
        <input wire:model="hasStatus" type="checkbox" name="status"
         class="toggle-checkbox absolute block w-{{ $toggle }} h-{{ $toggle }}
                        rounded-full bg-white border-4 appearance-none cursor-pointer"/>
        <label for="toggle" class="toggle-label block overflow-hidden h-{{ $toggle }} w-{{ $div }}
        rounded-full bg-gray-300 cursor-pointer"></label>
    </div>
</div>
