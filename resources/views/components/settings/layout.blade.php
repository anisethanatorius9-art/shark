<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <flux:navlist>

            <flux:navlist.item
                :href="route('settings.profile')"
                :active="request()->routeIs('settings.profile')"
                wire:navigate>
                {{ __('Profile') }}
            </flux:navlist.item>

            <flux:navlist.item
                :href="route('settings.password')"
                :active="request()->routeIs('settings.password')"
                wire:navigate>
                {{ __('Password') }}
            </flux:navlist.item>

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
            <flux:navlist.item
                :href="route('settings.two-factor')"
                :active="request()->routeIs('settings.two-factor')"
                wire:navigate>
                {{ __('Two-Factor Auth') }}
            </flux:navlist.item>
            @endif

            <flux:navlist.item
                :href="route('settings.appearance')"
                :active="request()->routeIs('settings.appearance')"
                wire:navigate>
                {{ __('Appearance') }}
            </flux:navlist.item>

        </flux:navlist>

        <!-- Customer Care Section -->
        <div class="mt-8 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">{{ __('Need Help?') }}</h3>
            <div class="space-y-2">
                <a href="https://wa.me/0775611999" target="_blank" rel="noopener noreferrer" class="w-full flex items-center justify-center gap-2 px-3 py-2 bg-black hover:bg-gray-800 text-white rounded-md font-medium transition dark:bg-black dark:hover:bg-gray-900">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.413-2.393-1.476-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-4.255.949c-.426.165-.809.342-1.123.645-.315.303-.528.659-.528 1.095 0 .468.203.875.6 1.272.397.397 1.063.922 1.756 1.329.693.407 1.5.704 2.306.876.393.074.813.125 1.243.125.43 0 .85-.051 1.243-.125.806-.172 1.613-.469 2.306-.876.693-.407 1.359-.932 1.756-1.329.397-.397.6-.804.6-1.272 0-.436-.213-.792-.528-1.095-.314-.303-.697-.48-1.123-.645a9.87 9.87 0 00-4.255-.949z" />
                    </svg>
                    WhatsApp
                </a>
                <a href="mailto:aanatorius@gmail.com" class="w-full flex items-center justify-center gap-2 px-3 py-2 bg-black hover:bg-gray-800 text-white rounded-md font-medium transition dark:bg-black dark:hover:bg-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Email
                </a>
            </div>
        </div>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
