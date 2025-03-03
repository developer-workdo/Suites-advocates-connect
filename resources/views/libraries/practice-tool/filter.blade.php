@foreach ($practiceToolCategories as $practiceToolCategory)
    <div class="col-md-6">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="page-header-title d-flex">
                    <h2>{{ $practiceToolCategory->name }}</h2>
                    @if (Auth::user()->type == 'company')
                        <div class="col-sm-1">
                            <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
                                <a href="javascript:void(0)" class="mx-1 btn btn-sm btn-primary" data-ajax-popup="true"
                                    data-size="lg" data-title="Add Practice Tool"
                                    data-url="{{ route('company.practice-tool.create', $practiceToolCategory->id) }}"
                                    data-toggle="tooltip" title="{{ __('Create New Practice Tool') }}"
                                    data-bs-original-title="{{ __('Create New Practice Tool') }}"
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
            $practiceTools = App\Models\PracticeTool::where('practice_tool_category_id', $practiceToolCategory->id);
            if (Auth::user()->type == 'company') {
                $practiceTools->where(function ($query) {
                    $query->where('created_by', Auth::user()->id)->orWhereNull('created_by');
                });
            } else {
                $practiceTools->whereNull('created_by');
            }
            $practiceTools = $practiceTools->get();
        @endphp
        <ol id="libraries-data-list">
            @foreach ($practiceTools as $practiceTool)
                <li class="library-data-item">
                    <a href="{{ route('library.show', ['id' => $practiceTool->id, 'slug' => 'practice-tool']) }}"
                        target="_blank">
                        {{ $practiceTool->title }}
                    </a>
                </li>
            @endforeach
        </ol>
    </div>
@endforeach
