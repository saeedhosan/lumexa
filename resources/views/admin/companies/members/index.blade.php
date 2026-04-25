<x-layouts::app title="{{ $company->name }} - Members">

    <div class="max-w-4xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <flux:heading size="xl">{{ $company->name }} - Members</flux:heading>
                <flux:subheading>Manage company members and roles.</flux:subheading>
            </div>
            <flux:button :href="route('admin.companies.show', $company)" variant="ghost" wire:navigate>
                Back to Company
            </flux:button>
        </div>

        <flux:card class="mb-6">
            <flux:heading level="2" size="sm" class="mb-4">Add New Member</flux:heading>

            <form method="POST" action="{{ route('admin.companies.members.store', $company) }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <flux:field>
                        <flux:label>Name</flux:label>
                        <flux:input name="name" value="{{ old('name') }}" placeholder="Full name" required />
                        <flux:error name="name" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Email</flux:label>
                        <flux:input type="email" name="email" value="{{ old('email') }}" placeholder="Email address" required />
                        <flux:error name="email" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Password</flux:label>
                        <flux:input type="password" name="password" placeholder="Password" required />
                        <flux:error name="password" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Role</flux:label>
                        <flux:select name="role" value="{{ old('role', 'admin') }}">
                            <flux:select.option value="admin">Admin</flux:select.option>
                            <flux:select.option value="customer">Customer</flux:select.option>
                        </flux:select>
                    </flux:field>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <flux:field>
                        <flux:label>Status</flux:label>
                        <flux:select name="status" value="{{ old('status', 'active') }}">
                            @foreach ($statuses as $status)
                                <flux:select.option value="{{ $status }}">{{ ucfirst($status) }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:field>

                    <flux:field>
                        <flux:label>Type</flux:label>
                        <flux:select name="type" value="{{ old('type', 'customer') }}">
                            @foreach ($types as $type)
                                <flux:select.option value="{{ $type }}">{{ ucfirst($type) }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:field>
                </div>

                <flux:button type="submit" variant="primary">Add Member</flux:button>
            </form>
        </flux:card>

        <flux:card>
            <flux:heading level="2" size="sm" class="mb-4">Current Members ({{ $members->count() }})</flux:heading>

            @if ($members->isEmpty())
                <div class="text-center py-8">
                    <flux:icon.users variant="outline" class="mx-auto h-12 w-12 text-zinc-300 dark:text-zinc-600" />
                    <flux:heading level="3" size="sm" class="mt-4 text-zinc-500">No members yet</flux:heading>
                    <flux:text size="sm" class="text-zinc-400">Add members using the form above.</flux:text>
                </div>
            @else
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Name</flux:table.column>
                        <flux:table.column>Email</flux:table.column>
                        <flux:table.column>Role</flux:table.column>
                        <flux:table.column>Status</flux:table.column>
                        <flux:table.column>Joined</flux:table.column>
                        <flux:table.column></flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach ($members as $member)
                            <flux:table.row :key="$member->id">
                                <flux:table.cell>
                                    <div class="flex items-center gap-3">
                                        <flux:avatar :name="$member->name" size="sm" />
                                        <div>
                                            <flux:heading level="3" size="sm">{{ $member->name }}</flux:heading>
                                        </div>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:text size="sm">{{ $member->email }}</flux:text>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:badge :color="$member->pivot->role === 'admin' ? 'blue' : 'zinc'" size="sm">
                                        {{ ucfirst($member->pivot->role) }}
                                    </flux:badge>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:badge :color="$member->status->color()" size="sm">
                                        {{ $member->status->label() }}
                                    </flux:badge>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:text size="sm" class="text-zinc-500">
                                        {{ $member->pivot->created_at->format('M d, Y') }}
                                    </flux:text>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <form method="POST" action="{{ route('admin.companies.members.destroy', [$company, $member]) }}"
                                        onsubmit="return confirm('Remove this member from the company?');">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button type="submit" variant="ghost" size="sm" icon="trash" />
                                    </form>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            @endif
        </flux:card>
    </div>

</x-layouts::app>