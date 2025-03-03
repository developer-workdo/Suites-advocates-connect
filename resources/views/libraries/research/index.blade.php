@extends('layouts.app')

@section('page-title', __('Researches'))

@section('action-button')
    @can('create research')
        <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
            <a href="javascript:void(0)" class="mx-1 btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg"
                data-title="Add Research" data-url="{{ route('researches.create') }}" data-toggle="tooltip"
                title="{{ __('Create New Research') }}" data-bs-original-title="{{ __('Create New Research') }}"
                data-bs-placement="top" data-bs-toggle="tooltip">
                <i class="ti ti-plus"></i>
            </a>
        </div>

    @endsection
@endcan

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Researches') }}</li>
@endsection

@section('content')
    <div class="p-0 row">
        <div class="col-md-12">
            <div class="shadow-none card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        {!! $dataTable->table(['width' => '100%']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    @include('layouts.includes.datatable-css')
@endpush
@push('custom-script')
    @include('layouts.includes.datatable-js')
    {{ $dataTable->scripts() }}
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
@endpush
