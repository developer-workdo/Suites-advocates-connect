@extends('layouts.app')
@if (Auth::user()->type == 'super admin')
    @section('page-title', __('Companies'))
@else
    @section('page-title', __('Employees'))
@endif
@php
    $premission = [];
    if (\Auth::user()->super_admin_employee == 1) {
        $premission = json_decode(\Auth::user()->permission_json);
        $premission_arr = get_object_vars($premission);
    }
@endphp
@section('action-button')
    <div class="mb-3 row align-items-end">
        <div class="col-md-12 d-flex justify-content-sm-end">
            <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                <a href="{{ route('users.index') }}" class="mx-1 btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                    data-title="Add Employee" data-toggle="tooltip" title="{{ __('Grid View') }}"
                    data-bs-original-title="{{ __('Grid View') }}" data-bs-placement="top" data-bs-toggle="tooltip">
                    <i class="ti ti-border-all"></i>
                </a>
            </div>


            @if (Auth::user()->can('create member') ||
                    Auth::user()->can('create user') ||
                    (Auth::user()->super_admin_employee == 1 && in_array('create user', $premission_arr)))
                <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                    <a href="javascript:void(0)" class="mx-1 btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                        data-title="{{ Auth::user()->type == 'super admin' ? __('Create New Company') : __('Create New Employee') }}"
                        data-url="{{ route('users.create') }}" data-toggle="tooltip"
                        title="{{ Auth::user()->type == 'super admin' ? __('Create New Company') : __('Create New Employee') }}">
                        <i class="ti ti-plus"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('breadcrumb')
    @if (Auth::user()->type == 'super admin')
        <li class="breadcrumb-item">{{ __('Companies') }}</li>
    @else
        <li class="breadcrumb-item">{{ __('Employees') }}</li>
    @endif
@endsection
@section('content')
    <div class="p-0 row">
        <div class="col-xl-12">
            <div class="">
                <div class="card-header card-body table-border-style">
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
