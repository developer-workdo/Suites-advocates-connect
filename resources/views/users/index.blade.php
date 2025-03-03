@extends('layouts.app')

@section('page-title', __('Companies'))

@section('action-button')
    <div class="mb-3 row align-items-center">
        <div class="col-md-12 d-flex align-items-center justify-content-end">
            <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                <a href="{{ route('users.list') }}" class="mx-1 btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                    data-title="Add User" data-toggle="tooltip" title="{{ __('List View') }}"
                    data-bs-original-title="{{ __('List View') }}" data-bs-placement="top" data-bs-toggle="tooltip">
                    <i class="ti ti-menu-2"></i>
                </a>
            </div>

            @if (\Auth::user()->type == 'company')
                <a href="{{ route('userlog.index') }}" class="m-1 btn btn-sm btn-primary btn-icon"
                    data-bs-toggle="tooltip"title="{{ __('User Log') }}">
                    <i class="ti ti-user-check"></i>
                </a>
            @endif

            @canany(['create member', 'create user'])
                <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                    <a href="javascript:void(0)" class="mx-1 btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                        data-title="{{ Auth::user()->type == 'super admin' ? __('Create New Company') : __('Create New Employee') }}"
                        data-url="{{ route('users.create') }}" data-toggle="tooltip"
                        title="{{ Auth::user()->type == 'super admin' ? __('Create New Company') : __('Create New Employee') }}">
                        <i class="ti ti-plus"></i>
                    </a>
                </div>
            </div>
        </div>
    @endcan

@endsection

@section('breadcrumb')

    <li class="breadcrumb-item">{{ __('Companies') }}</li>

@endsection

