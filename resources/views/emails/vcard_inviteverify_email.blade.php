@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ getLogoUrl() }}" class="logo" alt="{{ getAppName() }}" style="height:auto;width:auto;object-fit:cover">
        @endcomponent
    @endslot

    {{-- Body --}}
    <div>
        <h2>Hello ,</h2>
        <p>
            <b>{{ $data['shared_by'] }}</b> has shared their <b>vCard</b> with you! 🎉  
            Click below to register, explore, and start sharing your own digital card.
        </p>
        @component('mail::button', ['url' => $data['url']])
            Register & Explore
        @endcomponent
        <p>Start connecting and share your profile with others instantly.</p>
        <p>Thanks & Regards,</p>
        <p>{{ getAppName() }}</p>
    </div>

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            <h6>© {{ date('Y') }} {{ getAppName() }}.</h6>
        @endcomponent
    @endslot
@endcomponent
