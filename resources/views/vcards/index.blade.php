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

@push('scripts')
    <script>
$(document).on("click", ".vcardPwaStatus", function () {
            let vcardId = $(this).data("id");
            let updateUrl = route("vcard.pwa.status", vcardId); 
            $.ajax({
                type: "get",
                url: updateUrl,
                success: function (response) {
                    displaySuccessMessage(response.message);
                    Livewire.dispatch("refresh");
                },
                error: function (error) {
                    displayErrorMessage(error.responseJSON.message);
                },
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
@endpush
