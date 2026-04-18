<x-layouts::app title="Edit Setting">

    <flux:header :title="__('Edit Setting')" />

    <flux:main>
        <form method="POST" action="{{ route('admin.settings.update', $id) }}">
            @csrf
            @method('PUT')
            <flux:textarea name="value" label="Value" />
            <flux:button type="submit" variant="primary">Update</flux:button>
        </form>
    </flux:main>

</x-layouts::app>