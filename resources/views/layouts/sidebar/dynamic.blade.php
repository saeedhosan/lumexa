@switch(auth()->user()->type)
    @case(\App\Enums\UserType::super)
    @case(\App\Enums\UserType::admin)
        <x-layouts::sidebar.admin />
    @break

    @default
        <x-layouts::sidebar.default />
@endswitch
