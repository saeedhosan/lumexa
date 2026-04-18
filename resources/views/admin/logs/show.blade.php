<x-layouts::app title="Log Details">

    <flux:header :title="$log->id" />

    <flux:main>
        <flux:table>
            <flux:table.row>
                <flux:table.cell>Message</flux:table.cell>
                <flux:table.cell>{{ $log->message }}</flux:table.cell>
            </flux:table.row>
            <flux:table.row>
                <flux:table.cell>Level</flux:table.cell>
                <flux:table.cell>{{ $log->level }}</flux:table.cell>
            </flux:table.row>
        </flux:table>
    </flux:main>

</x-layouts::app>