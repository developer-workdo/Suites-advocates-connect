@extends('layouts.app')
@section('page-title')
    {{ __('Settings') }}
@endsection

@php
    $companyLogo = App\Models\Utility::getValByName('company_logo_dark');
@endphp

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Settings') }}</li>
@endsection

@section('content')
    <style>
        .list-group-item.active {
            border: none !important;
        }
    </style>

    <div class="row ">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row g-0">
                <div class="col-xl-3 border-end border-bottom">
                    @include('libraries.settings.setting-sidebar')
                </div>
                <div class="col-xl-9" data-bs-spy="scroll" data-bs-target="#useradd-sidenav" data-bs-offset="0" tabindex="0">
                    <div class="shadow-none card rounded-0 border-bottom" id="useradd-1">
                        <div class="card-header">
                            <h5>{{ __('Library Settings') }}</h5>
                        </div>
                        {{ Form::model($settings, ['route' => 'library.setting.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                        @csrf
                        <div class="card-body libraryDiv">
                            @foreach ($librarySettings as $librarySettingKey => $librarySettingValue)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="my-2 form-check form-switch custom-switch-v1">
                                            <input type="checkbox" name="is_{{ $librarySettingKey }}_enabled"
                                                class="form-check-input input-primary" id="{{ $librarySettingKey }}"
                                                {{ isset($settings["is_{$librarySettingKey}_enabled"]) && $settings["is_{$librarySettingKey}_enabled"] == 'on' ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="{{ $librarySettingKey }}">{{ $librarySettingValue }}</label>
                                        </div>
                                    </div>
                                    @if (isset($librarySettingValue) && $librarySettingValue == 'Recent Developments')
                                        <div class="col-md-12 {{ $librarySettingKey }}_setting_tag">
                                            <div class="mt-4 logo-content" style="margin-left: 50px;">
                                                <a href="{{ asset('storage/' . ($settings['recent_development_image'] ?? '/uploads/profile/avatar.png')) }}"
                                                    target="_blank">
                                                    <img id="recent-developments-image" alt="your image"
                                                        src="{{ asset('storage/' . ($settings['recent_development_image'] ?? '/uploads/profile/avatar.png')) . '?' . time() }}"
                                                        width="200px" class="big-logo img_setting">
                                                </a>
                                            </div>
                                            <div class="mt-5 choose-files">
                                                <label for="recent-development-image">
                                                    <div class="m-auto bg-primary recent-development-image_update">
                                                        <i class="px-1 ti ti-upload"></i>{{ __('Choose file here') }}
                                                    </div>
                                                    <input type="file" name="recent_development_image"
                                                        id="recent-development-image" class="form-control file"
                                                        data-filename="recent-development-image_update"
                                                        onchange="document.getElementById('recent-developments-image').src = window.URL.createObjectURL(this.files[0])"
                                                        accept="image/*">
                                                    <input type="hidden" name="current_recent_development_image"
                                                        value="{{ $settings['recent_development_image'] ?? '' }}">
                                                </label>
                                            </div>
                                            @error('recent_development_image')
                                                <div class="row">
                                                    <span class="invalid-logo" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                </div>
                                            @enderror
                                        </div>
                                    @endif
                                    <div class="col-md-12 {{ $librarySettingKey }}_setting_tag">
                                        <div class="form-group">
                                            {{ Form::label($librarySettingKey . '_description', __($librarySettingValue . ' Description'), ['class' => 'form-label']) }}
                                            *
                                            {!! Form::textarea(
                                                $librarySettingKey . '_description',
                                                isset($settings[$librarySettingKey . '_description']) ? $settings[$librarySettingKey . '_description'] : null,
                                                [
                                                    'class' => 'form-control library-editor',
                                                    'placeholder' => __('Enter short description'),
                                                    'required' => 'required',
                                                ],
                                            ) !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="pb-0 card-footer text-end">
                            <div class="form-group">
                                <input class="btn btn-print-invoice btn-primary m-r-10" type="submit"
                                    value="{{ __('Save Changes') }}">
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                    <!-- [ Main Content ] end -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-script')
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.library-editor').each(function() {
                CKEDITOR.replace(this.id, {
                    filebrowserUploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
                    filebrowserUploadMethod: 'form'
                });
            });
            $(document).on('change', '.libraryDiv input[type="checkbox"]', function() {
                var $this = $(this);
                var settingKey = $this.attr('id');
                showLibraryTab($this, settingKey);
            });
            $('.libraryDiv input[type="checkbox"]').each(function() {
                var $this = $(this);
                var settingKey = $this.attr('id');
                showLibraryTab($this, settingKey);
            });

            function showLibraryTab($this, settingKey) {
                if ($this.is(':checked')) {
                    $('.' + settingKey + '_setting_tag').removeClass('d-none');
                } else {
                    $('.' + settingKey + '_setting_tag').addClass('d-none');
                }
            }
        });
    </script>
@endpush
