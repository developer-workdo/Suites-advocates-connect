<span>
    @can('edit practice tool')
        <div class="action-btn bg-light-secondary ms-2">
            <a href="javascript:void(0)" class="btn btn-sm d-inline-flex align-items-center"
                data-url="{{ route('practice-tools.edit', $practiceTool->id) }}" data-size="lg" data-ajax-popup="true"
                data-title="{{ __('Edit Practice Tool') }}" title="{{ __('Edit Practice Tool') }}" data-bs-toggle="tooltip"
                data-bs-placement="top">
                <i class="ti ti-edit"></i>
            </a>
        </div>
    @endcan

    @can('delete practice tool')
        <div class="action-btn bg-light-secondary ms-2">
            <a href="#" class="btn btn-sm d-inline-flex align-items-center bs-pass-para"
                data-confirm="{{ __('Are You Sure?') }}"
                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                data-confirm-yes="delete-form-{{ $practiceTool->id }}" title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                data-bs-placement="top">
                <i class="ti ti-trash"></i>
            </a>
        </div>
        {!! Form::open([
            'method' => 'DELETE',
            'route' => ['practice-tools.destroy', $practiceTool->id],
            'id' => 'delete-form-' . $practiceTool->id,
        ]) !!}
        {!! Form::close() !!}
    @endcan
</span>
