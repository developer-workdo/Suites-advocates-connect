@foreach ($recentDevelopmentCategories as $recentDevelopment)
    <div class="col-md-6">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="page-header-title d-flex">
                    <h2>{{ $recentDevelopment->name }}</h2>
                    @if (Auth::user()->type == 'company')
                        <div class="col-sm-1">
                            <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
                                <a href="javascript:void(0)" class="mx-1 btn btn-sm btn-primary" data-ajax-popup="true"
                                    data-size="lg" data-title="Add Recent Development"
                                    data-url="{{ route('company.recent-development.create', $recentDevelopment->id) }}"
                                    data-toggle="tooltip" title="{{ __('Create New Recent Development') }}"
                                    data-bs-original-title="{{ __('Create New Recent Development') }}"
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
            $recentDevelopments = App\Models\RecentDevelopment::where(
                'recent_development_category_id',
                $recentDevelopment->id,
            );
            if (Auth::user()->type == 'company') {
                $recentDevelopments->where(function ($query) {
                    $query->where('created_by', Auth::user()->id)->orWhereNull('created_by');
                });
            } else {
                $recentDevelopments->whereNull('created_by');
            }
            $recentDevelopments = $recentDevelopments->get();
        @endphp
        <ol id="libraries-data-list">
            @foreach ($recentDevelopments as $recentDevelopment)
                <li class="library-data-item">
                    <a href="{{ route('library.show', ['id' => $recentDevelopment->id, 'slug' => 'recent-development']) }}"
                        target="_blank">
                        {{ $recentDevelopment->title }}
                    </a>
                </li>
            @endforeach
        </ol>
    </div>
@endforeach
