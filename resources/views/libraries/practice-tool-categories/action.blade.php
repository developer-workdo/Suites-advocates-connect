<span>
    @can('edit practice tool category')
        <div class="action-btn bg-light-secondary ms-2">
            <a href="javascript:void(0)" class="btn btn-sm d-inline-flex align-items-center"
                data-url="{{ route('practice-tool-categories.edit', $practiceToolCategory->id) }}" data-size="md" data-ajax-popup="true"
                data-title="{{ __('Edit practice tool Category') }}" title="{{ __('Edit practice tool Category') }}"
                data-bs-toggle="tooltip" data-bs-placement="top">
                <i class="ti ti-edit"></i>
            </a>
        </div>
    @endcan

    @can('delete practice tool category')
        <div class="action-btn bg-light-secondary ms-2">
            <a href="#" class="btn btn-sm d-inline-flex align-items-center bs-pass-para"
                data-confirm="{{ __('Are You Sure?') }}"
                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                data-confirm-yes="delete-form-{{ $practiceToolCategory->id }}" title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                data-bs-placement="top">
                <i class="ti ti-trash"></i>
            </a>
        </div>
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['practice-tool-categories.destroy', $practiceToolCategory->id],
            'id' => 'delete-form-' . $practiceToolCategory->id,
        ]) !!}
        {!! Form::close() !!}
    @endcan
</span>
