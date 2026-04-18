<x-layouts::app title="Create Setting">

    <flux:header :title="__('Create Setting')" />

    <flux:main>
        <form method="POST" action="{{ route('admin.settings.store') }}">
            @csrf
            <flux:input name="key" label="Key" required />
            <flux:textarea name="value" label="Value" />
            <flux:button type="submit" variant="primary">Create</flux:button>
        </form>
    </flux:main>

</x-layouts::app>