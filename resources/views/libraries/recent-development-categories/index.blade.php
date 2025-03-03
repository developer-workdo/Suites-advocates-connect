@extends('layouts.app')

@section('page-title', __('Recent Development Categories'))

@section('action-button')
    @can('create recent development category')
        <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
            <a href="javascript:void(0)" class="mx-1 btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                data-title="Add Recent Development Category" data-url="{{ route('recent-development-categories.create') }}"
                data-toggle="tooltip" title="{{ __('Create New Recent Development Category') }}"
                data-bs-original-title="{{ __('Create New Recent Development Category') }}" data-bs-placement="top"
                data-bs-toggle="tooltip">
                <i class="ti ti-plus"></i>
            </a>
        </div>

    @endsection
@endcan

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Recent Development Categories') }}</li>
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
@endpush
