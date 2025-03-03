<div class="row">
    <div class="col-md-1"></div>
    <div class="col-lg-10">
        <div class="card shadow-none rounded-0 border">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('case_id', __('Case'), ['class' => 'form-label']) !!}
                            {!! Form::select('case_id', $cases, null, ['class' => 'form-control ' . ($errors->has('case_id') ? ' is-invalid' : ''), 'id' => 'case_id', 'required']) !!}
                            <div class="invalid-feedback">
                                {{ $errors->first('case_id') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 choose-files fw-semibold">
                        <label for="legal_document" class="upload__btn">
                            {{ Form::label('legal_document', __('Legal Document'), ['class' => 'col-form-label']) }}

                            <div class="bg-primary profile_update {{ $errors->has('legal_document') ? ' is-invalid' : '' }}"
                                style="max-width: 100% !important;">
                                <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                            </div>

                            <input type="file" name="legal_document" id="legal_document"
                                class="form-control file upload__inputfile" style="width: 0px !important"
                                value="{{ isset($library) ? $library->legal_document : '' }}"
                                onchange="image_upload_bar($('input[id=legal_document]').val().split('.')[1])" />
                            <div class="invalid-feedback">
                                {{ $errors->first('legal_document') }}
                            </div>
                            <p><span
                                    class="text-muted m-0 file-info">{{ __('Allowed file extension : .pdf,.doc,.docx') }}</span>
                                <span class="text-muted">({{ __('Max Size: 20 MB') }})</span>
                            </p>
                        </label>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 choose-files fw-semibold">
                        <label for="template" class="upload__btn">
                            {{ Form::label('template', __('Template'), ['class' => 'col-form-label']) }}

                            <div class="bg-primary profile_update {{ $errors->has('template') ? ' is-invalid' : '' }}"
                                style="max-width: 100% !important;">
                                <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                            </div>

                            <input type="file" name="template" id="template"
                                class="form-control file upload__inputfile" style="width: 0px !important"
                                onchange="image_upload_bar($('input[id=template]').val().split('.')[1])" />
                            <div class="invalid-feedback">
                                {{ $errors->first('template') }}
                            </div>
                            <p><span
                                    class="text-muted m-0 file-info">{{ __('Allowed file extension : .pdf,.doc,.docx') }}</span>
                                <span class="text-muted">({{ __('Max Size: 20 MB') }})</span>
                            </p>
                        </label>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 choose-files fw-semibold">
                        <label for="case_law" class="upload__btn">
                            {{ Form::label('case_law', __('Case Law'), ['class' => 'col-form-label']) }}

                            <div class="bg-primary profile_update {{ $errors->has('case_law') ? ' is-invalid' : '' }}"
                                style="max-width: 100% !important;">
                                <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                            </div>

                            <input type="file" name="case_law" id="case_law"
                                class="form-control file upload__inputfile" style="width: 0px !important"
                                onchange="image_upload_bar($('input[id=case_law]').val().split('.')[1])" />
                            <div class="invalid-feedback">
                                {{ $errors->first('case_law') }}
                            </div>
                            <p><span
                                    class="text-muted m-0 file-info">{{ __('Allowed file extension : .doc,.docx') }}</span>
                                <span class="text-muted">({{ __('Max Size: 20 MB') }})</span>
                            </p>
                        </label>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 choose-files fw-semibold">
                        <label for="statute_regulation" class="upload__btn">
                            {{ Form::label('statute_regulation', __('Statute and Regulation'), ['class' => 'col-form-label']) }}

                            <div class="bg-primary profile_update {{ $errors->has('statute_regulation') ? ' is-invalid' : '' }}"
                                style="max-width: 100% !important;">
                                <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                            </div>

                            <input type="file" name="statute_regulation" id="statute_regulation"
                                class="form-control file upload__inputfile" style="width: 0px !important"
                                onchange="image_upload_bar($('input[id=statute_regulation]').val().split('.')[1])" />
                            <div class="invalid-feedback">
                                {{ $errors->first('statute_regulation') }}
                            </div>
                            <p><span class="text-muted m-0 file-info">{{ __('Allowed file extension : .pdf') }}</span>
                                <span class="text-muted">({{ __('Max Size: 20 MB') }})</span>
                            </p>
                        </label>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 choose-files fw-semibold">
                        <label for="practice_guide" class="upload__btn">
                            {{ Form::label('practice_guide', __('Practice Guide'), ['class' => 'col-form-label']) }}

                            <div class="bg-primary profile_update {{ $errors->has('practice_guide') ? ' is-invalid' : '' }}"
                                style="max-width: 100% !important;">
                                <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                            </div>

                            <input type="file" name="practice_guide" id="practice_guide"
                                class="form-control file upload__inputfile" style="width: 0px !important"
                                onchange="image_upload_bar($('input[id=practice_guide]').val().split('.')[1])" />
                            <div class="invalid-feedback">
                                {{ $errors->first('practice_guide') }}
                            </div>
                            <p><span class="text-muted m-0 file-info">{{ __('Allowed file extension : .pdf') }}</span>
                                <span class="text-muted">({{ __('Max Size: 20 MB') }})</span>
                            </p>
                        </label>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 choose-files fw-semibold">
                        <label for="form_checklist" class="upload__btn">
                            {{ Form::label('form_checklist', __('Forms and Checklists'), ['class' => 'col-form-label']) }}

                            <div class="bg-primary profile_update {{ $errors->has('form_checklist') ? ' is-invalid' : '' }}"
                                style="max-width: 100% !important;">
                                <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                            </div>

                            <input type="file" name="form_checklist" id="form_checklist"
                                class="form-control file upload__inputfile" style="width: 0px !important"
                                onchange="image_upload_bar($('input[id=form_checklist]').val().split('.')[1])" />
                            <div class="invalid-feedback">
                                {{ $errors->first('form_checklist') }}
                            </div>
                            <p><span class="text-muted m-0 file-info">{{ __('Allowed file extension : .pdf') }}</span>
                                <span class="text-muted">({{ __('Max Size: 20 MB') }})</span>
                            </p>
                        </label>
                    </div>

                    <div class="col-md-4 col-sm-12 col-xs-12 choose-files fw-semibold">
                        <label for="article_publication" class="upload__btn">
                            {{ Form::label('article_publication', __('Article and Publication'), ['class' => 'col-form-label']) }}

                            <div class="bg-primary profile_update {{ $errors->has('article_publication') ? ' is-invalid' : '' }}"
                                style="max-width: 100% !important;">
                                <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                            </div>

                            <input type="file" name="article_publication" id="article_publication"
                                class="form-control file upload__inputfile" style="width: 0px !important"
                                onchange="image_upload_bar($('input[id=article_publication]').val().split('.')[1])" />
                            <div class="invalid-feedback">
                                {{ $errors->first('article_publication') }}
                            </div>
                            <p><span
                                    class="text-muted m-0 file-info">{{ __('Allowed file extension : .pdf,.doc,.docx') }}</span>
                                <span class="text-muted">({{ __('Max Size: 20 MB') }})</span>
                            </p>
                        </label>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 choose-files fw-semibold">
                        <label for="firm_policy_procedure" class="upload__btn">
                            {{ Form::label('firm_policy_procedure', __('Firm Policy and Procedure'), ['class' => 'col-form-label']) }}

                            <div class="bg-primary profile_update {{ $errors->has('firm_policy_procedure') ? ' is-invalid' : '' }}"
                                style="max-width: 100% !important;">
                                <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                            </div>

                            <input type="file" name="firm_policy_procedure" id="firm_policy_procedure"
                                class="form-control file upload__inputfile" style="width: 0px !important"
                                onchange="image_upload_bar($('input[id=firm_policy_procedure]').val().split('.')[1])" />
                            <div class="invalid-feedback">
                                {{ $errors->first('firm_policy_procedure') }}
                            </div>
                            <p><span class="text-muted m-0 file-info">{{ __('Allowed file extension : .pdf') }}</span>
                                <span class="text-muted">({{ __('Max Size: 20 MB') }})</span>
                            </p>
                        </label>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 choose-files fw-semibold ">
                        <label for="research_tool" class="upload__btn">
                            {{ Form::label('research_tool', __('Research Tool'), ['class' => 'col-form-label']) }}

                            <div class="bg-primary profile_update {{ $errors->has('research_tool') ? ' is-invalid' : '' }}"
                                style="max-width: 100% !important;">
                                <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                            </div>

                            <input type="file" name="research_tool" id="research_tool"
                                class="form-control file upload__inputfile" style="width: 0px !important"
                                onchange="image_upload_bar($('input[id=research_tool]').val().split('.')[1])" />
                            <div class="invalid-feedback">
                                {{ $errors->first('research_tool') }}
                            </div>
                            <p><span
                                    class="text-muted m-0 file-info">{{ __('Allowed file extension : ') }}{{ $file_validation['mimes'] }}</span>
                                <span class="text-muted">({{ __('Max Size: 20 MB') }})</span>
                            </p>
                        </label>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12 choose-files fw-semibold">
                        <label for="training_material" class="upload__btn">
                            {{ Form::label('training_material', __('Training Material'), ['class' => 'col-form-label']) }}

                            <div class="bg-primary profile_update {{ $errors->has('training_material') ? ' is-invalid' : '' }}"
                                style="max-width: 100% !important;">
                                <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                            </div>

                            <input type="file" name="training_material" id="training_material"
                                class="form-control file upload__inputfile" style="width: 0px !important"
                                onchange="image_upload_bar($('input[id=training_material]').val().split('.')[1])" />
                            <div class="invalid-feedback">
                                {{ $errors->first('training_material') }}
                            </div>
                            <p><span class="text-muted m-0 file-info">{{ __('Allowed file extension : .pdf') }}</span>
                                <span class="text-muted">({{ __('Max Size: 20 MB') }})</span>
                            </p>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-1"></div>
    <div class="col-lg-10">
        <div class="card shadow-none rounded-0 border ">
            <div class="card-body p-2">
                <div class="form-group col-12 d-flex justify-content-end col-form-label mb-0">
                    <a href="{{ route('libraries.index') }}"
                        class="btn btn-secondary btn-light ms-3">{{ __('Cancel') }}</a>
                    <input type="submit" value="{{ __('Save') }}" class="btn btn-primary ms-2">
                </div>
            </div>
        </div>
    </div>
</div>

@push('style')
    <style>
        .is-invalid {
            width: 100%;
            margin-top: 0.25rem;
            box-shadow: 0 0 0 0.2rem rgba(255, 58, 110, 0.4);
        }

        .choose-files .invalid-feedback {
            max-width: 100% !important;
            border-color: #ff3a6e;
            margin-bottom: -10px;
            margin-top: -23px;
            color: #ff3a6e;
        }
    </style>
@endpush
