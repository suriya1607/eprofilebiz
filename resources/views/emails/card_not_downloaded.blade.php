@component('mail::layout')
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset(getAppLogo()) }}" class="logo" style="height:auto!important;width:auto!important;object-fit:cover" alt="{{ getAppName() }}">
        @endcomponent
    @endslot

    <p>Hello {{ $data['first_name'] }} {{ $data['last_name'] }},</p>
    <p>We noticed you’ve already created your digital card but haven’t downloaded it yet.  
    Please download it now and keep it handy for sharing.</p>
    <p>
        @component('mail::button', ['url' => url($data['alias'])])
            Download Your Digital Card
        @endcomponent
    </p>
    <p>Thanks & Regards,<br>{{ getAppName() }}</p>
    @slot('footer')
        @component('mail::footer')
            <h6>© {{ date('Y') }} {{ getAppName() }}.</h6>
        @endcomponent
    @endslot
@endcomponent
