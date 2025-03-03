<span>
    @can('edit case law by area category')
        <div class="action-btn bg-light-secondary ms-2">
            <a href="javascript:void(0)" class="btn btn-sm d-inline-flex align-items-center"
                data-url="{{ route('case-law-by-area-categories.edit', $caseLawByAreaCategory->id) }}" data-size="md"
                data-ajax-popup="true" data-title="{{ __('Edit Case Law By Area Category') }}"
                title="{{ __('Edit Case Law By Area Category') }}" data-bs-toggle="tooltip" data-bs-placement="top">
                <i class="ti ti-edit"></i>
            </a>
        </div>
    @endcan

    @can('delete case law by area category')
        <div class="action-btn bg-light-secondary ms-2">
            <a href="javascript:void(0)" class="btn btn-sm d-inline-flex align-items-center bs-pass-para"
                data-confirm="{{ __('Are You Sure?') }}"
                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                data-confirm-yes="delete-form-{{ $caseLawByAreaCategory->id }}" title="{{ __('Delete') }}"
                data-bs-toggle="tooltip" data-bs-placement="top">
                <i class="ti ti-trash"></i>
            </a>
        </div>
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['case-law-by-area-categories.destroy', $caseLawByAreaCategory->id],
            'id' => 'delete-form-' . $caseLawByAreaCategory->id,
        ]) !!}
        {!! Form::close() !!}
    @endcan
</span>
