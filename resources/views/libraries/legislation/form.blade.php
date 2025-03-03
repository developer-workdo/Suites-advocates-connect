<div class="col-12">
    <div class="form-group">
        {{ Form::label('title', __('Title'), ['class' => 'col-form-label']) }}
        {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => __('Enter Title'), 'required' => 'required']) }}
    </div>
</div>
<div class="col-12">
    <div class="form-group">
        {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
        {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'required' => 'required', 'id' => 'description']) }}
    </div>
</div>
<div class="col-12">
    <div class="form-group">
        {{ Form::label('document', __('Document'), ['class' => 'col-form-label']) }}
        <input type="file" class="form-control" name="document"  accept=".pdf,.docx,.doc,.rtf*">
        <span class="small">{{ __('NOTE: Allowed file extension : .pdf,.docx,.doc,.rtf') }}</span>
    </div>
</div>

<script>
    $(document).ready(function() {
        CKEDITOR.replace('description', {
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
    });
</script>
