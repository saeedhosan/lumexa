@use('\App\Enums\UserStatus')
@use('\App\Enums\UserType')

<x-layouts::app title="Create User">

    <div class="max-w-2xl">
        <flux:heading size="xl">Create New User</flux:heading>
        <flux:subheading class="mb-6">Add a new user to the system.</flux:subheading>

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="space-y-6">
                <flux:field>
                    <flux:label>Name</flux:label>
                    <flux:input
                        name="name"
                        value="{{ old('name') }}"
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
                        value="{{ old('email') }}"
                        placeholder="Enter email address"
                        required
                    />
                    <flux:error name="email" />
                </flux:field>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Password</flux:label>
                        <flux:input
                            type="password"
                            name="password"
                            placeholder="Enter password"
                            required
                        />
                        <flux:error name="password" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Confirm Password</flux:label>
                        <flux:input
                            type="password"
                            name="password_confirmation"
                            placeholder="Confirm password"
                            required
                        />
                    </flux:field>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Status</flux:label>
                        <flux:select name="status" placeholder="Select status" required>
                            @foreach ($statuses as $status)
                                <flux:select.option value="{{ $status }}"
                                    :selected="old('status', UserStatus::fallback()->value) === $status"
                                >
                                    {{ ucfirst($status) }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="status" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Type</flux:label>
                        <flux:select name="type" placeholder="Select type" required>
                            @foreach ($types as $type)
                                <flux:select.option value="{{ $type }}"
                                    :selected="old('type', UserType::fallback()->value) === $type"
                                >
                                    {{ ucfirst($type) }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="type" />
                    </flux:field>
                </div>

                <flux:field>
                    <flux:label>Company</flux:label>
                    <flux:select name="current_company_id" placeholder="Select company (optional)">
                        <flux:select.option value="">No company</flux:select.option>
                        @foreach ($companies as $company)
                            <flux:select.option value="{{ $company->id }}"
                                :selected="old('current_company_id') == $company->id"
                            >
                                {{ $company->name }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="current_company_id" />
                </flux:field>
            </div>

            <div class="mt-6 flex justify-between gap-3">
                </flux:button>
                <flux:button type="submit" variant="primary">Create user</flux:button>
            </div>
        </form>
    </div>

</x-layouts::app>
