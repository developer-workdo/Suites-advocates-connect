<span>
    @can('edit legislation')
        <div class="action-btn bg-light-secondary ms-2">
            <a href="javascript:void(0)" class="btn btn-sm d-inline-flex align-items-center"
                data-url="{{ route('legislations.edit', $legislation->id) }}" data-size="lg" data-ajax-popup="true"
                data-title="{{ __('Edit Legislation') }}" title="{{ __('Edit Legislation') }}" data-bs-toggle="tooltip"
                data-bs-placement="top">
                <i class="ti ti-edit"></i>
            </a>
        </div>
    @endcan

    @can('delete legislation')
        <div class="action-btn bg-light-secondary ms-2">
            <a href="javascript:void(0)" class="btn btn-sm d-inline-flex align-items-center bs-pass-para"
                data-confirm="{{ __('Are You Sure?') }}"
                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                data-confirm-yes="delete-form-{{ $legislation->id }}" title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                data-bs-placement="top">
                <i class="ti ti-trash"></i>
            </a>
        </div>
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['legislations.destroy', $legislation->id],
            'id' => 'delete-form-' . $legislation->id,
        ]) !!}
        {!! Form::close() !!}
    @endcan
</span>
