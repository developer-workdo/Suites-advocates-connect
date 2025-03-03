@extends('layouts.app')
@section('page-title', __('Libraries'))
@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Libraries') }}</li>
@endsection
@php
$sections = [
    ['name' => 'Case Law By Area', 'route' => 'case-law-by-areas.create'],
    ['name' => 'Legislation', 'route' => 'legislations.create'],
    ['name' => 'Journals', 'route' => 'journals.create'],
    ['name' => 'Research', 'route' => 'researches.create'],
    ['name' => 'Practice Tools', 'route' => 'practice-tools.create'],
    ['name' => 'Recent Developments', 'route' => 'recent-developments.create'],
];
@endphp
@section('content')

    <div class="p-0 row">
        <div class="col-md-12">
            <div class="shadow-none card">
                <div class="card-body">
                    <div class="header">
                        <nav>
                            {{-- <ul class="mb-3 nav nav-pills" id="pills-tab" role="tablist">
                                <li><a href="javascript:void(0)" role="tab" aria-selected="true" data-bs-toggle="pill"
                                        class="nav-link active" data-val="Case Law By Area">{{ __('Case Law By Area') }}</a>
                                </li>
                                <li><a href="javascript:void(0)" role="tab" aria-selected="true" data-bs-toggle="pill"
                                        class="nav-link" data-val="Legislation">{{ __('Legislation') }}</a></li>
                                <li><a href="javascript:void(0)" role="tab" aria-selected="true" data-bs-toggle="pill"
                                        class="nav-link" data-val="Journals">{{ __('Journals') }}</a></li>
                                <li><a href="javascript:void(0)" role="tab" aria-selected="true" data-bs-toggle="pill"
                                        class="nav-link" data-val="Research">{{ __('Research') }}</a></li>
                                <li><a href="javascript:void(0)" role="tab" aria-selected="true" data-bs-toggle="pill"
                                        class="nav-link" data-val="Practice Tools">{{ __('Practice Tools') }}</a></li>
                                <li><a href="javascript:void(0)" role="tab" aria-selected="true" data-bs-toggle="pill"
                                        class="nav-link" data-val="Recent Developments">{{ __('Recent Developments') }}</a>
                                </li>
                            </ul> --}}
                            <ul class="mb-3 nav nav-pills" id="pills-tab" role="tablist">
                                @foreach ($sections as $section)
                                <li class="d-flex">
                                    <a href="javascript:void(0)" role="tab" aria-selected="true" data-bs-toggle="pill" class="nav-link {{ $loop->first ? 'active' : '' }}" data-val="{{ $section['name'] }}">{{ __($section['name']) }}</a>
                                    @if (Auth::user()->type == 'super admin')
                                    <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
                                        <a href="javascript:void(0)" class="mx-1 btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg" data-title="Add {{ $section['name'] }}" data-url="{{ route($section['route']) }}" data-toggle="tooltip" title="{{ __('Create New ' . $section['name']) }}" data-bs-original-title="{{ __('Create New ' . $section['name']) }}" data-bs-placement="top" data-bs-toggle="tooltip">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                        </nav>
                    </div>
                    <div class="library-datas">
                        <div class="description-row">
                        </div>

                        <!-- Search Bar -->
                        <div class="mb-4 search-bar d-none input-group d-flex">
                            <div class="input-group-text"><i data-feather="search"></i></div>
                            <input type="search" id="query" class="form-control me-3"
                                placeholder="{{ __('Search...') }}" aria-label="Search">
                            <button id="searchButton" class="ml-2 btn btn-primary">{{ __('Search') }}</button>
                        </div>

                        <!-- Alphabet Filter -->
                        <div class="alphabet-filter">
                            <ul class="mb-3 nav nav-pills" id="pills-tab" role="tablist">
                                <li class="nav-item ">
                                    <a class="nav-link alphabet all d-none" id="pills-all-tab" data-bs-toggle="pill"
                                        href="#pills-all" role="tab" aria-controls="pills-all"
                                        aria-selected="true">{{ __('All') }}</a>
                                </li>
                                @foreach (range('A', 'Z') as $letter)
                                    <li class="nav-item">
                                        <a class="nav-link alphabet {{ $letter == 'A' ? 'active' : '' }}"
                                            id="pills-{{ $letter }}-tab" data-bs-toggle="pill"
                                            href="#pills-{{ $letter }}" role="tab"
                                            aria-controls="pills-{{ $letter }}"
                                            aria-selected="true">{{ $letter }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-12">
                            <div class="spinner-border" id="spinner" role="status">
                                <span class="sr-only">{{ __('Loading...') }}</span>
                            </div>
                        </div>
                        <div class="mt-4 row case-law-section filterData">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <style>
        .spinner-border {
            width: 3rem;
            height: 3rem;
            margin-left: 50%;
        }

        .page-header {
            display: none !important;
        }

        .library-datas {
            padding: 15px;
        }

        .image-div {
            margin-left: 9px;
        }

        .image-div img {
            width: 200px;
            height: 200px;
            object-fit: cover;
        }

        .description-row {
            padding: 5px;
        }

        .case-law-section {
            margin: auto;
        }

        .filterData {
            margin: 0px;
        }

        .header nav ul {
            list-style-type: none;
            display: flex;
            justify-content: space-around;
            padding: 0;
            margin: 0;
        }

        .header nav ul li {
            display: inline;
        }

        .header nav ul li a {
            text-decoration: none;
            color: #fff;
            padding: 10px;
            display: block;
        }

        .alphabet-filter {
            text-align: justify;
            margin: 20px 0;
            display: flex;
            justify-content: center
        }

        .alphabet-filter .alphabet {
            text-decoration: none;
            padding: 5px 15px;
            font-size: 28px;
        }

        .alphabet-filter .alphabet:hover {
            color: #ccc;
        }

        .alphabet-all {
            padding: 5px 15px !important;
            font-size: 28px !important;
        }

        .case-law-section h2 {}

        .case-law-section ol {
            padding-left: 20px;
        }

        .case-law-section ol li {
            line-height: 2;
        }

        .search-bar {
            text-align: center;
            max-width: 82%;
            margin: auto;
        }
    </style>
@endpush
@push('custom-script')
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.spinner-border').removeClass('d-none');
            var letter = $('.alphabet-filter .alphabet.active').text();
            var headerName = $('.header ul a.active').attr('data-val');

            filterData(letter, headerName);

            $(document).on('click', '#searchButton', function(e) {
                e.preventDefault();
                $('.spinner-border').removeClass('d-none');
                $('.alphabet').removeClass('active');
                var text = $('#query').val();
                var selectedTab = $('.nav-link.active').data('val');
                var route = '';
                if (text === '') {
                    $('#pills-A-tab').trigger('click');
                    $('#pills-A-tab').addClass('active').attr('aria-selected', 'true');
                } else {
                    if (selectedTab === 'Case Law By Area') {
                        route = "{{ route('libraries.case.law.by.area.search.data') }}";
                    } else if (selectedTab === 'Journals') {
                        route = "{{ route('libraries.journal.search.data') }}";
                    }
                    handleAjaxRequest(route, 'GET', {
                        text: text
                    });
                }
            });

            $(document).on('click', '.alphabet', function(e) {
                e.preventDefault();
                $('.spinner-border').removeClass('d-none');
                $('#query').val('');
                $('.alphabet').removeClass('active');
                var letter = $(this).text();
                var headerName = $('.header ul a.active').attr('data-val');
                filterData(letter, headerName);
            });

            $(document).on('click', '.header ul a', function(e) {
                e.preventDefault();
                $('.spinner-border').removeClass('d-none');
                $('#pills-all-tab').removeClass('active').addClass('d-none');
                $('.alphabet').eq(1).addClass('active');
                $('.alphabet-filter').removeClass('d-none');
                var headerName = $(this).attr('data-val');
                if (headerName === 'Journals' || headerName === 'Research' || headerName ===
                    'Practice Tools') {
                    $('#pills-all-tab').removeClass('d-none').addClass('active');
                    $('.alphabet').eq(1).removeClass('active');
                }
                if (headerName === 'Recent Developments') {
                    $('.alphabet-filter').addClass('d-none');
                    $('.alphabet').eq(1).removeClass('active');
                    $('.alphabet').eq(0).addClass('active');
                }
                var letter = $('.alphabet-filter .alphabet.active').text();
                filterData(letter, headerName);
            });

            function filterData(letter, headerName) {
                $('.spinner-border').removeClass('d-none');

                if (headerName === 'Case Law By Area' || headerName === 'Journals') {
                    $('.search-bar').removeClass('d-none');
                } else {
                    $('.search-bar').addClass('d-none');
                }
                handleAjaxRequest("{{ route('libraries.filter') }}", 'POST', {
                    letter: letter,
                    headerName: headerName
                });
            }

            function handleAjaxRequest(url, type, data, callback) {
                $.ajax({
                    url: url,
                    type: type,
                    data: data,
                    success: function(response) {
                        $('.filterData').empty().html(response.html);
                        var html = response.imageUrl ?
                            '<div class="row">' +
                            '<div class="col-md-2 image-div"></div>' +
                            '<div class="col-md-9 description"></div>' +
                            '</div>' :
                            '<div class="description"></div>';
                        $('.description-row').html(html);
                        $('.description').empty().html(response.description);
                        if (response.imageUrl) {
                            $('.image-div').html('<img src="' + response.imageUrl +
                                '" class="img-fluid">');
                        }
                        if (callback) callback(response);
                        $('.spinner-border').addClass('d-none');
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + ' - ' + error);
                        $('.spinner-border').removeClass('d-none');
                    }
                });
            }
        });
    </script>
@endpush
