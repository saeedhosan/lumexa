<x-layouts::app title="Add User to Company">

    <div class="max-w-2xl">
        <flux:heading size="xl">Add User to Company</flux:heading>
        <flux:subheading class="mb-6">Add an existing user to your company.</flux:subheading>

        <form method="POST" action="{{ route('admin.invites.store') }}">
            @csrf

            <div class="space-y-6">
                <flux:field>
                    <flux:label>Company</flux:label>
                    <flux:select name="company_id" value="{{ old('company_id') }}" required>
                        <flux:select.option value="">Select company</flux:select.option>
                        @foreach ($companies as $company)
                            <flux:select.option value="{{ $company->id }}">{{ $company->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="company_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Email Address</flux:label>
                    <flux:input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="user@example.com"
                        required
                    />
                    <flux:error name="email" />
                </flux:field>

                <flux:field>
                    <flux:label>Role</flux:label>
                    <flux:select name="role" placeholder="Select role" required>
                        <flux:select.option value="{{ \App\Models\Company::ROLE_ADMIN }}">Admin</flux:select.option>
                        <flux:select.option value="{{ $defaultRole }}">Customer</flux:select.option>
                    </flux:select>
                    <flux:error name="role" />
                </flux:field>
            </div>

            <div class="mt-6 flex justify-between gap-3">
                <flux:button :href="route('admin.invites.index')" variant="ghost" wire:navigate>Cancel</flux:button>
                <flux:button type="submit" variant="primary">Add User</flux:button>
            </div>
        </form>
    </div>

</x-layouts::app>