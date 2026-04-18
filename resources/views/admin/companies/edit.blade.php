<x-layouts::app title="Edit Company">

    <flux:header :title="__('Edit Company')" />

    <flux:main>
        <form method="POST" action="{{ route('admin.companies.update', $company) }}">
            @csrf
            @method('PUT')
            <flux:input name="name" label="Name" :value="$company->name" required />
            <flux:textarea name="description" label="Description">{{ $company->description }}</flux:textarea>
            <flux:button type="submit" variant="primary">Update</flux:button>
        </form>
    </flux:main>

</x-layouts::app>