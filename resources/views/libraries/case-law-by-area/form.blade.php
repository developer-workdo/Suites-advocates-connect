@if (isset($caseLawByAreaCategoryId) && !empty($caseLawByAreaCategoryId))
    {{ Form::hidden('case_law_by_area_category', $caseLawByAreaCategoryId, ['class' => 'form-control ']) }}
    {{ Form::hidden('is_company_specific', true) }}
@else
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('case_law_by_area_category', __('Case Law By Area Category'), ['class' => 'col-form-label']) }}
            {{ Form::select('case_law_by_area_category', $caseLawByAreaCategories, isset($caseLawByArea) ? $caseLawByArea->case_law_by_area_category_id : null, ['class' => 'form-control multi-select', 'required' => 'required']) }}
        </div>
    </div>
@endif
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
        <input type="file" class="form-control" name="document" accept=".pdf,.docx,.doc,.rtf*">
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
