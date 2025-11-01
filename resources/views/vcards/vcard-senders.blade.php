@extends('layouts.app')
@section('title')
    {{ __('messages.vcard.senders') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mb-5 d-inline-block">{{ __('messages.vcard.senders') }}</h1>
            <a href="{{ route('vcards.index') }}" class="btn btn-outline-primary">
                {{ __('messages.common.back') }}
            </a>
        </div>

        <div class="d-flex flex-column table-striped">
            @include('flash::message')
            <livewire:vcard-senders lazy :vcardId="$vcardId" />
        </div>
    </div>
@endsection