<x-guest-layout>
    <flux:heading size="xl">Accept Invitation</flux:heading>
    <flux:subheading class="mb-6">
        You've been invited to join <strong>{{ $invite->company->name }}</strong>.
        Create your account to get started.
    </flux:subheading>

    <form method="POST" action="{{ route('invite.process', $invite) }}">
        @csrf

        <div class="space-y-6">
            <flux:field>
                <flux:label>Your Name</flux:label>
                <flux:input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter your full name"
                    required
                />
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Email</flux:label>
                <flux:input
                    type="email"
                    name="email"
                    value="{{ $invite->email }}"
                    disabled
                />
                <flux:text size="xs" class="text-zinc-500">Your email cannot be changed</flux:text>
            </flux:field>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:field>
                    <flux:label>Password</flux:label>
                    <flux:input
                        type="password"
                        name="password"
                        placeholder="Create a password"
                        required
                    />
                    <flux:error name="password" />
                </flux:field>

                <flux:field>
                    <flux:label>Confirm Password</flux:label>
                    <flux:input
                        type="password"
                        name="password_confirmation"
                        placeholder="Confirm your password"
                        required
                    />
                    <flux:error name="password_confirmation" />
                </flux:field>
            </div>
        </div>

        <div class="mt-6">
            <flux:button type="submit" variant="primary" class="w-full">Create Account</flux:button>
        </div>
    </form>
</x-guest-layout>