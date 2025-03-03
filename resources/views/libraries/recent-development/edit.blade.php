{{ Form::model($recentDevelopment, ['route' => ['recent-developments.update', $recentDevelopment->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        @include('libraries.recent-development.form')
        <div class="modal-footer">
            <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
            <input type="submit" value="{{ __('Update') }}" class="btn btn-primary ms-2">
        </div>
    </div>
</div>
{{ Form::close() }}
