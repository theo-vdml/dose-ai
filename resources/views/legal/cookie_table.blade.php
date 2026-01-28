@php
    use Whitecube\LaravelCookieConsent\Facades\Cookies;
    use Carbon\Carbon;
@endphp

<div>
    @foreach(Cookies::getCategories() as $category)
            <h4>{{ $category->key() === 'essentials' ? 'Cookies Essentiels' : $category->title }}</h4>

            <p>
                @if($category->key() === 'essentials')
                    Ces cookies sont techniques et indispensables au fonctionnement du site.
                @else
                    {{ $category->description }}
                @endif
            </p>

            <div class="not-prose overflow-x-auto"> <table class="min-w-full text-left whitespace-nowrap">
                    <thead class="font-medium border-b border-white/10">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left">Nom</th>
                        <th scope="col" class="px-4 py-3 text-left">Fonction</th>
                        <th scope="col" class="px-4 py-3 text-right">Durée</th> </tr>
                    </thead>

                    <tbody class="divide-y divide-white/5">
                    @foreach($category->getCookies() as $cookie)
                        <tr>
                            <td class="px-4 py-4 font-mono text-sm">
                                {{ $cookie->name }}
                            </td>

                            <td class="px-4 py-4 whitespace-normal max-w-xs">
                                {{ $cookie->description }}
                            </td>

                            <td class="px-4 py-4 font-mono text-xs text-right">
                                {{ Carbon::now()->diffForHumans(Carbon::now()->addMinutes($cookie->duration), true) }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
    @endforeach
</div>
