{{ Form::open(['route' => 'case-law-by-areas.store', 'method' => 'post', 'enctype' => "multipart/form-data"]) }}
<div class="modal-body">
    <div class="row ck-editor">
        @include('libraries.case-law-by-area.form')
        <div class="modal-footer">
            <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
            <input type="submit" value="{{ __('Save') }}" class="btn btn-primary ms-2">
        </div>
    </div>
</div>
{{ Form::close() }}
