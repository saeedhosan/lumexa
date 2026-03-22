@switch(auth()->user()->type)
    {{-- condition --}}
    @case(\App\Enums\UserType::administrator)
        <x-layouts::sidebar.super />
    @break

    @case(\App\Enums\UserType::admin)
        <x-layouts::sidebar.admin />
    @break

    @default
        <x-layouts::sidebar.default />
@endswitch
