<x-layouts::auth>
    <flux:heading class="text-center">Welcome to Lumexa</flux:heading>

    <flux:separator class="my-6" />

    @if ($step === 1)
        <div class="space-y-4 text-center">
            <flux:text class="text-lg">
                You're just a few steps away from setting up your workspace.
            </flux:text>

            <flux:text class="text-zinc-500 dark:text-zinc-400">
                We'll help you get started by setting up your company profile and preferences.
            </flux:text>

            <div class="pt-4">
                <flux:button wire:click="nextStep" variant="primary" class="w-full">
                    Get Started
                </flux:button>
            </div>
        </div>
    @elseif ($step === 2)
        <div class="space-y-4">
            <flux:heading level="2" class="text-center">Company Details</flux:heading>

            <flux:text class="text-center text-zinc-500 dark:text-zinc-400">
                Tell us about your company. You can change these later.
            </flux:text>

            <flux:field>
                <flux:label>Company Name</flux:label>
                <flux:input wire:model="companyName" placeholder="Acme Inc." />
                <flux:error name="companyName" />
            </flux:field>

            <div class="flex justify-between pt-4">
                <flux:button wire:click="previousStep" variant="ghost">
                    Back
                </flux:button>
                <flux:button wire:click="nextStep" variant="primary">
                    Continue
                </flux:button>
            </div>
        </div>
    @elseif ($step === 3)
        <div class="space-y-4">
            <flux:heading level="2" class="text-center">Your Profile</flux:heading>

            <flux:text class="text-center text-zinc-500 dark:text-zinc-400">
                Confirm your display name.
            </flux:text>

            <flux:field>
                <flux:label>Full Name</flux:label>
                <flux:input wire:model="name" placeholder="Your name" />
                <flux:error name="name" />
            </flux:field>

            <div class="flex justify-between pt-4">
                <flux:button wire:click="previousStep" variant="ghost">
                    Back
                </flux:button>
                <flux:button wire:click="complete" variant="primary">
                    Complete Setup
                </flux:button>
            </div>
        </div>
    @elseif ($step === 4)
        <div class="space-y-4 text-center">
            <flux:heading level="2">You're All Set!</flux:heading>

            <flux:text class="text-zinc-500 dark:text-zinc-400">
                Your workspace is ready. We're redirecting you to the dashboard...
            </flux:text>
        </div>
    @endif

    <flux:separator class="my-6" />

    <div class="flex justify-center gap-2">
        @for ($i = 1; $i <= 3; $i++)
            <div class="h-2 w-2 rounded-full {{ $step >= $i ? 'bg-zinc-800 dark:bg-white' : 'bg-zinc-300 dark:bg-zinc-600' }}"></div>
        @endfor
    </div>
</x-layouts::auth>
