@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset(getAppLogo()) }}" class="logo"
                 style="height:auto!important;width:auto!important;object-fit:cover"
                 alt="{{ getAppName() }}">
        @endcomponent
    @endslot

    <p>
        {{ __('messages.mail.hello') }}
        {{ $user->first_name }} {{ $user->last_name }},
    </p>
    <p>
        @if($type === 'daily')
            {{ __('messages.mail.card_view_daily_msg', [
                'vcard' => $vcard->name,
                'count' => $count
            ]) }}
        @else
            {{ __('messages.mail.card_view_weekly_msg', [
                'vcard' => $vcard->name,
                'count' => $count
            ]) }}
        @endif
    </p>
    <p>{{ __('messages.mail.card_view_note') }}</p>
    <p>
        {{ __('messages.mail.thanks_regard') }},<br>
        <strong>{{ getAppName() }}</strong>
    </p>

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            <h6>© {{ date('Y') }} {{ getAppName() }}.</h6>
        @endcomponent
    @endslot
@endcomponent

