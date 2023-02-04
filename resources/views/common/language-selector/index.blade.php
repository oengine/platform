<div class="dropdown language-selector">
    <p class="p-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="fi fi-{{ $langs[$lang_current] }}"></span> {{ $lang_current }}
    </p>
    <ul class="dropdown-menu  dropdown-menu-end">
        @foreach ($langs as $key => $item)
            <li wire:click='DoSelector("{{ $key }}")' class="dropdown-item"><span
                    class="fi fi-{{ $item }}"></span> {{ $key }}</li>
        @endforeach
    </ul>
</div>
