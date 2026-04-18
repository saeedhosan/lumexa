<x-layouts::app title="Create Log">

    <flux:header :title="__('Create Log')" />

    <flux:main>
        <form method="POST" action="{{ route('admin.logs.store') }}">
            @csrf
            <flux:textarea name="message" label="Message" required />
            <flux:button type="submit" variant="primary">Create</flux:button>
        </form>
    </flux:main>

</x-layouts::app>