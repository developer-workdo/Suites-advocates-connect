{{ Form::model($research, ['route' => ['researches.update', $research->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        @include('libraries.research.form')
        <div class="modal-footer">
            <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
            <input type="submit" value="{{ __('Update') }}" class="btn btn-primary ms-2">
        </div>
    </div>
</div>
{{ Form::close() }}
