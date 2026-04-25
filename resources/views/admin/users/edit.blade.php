@use('\App\Enums\UserStatus')
@use('\App\Enums\UserType')

<x-layouts::app title="Edit User">

    <div class="max-w-2xl">
        <flux:heading size="xl">Edit User</flux:heading>
        <flux:subheading class="mb-6">Update user details.</flux:subheading>

        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PATCH')

            <div class="space-y-6">
                <flux:field>
                    <flux:label>Name</flux:label>
                    <flux:input
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        placeholder="Enter full name"
                        required
                    />
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label>Email</flux:label>
                    <flux:input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        placeholder="Enter email address"
                        disabled
                    />
                    <flux:error name="email" />
                </flux:field>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Password</flux:label>
                        <flux:input type="password" name="password"
                            placeholder="Leave blank to keep current"
                        />
                        <flux:error name="password" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Confirm Password</flux:label>
                        <flux:input type="password" name="password_confirmation"
                            placeholder="Confirm new password"
                        />
                    </flux:field>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Status</flux:label>
                        <flux:select name="status" value="{{ old('status', $user->status->value) }}">
                            @foreach ($statuses as $status)
                                <flux:select.option value="{{ $status }}">{{ ucfirst($status) }}</flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="status" />
                    </flux:field>

                    @if ($types)
                        <flux:field>
                            <flux:label>Type</flux:label>
                            <flux:select name="type" value="{{ old('type', $user->type->value) }}">
                                @foreach ($types as $type)
                                    <flux:select.option value="{{ $type }}">{{ ucfirst($type) }}</flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="type" />
                        </flux:field>
                    @endif
                </div>

                <flux:field>
                    <flux:label>Company</flux:label>
                    <flux:select name="current_company_id" value="{{ old('current_company_id', $user->companies->first()?->id ?? '') }}">
                        <flux:select.option value="">Select company</flux:select.option>
                        @foreach ($companies as $company)
                            <flux:select.option value="{{ $company->id }}">{{ $company->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="current_company_id" />
                </flux:field>
            </div>

            <div class="mt-6 flex justify-between gap-3">
                <flux:button :href="route('admin.users.index')" variant="ghost" wire:navigate>
                    Cancel
                </flux:button>
                <flux:button type="submit" variant="primary">Update user</flux:button>
            </div>
        </form>
    </div>

</x-layouts::app>