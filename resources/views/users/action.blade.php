<span>
    @if ($user->email_verified_at == null || $user->email_verified_at == '')
        <div class="action-btn bg-light-secondary ms-2">
            <a href="javascript:void(0)" class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para "
                data-confirm="{{ __('Are You Sure?') }}"
                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                data-confirm-yes="verify-form-{{ $user->id }}" title="{{ __('Verify Email') }}"
                data-bs-toggle="tooltip" data-bs-placement="top">
                <i class="ti ti-checks"></i>
            </a>
            {!! Form::open([
                'method' => 'POST',
                'route' => ['users.verify', $user->id],
                'id' => 'verify-form-' . $user->id,
            ]) !!}
            {!! Form::close() !!}
        </div>
    @else
        <div class="action-btn bg-primary ms-2">
            <a href="javascript:void(0)" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip"
                data-bs-placement="top" title="{{ __('verified Email') }}" data-size="md"
                data-title="{{ __('verified Email') }}">
                <i class="text-white ti ti-checks"></i>
            </a>
        </div>
    @endif

    @if (Auth::user()->type == 'super admin' ||
            (Auth::user()->super_admin_employee == 1 && in_array('manage user', $premission_arr)))
        <div class="action-btn bg-light-secondary ms-2">
            <a href="{{ route('users.detail', $user->id) }}" href="#"
                class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip"
                data-bs-placement="top" title="{{ __('View Details') }}" data-size="md"
                data-title="{{ __('View Details') }}">
                <i class="ti ti-eye "></i>
            </a>
        </div>

        <div class="action-btn bg-light-secondary ms-2">

            <a href="{{ route('login.with.admin', $user->id) }}"
                class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip"
                data-bs-placement="top" title="{{ __('Login') }}">
                <i class="py-1 ti ti-replace"></i>

            </a>
        </div>
    @endif

    @if (Auth::user()->type == 'super admin' ||
            (Auth::user()->super_admin_employee == 1 && in_array('manage user', $premission_arr)))
        <div class="action-btn bg-light-secondary ms-2">
            <a href="javascript:void(0)" data-url="{{ route('plan.upgrade', $user->id) }}"
                class="mx-3 btn btn-sm d-inline-flex align-items-center " data-tooltip="Edit" data-ajax-popup="true"
                data-title="{{ __('Upgrade Plan') }}" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Upgrade Plan') }}">

                <i class="ti ti-trophy "></i>

            </a>
        </div>
    @endif

    @if (Auth::user()->type == 'company')
        <div class="action-btn bg-light-secondary ms-2">
            <a data-url="{{ route('users.show', $user->id) }}" href="#"
                class="mx-3 btn btn-sm d-inline-flex align-items-center" data-ajax-popup="true" data-bs-toggle="tooltip"
                data-bs-placement="top" title="{{ __('View Groups') }}" data-size="md"
                data-title="{{ $user->name . __("'s Group") }}"><i class="ti ti-eye "></i>

            </a>
        </div>
    @endif

    <div class="action-btn bg-light-secondary ms-2">
        <a href="javascript:void(0)" data-url="{{ route('company.reset', \Crypt::encrypt($user->id)) }}"
            class="mx-3 btn btn-sm d-inline-flex align-items-center " data-tooltip="Edit" data-ajax-popup="true"
            data-title="{{ __('Reset Password') }}" data-bs-toggle="tooltip" data-bs-placement="top"
            title="{{ __('Reset Password') }}">

            <i class="ti ti-key "></i>

        </a>
    </div>

    @if (Auth::user()->can('edit member') ||
            Auth::user()->can('edit user') ||
            (Auth::user()->super_admin_employee == 1 && in_array('edit user', $premission_arr)))
        <div class="action-btn bg-light-secondary ms-2">
            <a href="{{ route('users.edit', $user->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}">

                <i class="ti ti-edit "></i>

            </a>
        </div>
    @endif

    @if (Auth::user()->can('delete member') ||
            Auth::user()->can('delete user') ||
            (Auth::user()->super_admin_employee == 1 && in_array('delete user', $premission_arr)))
        <div class="action-btn bg-light-secondary ms-2">
            <a href="javascript:void(0)" class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para "
                data-confirm="{{ __('Are You Sure?') }}"
                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                data-confirm-yes="delete-form-{{ $user->id }}" title="{{ __('Delete') }}"
                data-bs-toggle="tooltip" data-bs-placement="top">
                <i class="ti ti-trash"></i>
            </a>
        </div>
    @endif

    {!! Form::open([
        'method' => 'DELETE',
        'route' => ['users.destroy', $user->id],
        'id' => 'delete-form-' . $user->id,
    ]) !!}
    {!! Form::close() !!}
</span>
