<x-layouts::app title="Setting">

    <flux:header :title="$id" />

    <flux:main>
        <flux:button :href="route('admin.settings.edit', $id)" variant="primary">Edit</flux:button>
    </flux:main>

</x-layouts::app>