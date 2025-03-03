@if (isset($recentDevelopmentCategoryId) && !empty($recentDevelopmentCategoryId))
    {{ Form::hidden('recent_development_category', $recentDevelopmentCategoryId, ['class' => 'form-control ']) }}
    {{ Form::hidden('is_company_specific', true) }}
@else
    <div class="col-12">
        <div class="form-group">
            {{ Form::label('recent_development_category', __('Practice Tool Category'), ['class' => 'col-form-label']) }}
            {{ Form::select('recent_development_category', $recentDevelopmentCategories, isset($recentDevelopment) ? $recentDevelopment->recent_development_category_id : null, ['class' => 'form-control multi-select', 'required' => 'required']) }}
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

<script>
    $(document).ready(function() {
        CKEDITOR.replace('description', {
            filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });
    });
</script>
