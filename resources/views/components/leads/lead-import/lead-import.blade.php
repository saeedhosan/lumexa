<div>
    <flux:modal.trigger name="lead-import">
        <flux:button class="cursor-pointer">Import leads</flux:button>
    </flux:modal.trigger>

    <flux:modal name="lead-import" class="md:w-96">
        <form wire:submit.prevent="import()" class="space-y-6">
            <flux:heading size="lg">Import leads</flux:heading>

            <flux:input wire:model="title" label="Title" placeholder="Enter lead title" />

            <flux:input 
                wire:model="file" 
                type="file" 
                label="Upload file" 
                accept=".csv,.xlsx,.xls"
            />
            @error('file')
                <flux:error>{{ $message }}</flux:error>
            @enderror

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Upload</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
