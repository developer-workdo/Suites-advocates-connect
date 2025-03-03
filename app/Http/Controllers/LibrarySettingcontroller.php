<?php

namespace App\Http\Controllers;

use App\Models\LibrarySetting;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LibrarySettingcontroller extends Controller
{
    public function saveLibrarySettings(Request $request)
    {
        if (Auth::user()->can('manage library settings')  && (Auth::user()->type == 'super admin' || Auth::user()->type == 'company')) {
            $rules = [
                'case_law_by_area_description'       => 'required_if:is_case_law_by_area_enabled,on',
                'legislation_description'            => 'required_if:is_legislation_enabled,on',
                'journals_description'               => 'required_if:is_journals_enabled,on',
                'research_description'               => 'required_if:is_research_enabled,on',
                'practice_tools_description'         => 'required_if:is_practice_tools_enabled,on',
                'recent_developments_description'    => 'required_if:is_recent_developments_enabled,on',
            ];
            if ($request->hasFile('recent_development_image')) {
                $rules = ['recent_development_image' => 'required_if:is_recent_developments_enabled,on|file|mimes:jpeg,jpg,png|max:2048'];
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $post['is_case_law_by_area_enabled']         = $request->is_case_law_by_area_enabled && $request->is_case_law_by_area_enabled == 'on' ? 'on' : 'off';
            $post['case_law_by_area_description']        = $request->case_law_by_area_description;
            $post['is_legislation_enabled']              = $request->is_legislation_enabled && $request->is_legislation_enabled == 'on' ? 'on' : 'off';
            $post['legislation_description']             = $request->legislation_description;
            $post['is_journals_enabled']                 = $request->is_journals_enabled && $request->is_journals_enabled == 'on' ? 'on' : 'off';
            $post['journals_description']                = $request->journals_description;
            $post['is_research_enabled']                 = $request->is_research_enabled && $request->is_research_enabled == 'on' ? 'on' : 'off';
            $post['research_description']                = $request->research_description;
            $post['is_practice_tools_enabled']           = $request->is_practice_tools_enabled && $request->is_practice_tools_enabled == 'on' ? 'on' : 'off';
            $post['practice_tools_description']          = $request->practice_tools_description;
            $post['is_recent_developments_enabled']      = $request->is_recent_developments_enabled && $request->is_recent_developments_enabled == 'on' ? 'on' : 'off';
            $post['recent_developments_description']     = $request->recent_developments_description;
            if ($request->is_recent_developments_enabled == 'on' && $request->hasFile('recent_development_image')) {
                $logoName = Auth::user()->id . '-recent-developments-image.png';
                $path     = Utility::upload_file($request, 'recent_development_image', $logoName, 'uploads/logo/', []);
                if ($path['flag'] == 1) {
                    $recent_development_image = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
                $post['recent_development_image'] = $recent_development_image;
            }
            foreach ($post as $key => $value) {
                LibrarySetting::updateOrCreate(
                    [
                        'name' => $key,
                        'created_by' => Auth::user()->creatorId()
                    ],
                    [
                        'value' => $value
                    ]
                );
            }
            return redirect()->back()->with('success', __('Library settings updated successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function loadLibrarySetting($type = 'library-settings')
    {
        $config = [
            'case-law-by-area-categories'   => ['Add Case Law By Area Category', 'case-law-by-area-categories.create', 'CaseLawByAreaCategoryDataTable'],
            'case-law-by-areas'             => ['Add Case Law By Area', 'case-law-by-areas.create', 'CaseLawByAreaDataTable'],
            'legislations'                  => ['Add Legislation', 'legislations.create', 'LegislationDataTable'],
            'journals'                      => ['Add Journal', 'journals.create', 'JournalDataTable'],
            'researches'                    => ['Add Research', 'researches.create', 'ResearchDataTable'],
            'practice-tool-categories'      => ['Add Practice Tool Category', 'practice-tool-categories.create', 'PracticeToolCategoryDataTable'],
            'practice-tools'                => ['Add Practice Tool', 'practice-tools.create', 'PracticeToolDataTable'],
            'recent-development-categories' => ['Add Recent Development Category', 'recent-development-categories.create', 'RecentDevelopmentCategoryDataTable'],
            'recent-developments'           => ['Add Recent Development', 'recent-developments.create', 'RecentDevelopmentDataTable'],
        ];

        if ($type == 'library-settings') {
            $settings = LibrarySetting::where('created_by', Auth::user()->id)->pluck('value', 'name')->all();
            $librarySettings = [
                'case_law_by_area'    => 'Case Law By Area',
                'legislation'         => 'Legislation',
                'journals'            => 'Journals',
                'research'            => 'Research',
                'practice_tools'      => 'Practice Tools',
                'recent_developments' => 'Recent Developments',
            ];
            $type = 'library-settings';
            return view('libraries.settings.admin', compact('settings', 'librarySettings', 'type'));
        }

        if (array_key_exists($type, $config)) {
            [$title, $route, $dataTableClass] = $config[$type];
            $dataTableClass = 'App\\DataTables\\' . $dataTableClass;

            return (new $dataTableClass)->render('libraries.settings.datatable', [
                'type'      => $type,
                'title'     => $title,
                'createUrl' => route($route)
            ]);
        }

        abort(404, 'Invalid type.');
    }
}
