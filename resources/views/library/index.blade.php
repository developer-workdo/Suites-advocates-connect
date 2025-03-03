@extends('layouts.app')

@section('page-title', __('Library'))

@section('action-button')
    @can('create library')
        <div class="mx-1 text-sm-end d-flex all-button-box justify-content-sm-end">
            <a href="{{ route('library.create') }}" class="btn btn-sm btn-primary " data-toggle="tooltip"
                title="{{ __('Create Library') }}" data-bs-original-title="{{ __('Add Library') }}" data-bs-placement="top"
                data-bs-toggle="tooltip">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endcan

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Library') }}</li>
@endsection

@section('content')

    <div class="p-0 row">
        <div class="col-xl-12">

            <div class="card-header card-body table-border-style">
                <h5></h5>
                <div class="table-responsive">
                    <table class="table dataTable">
                        <thead>
                            <tr>
                                <th>{{ __('S.No.') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Case No.') }}</th>
                                <th width="100px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($libraries as $key => $library)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $library->case->title }}</td>
                                    <td>
                                        {{ !empty($library->case->case_number) ? $library->case->case_number : ' ' }}
                                    </td>
                                    <td>
                                        @can('view library')
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="{{ route('library.show', $library->id) }}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                    data-title="{{ __('View Cause') }}" title="{{ __('View') }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"><i
                                                        class="ti ti-eye "></i></a>
                                            </div>
                                        @endcan

                                        @can('edit library')
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="{{ route('library.edit', $library->id) }}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                    title="{{ __('Edit') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top">
                                                    <i class="ti ti-edit "></i>
                                                </a>
                                            </div>
                                        @endcan
                                        @can('delete library')
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                    data-confirm-yes="delete-form-{{ $library->id }}"
                                                    title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                    data-bs-placement="top">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </div>
                                        @endcan
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['library.destroy', $library->id],
                                            'id' => 'delete-form-' . $library->id,
                                        ]) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
