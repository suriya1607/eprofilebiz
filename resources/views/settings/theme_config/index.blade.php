@extends('layouts.app')
@section('title')
    {{ __('messages.settings') }}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
            @include('flash::message')
            @include('layouts.errors') 
            <div class="card">
                <div class="card-body d-md-flex">
                    @include('settings.front_cms.front_cms_menu')
                    {{ Form::open(['route' => ['setting.update.theme'], 'method' => 'post']) }}
                    @include('settings/theme_config/theme_configuration')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
