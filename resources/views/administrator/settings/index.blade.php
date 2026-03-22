<x-layouts::administrator>
    <div class="mx-auto max-w-2xl space-y-6">
        <div>
            <flux:heading size="lg">Core Settings</flux:heading>
            <flux:text class="mt-1">Manage your application core settings</flux:text>
        </div>

        @if (session('success'))
            <flux:callout color="green" icon="check-circle">
                {{ session('success') }}
            </flux:callout>
        @endif
        @if (session()->has('errors') && $errors->any())
            <flux:callout color="red" icon="check-circle">
                {{ $errors->first() }}
            </flux:callout>
        @endif

        <form
            method="POST"
            action="{{ route('super.settings.store') }}"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf

            <flux:card>
                <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                    <flux:heading size="sm">Application Info</flux:heading>
                    <flux:text variant="muted" size="sm">Basic application information
                    </flux:text>
                </div>

                <div class="p-6 space-y-4">
                    <flux:field>
                        <flux:label>Application Name</flux:label>
                        <flux:input name="APP_NAME" :value="old('APP_NAME', config('app.name'))"
                            placeholder="Enter application name"
                        />
                        @error('APP_NAME')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label>Application Title</flux:label>
                        <flux:input name="APP_TITLE" :value="old('APP_TITLE', config('app.title'))"
                            placeholder="Enter application title"
                        />
                        @error('APP_TITLE')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label>Application URL</flux:label>
                        <flux:input
                            name="APP_URL"
                            :value="old('APP_URL', config('app.url'))"
                            type="url"
                            placeholder="https://example.com"
                        />
                        @error('APP_URL')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>
                </div>
            </flux:card>

            <flux:card>
                <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                    <flux:heading size="sm">Environment</flux:heading>
                    <flux:text variant="muted" size="sm">Application environment and debug
                        settings</flux:text>
                </div>

                <div class="p-6 space-y-4">
                    <flux:field>

                        <flux:label>Environment</flux:label>

                        @php $env = old('APP_ENV', config('app.env')); @endphp

                        <flux:select name="APP_ENV">
                            <flux:select.option value="local" :selected="$env === 'local'">Local
                            </flux:select.option>
                            <flux:select.option value="production"
                                :selected="$env === 'production'">Production</flux:select.option>
                            <flux:select.option value="testing" :selected="$env === 'testing'">
                                Testing</flux:select.option>
                        </flux:select>

                        @error('APP_ENV')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror

                    </flux:field>

                    <flux:field>
                        <flux:label>Debug Mode</flux:label>
                        <div class="flex items-center gap-3">
                            <input type="hidden" name="APP_DEBUG" value="0">
                            <flux:switch name="APP_DEBUG" value="1"
                                :checked="old('APP_DEBUG', config('app.debug'))"
                            />
                            <flux:text size="sm" variant="muted">
                                {{ config('app.debug') ? 'Debug mode is enabled' : 'Debug mode is disabled' }}
                            </flux:text>
                        </div>
                        @error('APP_DEBUG')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>
                </div>
            </flux:card>

            <flux:card>
                <div class="px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
                    <flux:heading size="sm">Branding</flux:heading>
                    <flux:text variant="muted" size="sm">Application logo and favicon
                    </flux:text>
                </div>

                <div class="p-6 space-y-4">
                    <flux:field>
                        <flux:label>Logo</flux:label>
                        <flux:input name="APP_LOGO" type="file" accept="image/*" />
                        @error('APP_LOGO')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>

                    <flux:field>
                        <flux:label>Favicon</flux:label>
                        <flux:input name="APP_FACICON" type="file" accept="image/*" />
                        @error('APP_FACICON')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:field>
                </div>
            </flux:card>

            <div class="flex items-center justify-end gap-3">
                <flux:button variant="primary" type="submit">
                    {{ __('Save Changes') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::administrator>
