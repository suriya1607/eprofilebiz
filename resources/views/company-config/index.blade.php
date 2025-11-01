@extends('layouts.app')
@section('title')
    Company Configure
@endsection
@section('content')
@section('content')
    <div class="container-fluid">
        <div class="d-flex flex-column">
<div class="card w-100">
    <div class="card-body">
        {{ Form::open(['route' => 'company-configure.store', 'files' => true]) }}

        <div class="mb-3">
            {{ Form::label('name', 'Company Name:', ['class' => 'form-label required']) }}
            {{ Form::text('name', $config->name ?? '', ['class' => 'form-control', 'required']) }}
        </div>

        <div class="mb-3">
            {{ Form::label('logo', 'Company Logo:', ['class' => 'form-label']) }}
            <input type="file" name="logo" class="form-control">
            @if(!empty($config->logo))
                <img src="{{ asset('/'.$config->logo) }}" height="80" class="mt-2">
            @endif
        </div>

        <div class="mb-3">
            {{ Form::label('description', 'Description:', ['class' => 'form-label']) }}
            {{ Form::textarea('description', $config->description ?? '', ['class' => 'form-control']) }}
        </div>

        <div class="mb-3">
            {{ Form::label('social_links', 'Social Links:', ['class' => 'form-label']) }}
            {{ Form::text('social_links[facebook]', $config->social_links['facebook'] ?? '', ['class' => 'form-control mb-2', 'placeholder' => 'Facebook URL']) }}
            {{ Form::text('social_links[linkedin]', $config->social_links['linkedin'] ?? '', ['class' => 'form-control mb-2', 'placeholder' => 'LinkedIn URL']) }}
            {{ Form::text('social_links[twitter]', $config->social_links['twitter'] ?? '', ['class' => 'form-control mb-2', 'placeholder' => 'Twitter URL']) }}
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        {{ Form::close() }}
    </div>
</div>
    </div>
</div>
@endsection
