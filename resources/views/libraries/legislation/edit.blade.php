{{ Form::model($legislation, ['route' => ['legislations.update', $legislation->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        @include('libraries.legislation.form')
        <div class="modal-footer">
            <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
            <input type="submit" value="{{ __('Update') }}" class="btn btn-primary ms-2">
        </div>
    </div>
</div>
{{ Form::close() }}
