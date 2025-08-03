@extends('layouts.app')
@section('title')
    {{__('messages.vcards')}}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column table-striped">
            @include('flash::message')
            @if(getLogInUser()->vcard_table_view_type == 0)
            <livewire:vcard-lists lazy/>
            @else
            <livewire:user-vcard-table lazy/>
            @endif
        </div>
    </div>

    @include('layouts.templates.actions')
    @include('vcards.templates.templates')
    @include('vcards.templates.analytics')
@endsection
