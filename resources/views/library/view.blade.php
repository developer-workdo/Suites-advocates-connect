@extends('layouts.app')
@section('page-title', __('View Library'))
@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('View Library') }}</li>
@endsection
@section('content')
    <div class="card shadow-none rounded-0 border">
        <div class="row col-lg-12 col-xl-12">
            <div class="card-body">
                @if (isset($library))
                    <div class=" row">
                        <div class=" row col-6 p-5 py-2">
                            <h5>{{ __('Case:') }}</h5>
                            <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Title') }}:</span></dt>
                            <dd class="col-md-4">
                                <span class="text-md">{{ $library->case->title }}</span>
                            </dd>
                            <dd class="col-md-4">
                                {{ ' ' }}
                            </dd>
                            <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Case No') }}:</span></dt>
                            <dd class="col-md-8">
                                <span class="text-md">{{ $library->case->case_number }}</span>
                            </dd>
                        </div>
                        <div class=" row col-6 p-5 py-2">
                            <h5>{{ __('Legal Document:') }}</h5>
                            @if (pathinfo($library->legal_document, PATHINFO_EXTENSION) === 'pdf')
                                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('View') }}:</span>
                                </dt>
                                <dd class="col-md-4">
                                    <span class="text-md">
                                        <a href="{{ \App\Models\Utility::get_file($library->legal_document) }}"
                                            target="_blank">{{ __('Click here') }}
                                        </a>
                                    </span>
                                </dd>
                                <dd class="col-md-4">
                                    {{ ' ' }}
                                </dd>
                            @endif
                            <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Download') }}:</span>
                            </dt>
                            <dd class="col-md-8">
                                <span class="text-md">
                                    <a href="{{ \App\Models\Utility::get_file($library->legal_document) }}" target="_blank"
                                        download="">{{ __('Click here') }}
                                    </a>
                                </span>
                            </dd>
                        </div>
                        <div class=" row col-6 p-5 py-2">
                            <hr>
                            <h5>{{ __('Template:') }}</h5>
                            @if (pathinfo($library->template, PATHINFO_EXTENSION) === 'pdf')
                                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('View') }}:</span>
                                </dt>
                                <dd class="col-md-4">
                                    <span class="text-md">
                                        <a href="{{ \App\Models\Utility::get_file($library->template) }}"
                                            target="_blank">{{ __('Click here') }}
                                        </a>
                                    </span>
                                </dd>
                                <dd class="col-md-4">
                                    {{ ' ' }}
                                </dd>
                            @endif
                            <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Download') }}:</span>
                            </dt>
                            <dd class="col-md-8">
                                <span class="text-md">
                                    <a href="{{ \App\Models\Utility::get_file($library->template) }}" target="_blank"
                                        download="">{{ __('Click here') }}
                                    </a>
                                </span>
                            </dd>
                        </div>
                        <div class=" row col-6 p-5 py-2">
                            <hr>
                            <h5>{{ __('Case Law:') }}</h5>
                            <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Download') }}:</span>
                            </dt>
                            <dd class="col-md-8">
                                <span class="text-md">
                                    <a href="{{ \App\Models\Utility::get_file($library->case_law) }}" target="_blank"
                                        download="">{{ __('Click here') }}
                                    </a>
                                </span>
                            </dd>
                        </div>
                        <div class=" row col-6 p-5 py-2">
                            <hr>
                            <h5>{{ __('Statute and Regulation:') }}</h5>
                            <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('View') }}:</span></dt>
                            <dd class="col-md-4">
                                <span class="text-md">
                                    <a href="{{ \App\Models\Utility::get_file($library->statute_regulation) }}"
                                        target="_blank">{{ __('Click here') }}
                                    </a>
                                </span>
                            </dd>
                            <dd class="col-md-4">
                                {{ ' ' }}
                            </dd>
                            <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Download') }}:</span>
                            </dt>
                            <dd class="col-md-8">
                                <span class="text-md">
                                    <a href="{{ \App\Models\Utility::get_file($library->statute_regulation) }}"
                                        target="_blank" download="">{{ __('Click here') }}
                                    </a>
                                </span>
                            </dd>
                        </div>
                        <div class=" row col-6 p-5 py-2">
                            <hr>
                            <h5>{{ __('Practice Guide:') }}</h5>
                            <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('View') }}:</span></dt>
                            <dd class="col-md-4">
                                <span class="text-md">
                                    <a href="{{ \App\Models\Utility::get_file($library->practice_guide) }}"
                                        target="_blank">{{ __('Click here') }}
                                    </a>
                                </span>
                            </dd>
                            <dd class="col-md-4">
                                {{ ' ' }}
                            </dd>
                            <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Download') }}:</span>
                            </dt>
                            <dd class="col-md-8">
                                <span class="text-md">
                                    <a href="{{ \App\Models\Utility::get_file($library->practice_guide) }}" target="_blank"
                                        download="">{{ __('Click here') }}
                                    </a>
                                </span>
                            </dd>
                        </div>
                        <div class=" row col-6 p-5 py-2">
                            <hr>
                            <h5>{{ __('Forms and Checklists:') }}</h5>
                            <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('View') }}:</span></dt>
                            <dd class="col-md-4">
                                <span class="text-md">
                                    <a href="{{ \App\Models\Utility::get_file($library->form_checklist) }}"
                                        target="_blank">{{ __('Click here') }}
                                    </a>
                                </span>
                            </dd>
                            <dd class="col-md-4">
                                {{ ' ' }}
                            </dd>
                            <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Download') }}:</span>
                            </dt>
                            <dd class="col-md-8">
                                <span class="text-md">
                                    <a href="{{ \App\Models\Utility::get_file($library->form_checklist) }}" target="_blank"
                                        download="">{{ __('Click here') }}
                                    </a>
                                </span>
                            </dd>
                        </div>
                        <div class=" row col-6 p-5 py-2">
                            <hr>
                            <h5>{{ __('Article and Publication:') }}</h5>
                            @if (pathinfo($library->article_publication, PATHINFO_EXTENSION) === 'pdf')
                                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('View') }}:</span></dt>
                                <dd class="col-md-4">
                                    <span class="text-md">
                                        <a href="{{ \App\Models\Utility::get_file($library->article_publication) }}"
                                            target="_blank">{{ __('Click here') }}
                                        </a>
                                    </span>
                                </dd>
                                <dd class="col-md-4">
                                    {{ ' ' }}
                                </dd>
                            @endif
                            <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Download') }}:</span>
                            </dt>
                            <dd class="col-md-8">
                                <span class="text-md">
                                    <a href="{{ \App\Models\Utility::get_file($library->article_publication) }}"
                                        target="_blank" download="">{{ __('Click here') }}
                                    </a>
                                </span>
                            </dd>
                        </div>
                        <div class=" row col-6 p-5 py-2">
                            <hr>
                            <h5>{{ __('Firm Policy and Procedure:') }}</h5>
                            <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('View') }}:</span>
                            </dt>
                            <dd class="col-md-4">
                                <span class="text-md">
                                    <a href="{{ \App\Models\Utility::get_file($library->firm_policy_procedure) }}"
                                        target="_blank">{{ __('Click here') }}
                                    </a>
                                </span>
                            </dd>
                            <dd class="col-md-4">
                                {{ ' ' }}
                            </dd>
                            <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Download') }}:</span>
                            </dt>
                            <dd class="col-md-8">
                                <span class="text-md">
                                    <a href="{{ \App\Models\Utility::get_file($library->firm_policy_procedure) }}"
                                        target="_blank" download="">{{ __('Click here') }}
                                    </a>
                                </span>
                            </dd>
                        </div>
                        <div class=" row col-6 p-5 py-2">
                            <hr>
                            <h5>{{ __('Research Tool:') }}</h5>
                            @if (in_array(pathinfo($library->research_tool, PATHINFO_EXTENSION), ['pdf', 'jpg', 'jpeg', 'png', 'gif']))
                                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('View') }}:</span></dt>
                                <dd class="col-md-4">
                                    <span class="text-md">
                                        <a href="{{ \App\Models\Utility::get_file($library->research_tool) }}"
                                            target="_blank">{{ __('Click here') }}
                                        </a>
                                    </span>
                                </dd>
                                <dd class="col-md-4">
                                    {{ ' ' }}
                                </dd>
                            @endif
                            <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Download') }}:</span>
                            </dt>
                            <dd class="col-md-8">
                                <span class="text-md">
                                    <a href="{{ \App\Models\Utility::get_file($library->research_tool) }}"
                                        target="_blank" download="">{{ __('Click here') }}
                                    </a>
                                </span>
                            </dd>
                        </div>
                        <div class=" row col-6 p-5 py-2">
                            <hr>
                            <h5>{{ __('Training Material:') }}</h5>
                            <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('View') }}:</span>
                            </dt>
                            <dd class="col-md-4">
                                <span class="text-md">
                                    <a href="{{ \App\Models\Utility::get_file($library->training_material) }}"
                                        target="_blank">{{ __('Click here') }}
                                    </a>
                                </span>
                            </dd>
                            <dd class="col-md-4">
                                {{ ' ' }}
                            </dd>
                            <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Download') }}:</span>
                            </dt>
                            <dd class="col-md-8">
                                <span class="text-md">
                                    <a href="{{ \App\Models\Utility::get_file($library->training_material) }}"
                                        target="_blank" download="">{{ __('Click here') }}
                                    </a>
                                </span>
                            </dd>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
