@if (isset($caseLawByAreaCategories) && !empty($caseLawByAreaCategories))
    @foreach ($caseLawByAreaCategories as $caseLawByAreaCategory)
        <div class="col-md-6">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="page-header-title d-flex">
                        <h2>{{ $caseLawByAreaCategory->name }}</h2>
                        @if (Auth::user()->type == 'company')
                            <div class="col-sm-1">
                                <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
                                    <a href="javascript:void(0)" class="mx-1 mt-1 btn btn-sm btn-primary"
                                        data-ajax-popup="true" data-size="lg" data-title="Add Case Law By Area"
                                        data-url="{{ route('company.case-law-by-area.create', $caseLawByAreaCategory->id) }}"
                                        data-toggle="tooltip" title="{{ __('Create New Case Law By Area') }}"
                                        data-bs-original-title="{{ __('Create New Case Law By Area') }}"
                                        data-bs-placement="top" data-bs-toggle="tooltip">
                                        <i class="ti ti-plus"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @php
                $caseLawByAreas = App\Models\CaseLawByArea::where(
                    'case_law_by_area_category_id',
                    $caseLawByAreaCategory->id,
                );
                if (Auth::user()->type == 'company') {
                    $caseLawByAreas->where(function ($query) {
                        $query->where('created_by', Auth::user()->id)->orWhereNull('created_by');
                    });
                } else {
                    $caseLawByAreas->whereNull('created_by');
                }
                $caseLawByAreas = $caseLawByAreas->get();
            @endphp
            <ol id="libraries-data-list">
                @foreach ($caseLawByAreas as $caseLawByArea)
                    <li class="library-data-item">
                        <a href="{{ route('library.show', ['id' => $caseLawByArea->id, 'slug' => 'case-law-by-area']) }}"
                            target="_blank">
                            {{ $caseLawByArea->title }}
                        </a>
                    </li>
                @endforeach
            </ol>
        </div>
    @endforeach
@else
    @if (isset($groupedCaseLawByAreas) && !empty($groupedCaseLawByAreas))
        @foreach ($groupedCaseLawByAreas as $categoryName => $caseLawByAreas)
            <div class="col-md-6">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="page-header-title">
                            <h2>{{ $categoryName }}</h2>
                        </div>
                    </div>
                </div>
                @foreach ($caseLawByAreas as $caseLawByArea)
                    <div class="col-md-12">
                        <ol class="libraries-data-list">
                            <li class="library-data-item">
                                <a href="{{ route('library.show', ['id' => $caseLawByArea->id, 'slug' => 'case-law-by-area']) }}"
                                    target="_blank">
                                    {{ $caseLawByArea->title }}
                                </a>
                            </li>
                        </ol>
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif
@endif
