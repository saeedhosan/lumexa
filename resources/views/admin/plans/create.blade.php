<x-layouts::app title="Create Plan">

    <flux:header :title="__('Create Plan')" />

    <flux:main>
        <form method="POST" action="{{ route('admin.plans.store') }}">
            @csrf
            <flux:input name="name" label="Name" required />
            <flux:input name="price" label="Price" type="number" step="0.01" required />
            <flux:textarea name="description" label="Description" />
            <flux:button type="submit" variant="primary">Create</flux:button>
        </form>
    </flux:main>

</x-layouts::app>