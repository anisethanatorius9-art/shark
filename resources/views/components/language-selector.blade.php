<div x-data="languageSelector()" x-init="init()" class="relative">
    <!-- Language Button -->
    <button
        @click="toggleDropdown()"
        class="flex items-center gap-2 px-3 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 rounded-lg transition-colors">
        <span class="text-lg" x-text="currentFlag"></span>
        <span x-text="currentLanguage"></span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        @click.away="closeDropdown()"
        class="absolute right-0 mt-2 w-48 bg-white dark:bg-zinc-800 rounded-lg shadow-lg border border-zinc-200 dark:border-zinc-700 py-1 z-50">
        <!-- Detected Location Info -->
        <template x-if="detectedCountry && !userPreference">
            <div class="px-3 py-2 text-xs text-zinc-500 dark:text-zinc-400 border-b border-zinc-100 dark:border-zinc-700">
                <span x-text="' Detected: ' + detectedCountry"></span>
            </div>
        </template>

        <!-- Language Options -->
        <template x-for="lang in languages" :key="lang.code">
            <button
                @click="changeLanguage(lang.code)"
                :class="{'bg-zinc-100 dark:bg-zinc-700': currentLocale === lang.code}"
                class="w-full flex items-center gap-3 px-3 py-2 text-sm text-left hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                <span class="text-lg" x-text="lang.flag"></span>
                <div class="flex flex-col">
                    <span class="font-medium" x-text="lang.name"></span>
                    <span class="text-xs text-zinc-500 dark:text-zinc-400" x-text="lang.native_name"></span>
                </div>
                <svg x-show="currentLocale === lang.code" class="w-4 h-4 ml-auto text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </template>

        <!-- Refresh Detection Button -->
        <template x-if="detectedCountry">
            <div class="border-t border-zinc-100 dark:border-zinc-700 mt-1 pt-1">
                <button
                    @click="refreshDetection()"
                    class="w-full flex items-center gap-2 px-3 py-2 text-xs text-zinc-500 hover:bg-zinc-50 dark:hover:bg-zinc-700">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh location detection
                </button>
            </div>
        </template>
    </div>
</div>

<script>
    function updateTranslations(translations) {
        document.querySelectorAll('[data-lang-key]').forEach(el => {
            const key = el.getAttribute('data-lang-key');
            if (translations[key]) {
                el.textContent = translations[key];
            }
        });
    }

    function languageSelector() {
        return {
            isOpen: false,
            currentLocale: '{{ app()->getLocale() }}',
            currentLanguage: '{{ \App\Services\LanguageHelper::getLanguageName(app()->getLocale()) }}',
            currentFlag: '{{ \App\Services\LanguageHelper::getLanguageFlag(app()->getLocale()) }}',
            languages: [],
            detectedCountry: null,
            userPreference: false,

            async init() {
                // Fetch available languages
                try {
                    const response = await fetch('/language/current');
                    const data = await response.json();

                    this.languages = data.available_languages;
                    this.currentLocale = data.current_language;
                    this.currentLanguage = data.language_name;
                    this.currentFlag = data.language_flag;
                    this.userPreference = data.is_authenticated;

                    if (data.detected_country && !data.detected_country.is_localhost) {
                        this.detectedCountry = data.detected_country.country;
                    }

                    // Load initial translations
                    const translationsResponse = await fetch('/language/translations?lang=' + data.current_language);
                    const translations = await translationsResponse.json();
                    updateTranslations(translations);
                } catch (error) {
                    console.error('Failed to load language data:', error);
                    // Fallback to static data
                    this.languages = [{
                            code: 'en',
                            name: 'English',
                            flag: '🇺🇸',
                            native_name: 'English'
                        },
                        {
                            code: 'sw',
                            name: 'Swahili',
                            flag: '🇹🇿',
                            native_name: 'Kiswahili'
                        }
                    ];
                }
            },

            toggleDropdown() {
                this.isOpen = !this.isOpen;
            },

            closeDropdown() {
                this.isOpen = false;
            },

            async changeLanguage(langCode) {
                try {
                    const response = await fetch('/language/set-ajax', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            language: langCode
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.currentLocale = data.language;
                        this.currentLanguage = data.language_name;
                        this.currentFlag = data.language_flag;

                        // Update translations on the page
                        updateTranslations(data.translations);
                    }
                } catch (error) {
                    console.error('Failed to change language:', error);
                    // Fallback to form submission
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/language/change?redirect=' + encodeURIComponent(window.location.pathname);

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const languageInput = document.createElement('input');
                    languageInput.type = 'hidden';
                    languageInput.name = 'language';
                    languageInput.value = langCode;

                    form.appendChild(csrfToken);
                    form.appendChild(languageInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            },

            async refreshDetection() {
                try {
                    const response = await fetch('/language/detect');
                    const data = await response.json();

                    if (data.detected) {
                        this.detectedCountry = data.detected_country?.country || null;
                        // Optionally auto-select the detected language
                        // this.changeLanguage(data.language);
                    }
                } catch (error) {
                    console.error('Failed to refresh detection:', error);
                }
            }
        };
    }
</script>
