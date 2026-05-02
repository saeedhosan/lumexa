<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component {
    public string $tokenName = '';
    public string $plainTextToken = '';
    public bool $showToken = false;
    public bool $showGenerateModal = false;
    public $tokens;

    public function mount(): void
    {
        $this->loadTokens();
    }

    public function openGenerateModal(): void
    {
        $this->resetErrorBag();
        $this->tokenName = '';
        $this->showGenerateModal = true;
    }

    public function generateToken(): void
    {
        $this->validate([
            'tokenName' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $token = $user->createToken($this->tokenName);

        $this->plainTextToken = $token->plainTextToken;
        $this->showToken = true;
        $this->showGenerateModal = false;
        $this->tokenName = '';
        $this->loadTokens();
    }

    public function revokeToken(int $tokenId): void
    {
        Auth::user()->tokens()->where('id', $tokenId)->delete();
        $this->loadTokens();
    }

    public function revokeAllTokens(): void
    {
        Auth::user()->tokens()->delete();
        $this->loadTokens();
    }

    public function loadTokens(): void
    {
        $this->tokens = Auth::user()->tokens()->latest()->get();
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('API Tokens') }}</flux:heading>

    <x-pages::settings.layout :heading="__('API Tokens')" :subheading="__('Create and manage API tokens to access the API from external applications')">
        @if ($showToken && $plainTextToken)
            <flux:card class="mb-6 bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800">
                <div class="flex items-start gap-3">
                    <flux:icon name="check-circle" class="mt-0.5 size-5 text-green-600 dark:text-green-400" />
                    <div class="flex-1">
                        <flux:heading size="sm" class="mb-1">{{ __('New API Token Created') }}</flux:heading>
                        <flux:text class="mb-3 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Copy this token now. You won\'t be able to see it again!') }}</flux:text>
                        <flux:input :value="$plainTextToken" readonly class="font-mono text-xs" />
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <flux:button wire:click="$set('showToken', false)" variant="subtle" size="sm">
                        {{ __('Dismiss') }}
                    </flux:button>
                </div>
            </flux:card>
        @endif

        <div class="flex items-center justify-between mb-4">
            @if($tokens->count() > 0)
                <div>
                    <flux:heading size="sm">{{ __('Active Tokens') }}</flux:heading>
                    <flux:subheading size="sm" class="mt-1">{{ __('Tokens that can access the API') }}</flux:subheading>
                </div>
                <div class="flex gap-3">
                    <flux:modal.trigger name="generate-token">
                        <flux:button variant="primary">
                            <flux:icon name="plus" class="size-4" />
                            <span class="ml-2">{{ __('Generate Token') }}</span>
                        </flux:button>
                    </flux:modal.trigger>

                    <flux:button
                        wire:click="revokeAllTokens"
                        wire:confirm="{{ __('Are you sure you want to revoke all tokens?') }}"
                        variant="danger"
                        size="sm"
                    >
                        {{ __('Revoke All') }}
                    </flux:button>
                </div>
            @else
                <div>
                    <flux:heading size="sm">{{ __('API Tokens') }}</flux:heading>
                    <flux:subheading size="sm" class="mt-1">{{ __('Generate a token to get started') }}</flux:subheading>
                </div>
                <flux:modal.trigger name="generate-token">
                    <flux:button variant="primary">
                        <flux:icon name="plus" class="size-4" />
                        <span class="ml-2">{{ __('Generate Token') }}</span>
                    </flux:button>
                </flux:modal.trigger>
            @endif
        </div>

        @if($tokens->count() > 0)
            <div class="overflow-x-auto">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>{{ __('Name') }}</flux:table.column>
                        <flux:table.column>{{ __('Expires At') }}</flux:table.column>
                        <flux:table.column>{{ __('Actions') }}</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach($tokens as $token)
                            <flux:table.row :key="$token->id">
                                <flux:table.cell>
                                    <div class="flex items-center gap-2">
                                        <flux:icon name="key" class="size-4 text-zinc-400" />
                                        <flux:text>{{ $token->name }}</flux:text>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:text variant="subtle" size="sm">
                                        {{ $token->expires_at ? $token->expires_at->format('M j, Y') : __('Never') }}
                                    </flux:text>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <div class="flex items-center gap-2">
                                        <flux:button
                                            wire:click="revokeToken({{ $token->id }})"
                                            wire:confirm="{{ __('Revoke this token?') }}"
                                            variant="ghost"
                                            size="sm"
                                            icon="trash"
                                        />
                                    </div>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </div>
        @else
            <flux:card class="border-dashed">
                <div class="text-center py-12">
                    <flux:icon name="key" class="mx-auto mb-4 size-12 text-zinc-300 dark:text-zinc-600" />
                    <flux:heading size="sm" class="mb-2">{{ __('No API Tokens') }}</flux:heading>
                    <flux:text variant="subtle" size="sm">{{ __('Generate your first token using the button above to get started with the API.') }}</flux:text>
                </div>
            </flux:card>
        @endif
    </x-pages::settings.layout>

    <flux:modal name="generate-token" :show="$showGenerateModal">
        <form wire:submit="generateToken" class="space-y-5 p-6">
            <div>
                <flux:heading size="lg">{{ __('Generate New Token') }}</flux:heading>
                <flux:subheading>{{ __('Create a token to authenticate API requests from external applications') }}</flux:subheading>
            </div>

            <flux:input
                wire:model="tokenName"
                :label="__('Token Name')"
                :placeholder="__('e.g., Mobile App, Third-party Integration')"
                required
                autofocus
            />

            @error('tokenName')
                <flux:text variant="danger" size="sm">{{ $message }}</flux:text>
            @enderror

            <div class="flex gap-3 justify-end">
                <flux:button type="button" variant="subtle" wire:click="$set('showGenerateModal', false)">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button type="submit" variant="primary">
                    {{ __('Generate Token') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>
</section>
