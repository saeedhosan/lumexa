<x-layouts::app title="Plan Details">

    <flux:header :title="$plan->name" />

    <flux:main>
        <flux:table>
            <flux:table.row>
                <flux:table.cell>Name</flux:table.cell>
                <flux:table.cell>{{ $plan->name }}</flux:table.cell>
            </flux:table.row>
            <flux:table.row>
                <flux:table.cell>Price</flux:table.cell>
                <flux:table.cell>{{ $plan->price }}</flux:table.cell>
            </flux:table.row>
            <flux:table.row>
                <flux:table.cell>Description</flux:table.cell>
                <flux:table.cell>{{ $plan->description ?? '-' }}</flux:table.cell>
            </flux:table.row>
        </flux:table>

        <flux:button :href="route('admin.plans.edit', $plan)" variant="primary">Edit</flux:button>
    </flux:main>

</x-layouts::app>