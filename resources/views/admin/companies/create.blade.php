<x-layouts::app title="Create Company">

    <div class="max-w-2xl">
        <flux:heading size="xl">Create Company</flux:heading>
        <flux:subheading class="mb-6">Add a new company to the system.</flux:subheading>

        <form method="POST" action="{{ route('admin.companies.store') }}">
            @csrf

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Name</flux:label>
                        <flux:input
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Enter company name"
                            required
                        />
                        <flux:error name="name" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Title</flux:label>
                        <flux:input
                            name="title"
                            value="{{ old('title') }}"
                            placeholder="Enter company title"
                        />
                        <flux:error name="title" />
                    </flux:field>
                </div>

                <flux:field>
                    <flux:label>Description</flux:label>
                    <flux:textarea
                        name="description"
                        placeholder="Enter company description"
                    >{{ old('description') }}</flux:textarea>
                    <flux:error name="description" />
                </flux:field>

                <flux:field>
                    <flux:label>Logo URL</flux:label>
                    <flux:input
                        name="logo"
                        value="{{ old('logo') }}"
                        placeholder="https://example.com/logo.png"
                    />
                    <flux:error name="logo" />
                </flux:field>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Country</flux:label>
                        <flux:select name="country" value="{{ old('country', 'US') }}">
                            <flux:select.option value="US">United States</flux:select.option>
                            <flux:select.option value="UK">United Kingdom</flux:select.option>
                            <flux:select.option value="CA">Canada</flux:select.option>
                            <flux:select.option value="AU">Australia</flux:select.option>
                            <flux:select.option value="DE">Germany</flux:select.option>
                            <flux:select.option value="FR">France</flux:select.option>
                            <flux:select.option value="BD">Bangladesh</flux:select.option>
                            <flux:select.option value="IN">India</flux:select.option>
                        </flux:select>
                        <flux:error name="country" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Language</flux:label>
                        <flux:select name="language" value="{{ old('language', 'en') }}">
                            <flux:select.option value="en">English</flux:select.option>
                            <flux:select.option value="bn">Bengali</flux:select.option>
                            <flux:select.option value="ar">Arabic</flux:select.option>
                            <flux:select.option value="fr">French</flux:select.option>
                            <flux:select.option value="de">German</flux:select.option>
                        </flux:select>
                        <flux:error name="language" />
                    </flux:field>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Timezone</flux:label>
                        <flux:select name="timezone" value="{{ old('timezone', 'UTC') }}">
                            <flux:select.option value="UTC">UTC</flux:select.option>
                            <flux:select.option value="America/New_York">Eastern Time (US)</flux:select.option>
                            <flux:select.option value="America/Chicago">Central Time (US)</flux:select.option>
                            <flux:select.option value="America/Los_Angeles">Pacific Time (US)</flux:select.option>
                            <flux:select.option value="Europe/London">London</flux:select.option>
                            <flux:select.option value="Europe/Paris">Paris</flux:select.option>
                            <flux:select.option value="Asia/Dhaka">Dhaka</flux:select.option>
                            <flux:select.option value="Asia/Kolkata">Kolkata</flux:select.option>
                        </flux:select>
                        <flux:error name="timezone" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Currency</flux:label>
                        <flux:select name="currency" value="{{ old('currency', 'USD') }}">
                            <flux:select.option value="USD">USD ($)</flux:select.option>
                            <flux:select.option value="EUR">EUR (€)</flux:select.option>
                            <flux:select.option value="GBP">GBP (£)</flux:select.option>
                            <flux:select.option value="BDT">BDT (৳)</flux:select.option>
                            <flux:select.option value="INR">INR (₹)</flux:select.option>
                        </flux:select>
                        <flux:error name="currency" />
                    </flux:field>
                </div>
            </div>

            <div class="mt-6 flex justify-between gap-3">
                <flux:button :href="route('admin.companies.index')" variant="ghost" wire:navigate>Cancel
                </flux:button>
                <flux:button type="submit" variant="primary">Create company</flux:button>
            </div>
        </form>
    </div>

</x-layouts::app>