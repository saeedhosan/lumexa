<x-layouts::app title="Edit Plan">

    <flux:header :title="__('Edit Plan')" />

    <flux:main>
        <form method="POST" action="{{ route('admin.plans.update', $plan) }}">
            @csrf
            @method('PUT')
            <flux:input name="name" label="Name" :value="$plan->name" required />
            <flux:input name="price" label="Price" type="number" step="0.01" :value="$plan->price" required />
            <flux:textarea name="description" label="Description">{{ $plan->description }}</flux:textarea>
            <flux:button type="submit" variant="primary">Update</flux:button>
        </form>
    </flux:main>

</x-layouts::app>