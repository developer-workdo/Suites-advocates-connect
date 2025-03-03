<span>
    @can('edit recent development category')
        <div class="action-btn bg-light-secondary ms-2">
            <a href="javascript:void(0)" class="btn btn-sm d-inline-flex align-items-center"
                data-url="{{ route('recent-development-categories.edit', $recentDevelopmentCategory->id) }}" data-size="md"
                data-ajax-popup="true" data-title="{{ __('Edit Recent Development Category') }}"
                title="{{ __('Edit Recent Development Category') }}" data-bs-toggle="tooltip" data-bs-placement="top">
                <i class="ti ti-edit"></i>
            </a>
        </div>
    @endcan

    @can('delete recent development category')
        <div class="action-btn bg-light-secondary ms-2">
            <a href="#" class="btn btn-sm d-inline-flex align-items-center bs-pass-para"
                data-confirm="{{ __('Are You Sure?') }}"
                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                data-confirm-yes="delete-form-{{ $recentDevelopmentCategory->id }}" title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                data-bs-placement="top">
                <i class="ti ti-trash"></i>
            </a>
        </div>
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['recent-development-categories.destroy', $recentDevelopmentCategory->id],
            'id' => 'delete-form-' . $recentDevelopmentCategory->id,
        ]) !!}
        {!! Form::close() !!}
    @endcan
</span>
