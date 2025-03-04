@php
    $file_validation = App\Models\Utility::file_upload_validation();
@endphp
@extends('layouts.app')
@section('page-title', __('Add Library'))
@section('breadcrumb')
    <li class="breadcrumb-item">{{ __(' Add Library') }}</li>
@endsection
@section('content')
    {{ Form::open(['route' => 'library.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
    @include('library.form')
    {{ Form::close() }}
@endsection


@push('custom-script')
    <script src="{{ asset('public/assets/js/jquery-ui.js') }}"></script>
    <script>
        jQuery(document).ready(function() {
            ImgUpload();
        });

        function ImgUpload() {
            var imgWrap = "";
            var imgArray = [];

            $('.upload__inputfile').each(function() {
                $(this).on('change', function(e) {
                    imgWrap = $('.upload__box').find('.upload__img-wrap');

                    var maxLength = $(this).attr('data-max_length');

                    var files = e.target.files;
                    var filesArr = Array.prototype.slice.call(files);
                    var iterator = 0;
                    filesArr.forEach(function(f, index) {

                        // if (!f.type.match('image.*')) {
                        //   return;
                        // }

                        if (imgArray.length > maxLength) {
                            return false
                        } else {
                            var len = 0;
                            for (var i = 0; i < imgArray.length; i++) {
                                if (imgArray[i] !== undefined) {
                                    len++;
                                }
                            }
                            if (len > maxLength) {
                                return false;
                            } else {

                                imgArray.push(f);

                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    if (!f.type.match('image.*')) {

                                        var html =
                                            "<div class='upload__img-box'><div style='background-image: url(" +
                                            e.target.result + ")' data-number='" + $(
                                                ".upload__img-close").length + "' data-file='" +
                                            f.name + "' class='img-bg'><p>" + f.name +
                                            "</p><div class='upload__img-close'></div></div></div>";
                                        imgWrap.append(html);
                                        iterator++;
                                    } else {
                                        var html =
                                            "<div class='upload__img-box'><div style='background-image: url(" +
                                            e.target.result + ")' data-number='" + $(
                                                ".upload__img-close").length + "' data-file='" +
                                            f.name + "' class='img-bg'><p>" + f.name +
                                            "</p><div class='upload__img-close'></div></div></div>";
                                        imgWrap.append(html);
                                        iterator++;
                                    }

                                }
                                reader.readAsDataURL(f);
                            }
                        }
                    });
                });
            });

            $('body').on('click', ".upload__img-close", function(e) {
                var file = $(this).parent().data("file");
                for (var i = 0; i < imgArray.length; i++) {
                    if (imgArray[i].name === file) {
                        imgArray.splice(i, 1);
                        break;
                    }
                }
                $(this).parent().parent().remove();
            });
        }
    </script>
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
    <script>
        $('.summernote').summernote({
            dialogsInBody: !0,
            minHeight: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                ['list', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'unlink']],
            ]
        });
    </script>
@endpush
