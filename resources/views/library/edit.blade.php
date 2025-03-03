@php
    $file_validation = App\Models\Utility::file_upload_validation();
@endphp
@extends('layouts.app')

@section('page-title', __('Edit Library'))


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __(' Edit Library') }}</li>
@endsection
@section('content')
    {{ Form::model($library, [
        'route' => ['library.update', $library->id],
        'method' => 'put',
        'enctype' => 'multipart/form-data',
    ]) }}
        @include('library.form')
    {{ Form::close() }}
@endsection
@push('custom-script')
    <script src="{{ asset('public/assets/js/jquery-ui.js') }}"></script>

    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
    <script>
        $('.summernote').summernote({
            dialogsInBody: !0,
            minHeight: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                ['list', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'unlink']],
            ]
        });
    </script>
@endpush
