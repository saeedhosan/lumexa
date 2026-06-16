@if (request()->routeIs('admin.*'))
    <x-layouts::sidebar.admin />
@else
    <x-layouts::sidebar.default />
@endif
