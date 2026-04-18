<x-layouts::app title="Create Company">

    <flux:header :title="__('Create Company')" />

    <flux:main>
        <form method="POST" action="{{ route('admin.companies.store') }}">
            @csrf
            <flux:input name="name" label="Name" required />
            <flux:textarea name="description" label="Description" />
            <flux:button type="submit" variant="primary">Create</flux:button>
        </form>
    </flux:main>

</x-layouts::app>