@section('content')


    <div class="pt-0 row g-0">
        <div class="col-xxl-12">
            <div class="row g-0">

                @foreach ($users as $user)
                    <div class="col-md-6 col-xxl-3 col-lg-4 col-sm-6 border-end border-bottom">
                        <div class="text-center bg-transparent border shadow-none card h-100 rounded-0">
                            <div class="pb-0 border-0 card-header">


                                @if (Gate::check('delete member') || Gate::check('delete user'))
                                    <div class="card-header-right">
                                        <div class="btn-group card-option">

                                            @if (Auth::user()->type == 'super admin')
                                                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>

                                                <div class="dropdown-menu dropdown-menu-end">

                                                    @if ($user->email_verified_at == null || $user->email_verified_at == '')
                                                        <a href="javascript:void(0)" class="dropdown-item bs-pass-para "
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="verify-form-{{ $user->id }}"
                                                            title="{{ __('Verify Email') }}" data-bs-toggle="tooltip"
                                                            data-bs-placement="top">
                                                            <i class="ti ti-checks"></i>
                                                            {{ __('Verify Email') }}

                                                        </a>
                                                        {!! Form::open([
                                                            'method' => 'POST',
                                                            'route' => ['users.verify', $user->id],
                                                            'id' => 'verify-form-' . $user->id,
                                                        ]) !!}
                                                        {!! Form::close() !!}
                                                    @else
                                                        <a href="javascript:void(0)" class="dropdown-item" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="{{ __('verified Email') }}"
                                                            data-size="md" data-title="{{ __('verified Email') }}">
                                                            <i class="ti ti-checks "></i>
                                                            {{ __('Verified Email') }}
                                                        </a>
                                                    @endif



                                                    @canany(['edit member', 'edit user'])


                                                        <a href="{{ route('login.with.admin', $user->id) }}"
                                                            class="dropdown-item" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="{{ __('Login') }}">
                                                            <i class="py-1 ti ti-replace"></i>
                                                            <span>{{ __('Login') }}</span>
                                                        </a>

                                                        <a href="{{ route('users.edit', $user->id) }}" class="dropdown-item"
                                                            data-bs-original-title="{{ __('Edit User') }}">
                                                            <i class="ti ti-pencil"></i>
                                                            <span>{{ __('Edit') }}</span>
                                                        </a>
                                                    @endcan

                                                    <a href="#!"
                                                        data-url="{{ route('company.reset', \Crypt::encrypt($user->id)) }}"
                                                        data-ajax-popup="true" data-size="md" class="dropdown-item"
                                                        data-bs-original-title="{{ __('Reset Password') }}"
                                                        data-title="{{ __('Reset Password') }}"
                                                        title="{{ __('Reset Password') }}">
                                                        <i class="ti ti-adjustments"></i>
                                                        <span> {{ __('Reset Password') }}</span>
                                                    </a>

                                                    @canany(['delete member', 'delete user'])
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['users.destroy', $user->id],
                                                            'id' => 'delete-form-' . $user->id,
                                                        ]) !!}
                                                        <a href="javascript:void(0)" class="dropdown-item bs-pass-para-user-delete"
                                                            data-id="{{ $user['id'] }}"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $user->id }}"
                                                            title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                            data-bs-placement="top">
                                                            <i class="ti ti-archive"></i>
                                                            <span> {{ __('Delete') }}</span>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    @endcan
                                                </div>
                                            @else
                                                @if ($user->is_active == 1 && $user->is_disable == 1)
                                                    <button type="button" class="btn dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a data-url="{{ route('users.show', $user->id) }}" href="#"
                                                            class="dropdown-item" data-ajax-popup="true" data-size="lg"
                                                            data-title="{{ $user->name . __("'s Group") }}">
                                                            <i class="ti ti-eye"></i>
                                                            <span>{{ __('View Groups') }}</span>
                                                        </a>

                                                        @canany(['edit member', 'edit user'])
                                                            <a href="{{ route('users.edit', $user->id) }}"
                                                                class="dropdown-item"
                                                                data-bs-original-title="{{ __('Edit User') }}">
                                                                <i class="ti ti-pencil"></i>
                                                                <span>{{ __('Edit') }}</span>
                                                            </a>
                                                        @endcan

                                                        <a href="#!"
                                                            data-url="{{ route('company.reset', \Crypt::encrypt($user->id)) }}"
                                                            data-ajax-popup="true" data-size="md" class="dropdown-item"
                                                            data-bs-original-title="{{ __('Reset Password') }}"
                                                            data-title="{{ __('Reset Password') }}"
                                                            title="{{ __('Reset Password') }}">
                                                            <i class="ti ti-adjustments"></i>
                                                            <span> {{ __('Reset Password') }}</span>
                                                        </a>

                                                        @canany(['delete member', 'delete user'])
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['users.destroy', $user->id],
                                                                'id' => 'delete-form-' . $user->id,
                                                            ]) !!}
                                                            <a href="javascript:void(0)" class="dropdown-item bs-pass-para"
                                                                data-id="{{ $user['id'] }}"
                                                                data-confirm="{{ __('Are You Sure?') }}"
                                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                data-confirm-yes="delete-form-{{ $user->id }}"
                                                                title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                                data-bs-placement="top">
                                                                <i class="ti ti-archive"></i>
                                                                <span> {{ __('Delete') }}</span>
                                                            </a>
                                                            {!! Form::close() !!}
                                                        @endcan


                                                    </div>
                                                @else
                                                    <a href="javascript:void(0)" class="action-item"><i class="ti ti-lock"></i></a>
                                                @endif
                                            @endif


                                        </div>
                                    </div>
                                @endif


                            </div>
                            <div class="card-body full-card">
                                <div class="img-fluid rounded-circle card-avatar">
                                    <img src="{{ !empty($user->avatar)
                                        ? asset('storage/uploads/profile/' . $user->avatar)
                                        : asset('storage/uploads/profile/avatar.png') }}"
                                        class="img-user wid-80 round-img rounded-circle">
                                </div>
                                <h4 class="mt-3 text-primary">{{ $user->name }}</h4>

                                <small class="text-primary">{{ $user->email }}</small>
                                <p></p>
                                <div class="text-center" data-bs-toggle="tooltip" title="{{ __('Last Login') }}">
                                    {{ !empty($user->last_login_at) ? $user->last_login_at : '' }}
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        @if (\Auth::user()->type == 'super admin')
                                            <div class="">
                                                <a href="javascript:void(0)" class="text-sm btn btn-sm btn-light-primary"
                                                    data-url="{{ route('plan.upgrade', $user->id) }}" data-size="lg"
                                                    data-ajax-popup="true" data-title="{{ __('Upgrade Plan') }}">
                                                    {{ __('Upgrade Plan') }}
                                                </a>
                                            </div>
                                        @else
                                            <div class="p-2 px-3 rounded badge bg-primary">{{ ucfirst($user->type) }}
                                            </div>
                                        @endif
                                    </h6>

                                    <h6 class="mb-0">
                                        @if (\Auth::user()->type == 'super admin')
                                            <div class="">
                                                <a href="javascript:void(0)" data-url="{{ route('company.info', $user->id) }}"
                                                    data-size="lg" data-ajax-popup="true"
                                                    class="text-sm btn btn-sm btn-light-primary"
                                                    data-title="{{ __('Company Info') }}">{{ __('AdminHub') }}</a>
                                            </div>
                                        @endif
                                    </h6>

                                </div>

                            </div>

                        </div>
                    </div>
                @endforeach

                <div class="col-md-6 col-xxl-3 col-lg-4 col-sm-6 border-end border-bottom">
                    <div class="text-center bg-transparent border shadow-none card h-100 rounded-0">
                        <div class="pb-0 border-0 card-body">
                            <a href="javascript:void(0)" class="border-0 btn-addnew-project" data-ajax-popup="true" data-size="md"
                                data-title="Create New User" data-url="{{ route('users.create') }}">
                                <div class="bg-primary proj-add-icon">
                                    <i class="ti ti-plus"></i>
                                </div>
                                <h6 class="mt-4 mb-2">{{ __('New User') }}</h6>
                                <p class="text-center text-muted">{{ __('Click here to add New User') }}</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
