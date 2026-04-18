<x-layouts::app title="Edit Log">

    <flux:header :title="__('Edit Log')" />

    <flux:main>
        <form method="POST" action="{{ route('admin.logs.update', $log) }}">
            @csrf
            @method('PUT')
            <flux:textarea name="message" label="Message" :value="$log->message" required />
            <flux:button type="submit" variant="primary">Update</flux:button>
        </form>
    </flux:main>

</x-layouts::app>