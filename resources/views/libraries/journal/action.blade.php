<span>
    @can('edit journal')
        <div class="action-btn bg-light-secondary ms-2">
            <a href="javascript:void(0)" class="btn btn-sm d-inline-flex align-items-center"
                data-url="{{ route('journals.edit', $journal->id) }}" data-size="lg" data-ajax-popup="true"
                data-title="{{ __('Edit Journal') }}" title="{{ __('Edit Journal') }}" data-bs-toggle="tooltip"
                data-bs-placement="top">
                <i class="ti ti-edit"></i>
            </a>
        </div>
    @endcan

    @can('delete journal')
        <div class="action-btn bg-light-secondary ms-2">
            <a href="javascript:void(0)" class="btn btn-sm d-inline-flex align-items-center bs-pass-para"
                data-confirm="{{ __('Are You Sure?') }}"
                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                data-confirm-yes="delete-form-{{ $journal->id }}" title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                data-bs-placement="top">
                <i class="ti ti-trash"></i>
            </a>
        </div>
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['journals.destroy', $journal->id],
            'id' => 'delete-form-' . $journal->id,
        ]) !!}
        {!! Form::close() !!}
    @endcan
</span>
