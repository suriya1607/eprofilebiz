@component('mail::layout')
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset(getAppLogo()) }}" class="logo" style="height:auto!important;width:auto!important;object-fit:cover" alt="{{ getAppName() }}">
        @endcomponent
    @endslot
    <p>Hello {{ $data['first_name'] }} {{ $data['last_name'] }},</p>
    <p>It looks like you haven’t created your digital card yet. To complete your profile, please create your digital card now.</p>
    <p>
        @component('mail::button', ['url' => route('vcards.create')])
            Create Your Digital Card
        @endcomponent
    </p>
    <p>Thanks & Regards,<br>{{ getAppName() }}</p>
    @slot('footer')
        @component('mail::footer')
            <h6>© {{ date('Y') }} {{ getAppName() }}.</h6>
        @endcomponent
    @endslot
@endcomponent
