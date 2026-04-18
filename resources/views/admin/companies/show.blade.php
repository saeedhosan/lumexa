<x-layouts::app title="Company Details">

    <flux:header :title="$company->name" />

    <flux:main>
        <flux:table>
            <flux:table.row>
                <flux:table.cell>Name</flux:table.cell>
                <flux:table.cell>{{ $company->name }}</flux:table.cell>
            </flux:table.row>
        </flux:table>

        <flux:button :href="route('admin.companies.edit', $company)" variant="primary">Edit</flux:button>
    </flux:main>

</x-layouts::app>