@extends('layouts.app')
@section('page-title')
    {{ __('Settings') }}
@endsection

@php
    $companyLogo = App\Models\Utility::getValByName('company_logo_dark');
@endphp
@section('action-button')
    @can('create case law by area category')
        <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
            <a href="javascript:void(0)" class="mx-1 btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                data-title="Add Case Law By Area Category" data-url="{{ $createUrl }}"
                data-toggle="tooltip" title="{{ $title }}"
                data-bs-original-title="{{ $title }}" data-bs-placement="top"
                data-bs-toggle="tooltip">
                <i class="ti ti-plus"></i>
            </a>
        </div>

    @endsection
@endcan
@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Settings') }}</li>
@endsection
@section('content')
    <div class="card">
        <div class="row g-0">
            <div class="col-3 d-none d-md-block border-end">
                @include('libraries.settings.setting-sidebar')
            </div>
            <div class="col d-flex flex-column">
                {!! $dataTable->table(['width' => '100%']) !!}
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
