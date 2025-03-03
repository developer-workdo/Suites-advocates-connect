<?php

namespace App\Http\Controllers;

use App\Models\CaseLawByArea;
use App\Models\Journal;
use App\Models\Legislation;
use App\Models\LibrarySetting;
use App\Models\PracticeTool;
use App\Models\RecentDevelopment;
use App\Models\Research;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LibrariesController extends Controller
{
    public function index()
    {
        $authUser = Auth::user();
        if ($authUser->can('manage libraries') || $authUser->type === 'super admin' || $authUser->type === 'company' || $authUser->type === 'advocate') {
            return view('libraries.index');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function filterData(Request $request)
    {
        $authUser = Auth::user();
        if ($authUser->can('manage libraries') || $authUser->type === 'super admin' || $authUser->type === 'company' || $authUser->type === 'advocate') {
            $letter            = $request->letter ?? '';
            $html              = '';
            $description       = '';
            $imageUrl          = '';
            $superAdmin        = User::where('type', 'super admin')->first();
            if ($authUser->type == 'super admin' || $authUser->type == 'advocate' || $authUser->type == 'Clerk') {
                $createdByID = [$superAdmin->id];
            } elseif ($authUser->type == 'company') {
                $createdByID = [$superAdmin->id, $authUser->id];
            } else {
                $createdByID = [$authUser->creatorId()];
            }
            $librarySetting    = LibrarySetting::whereIn('created_by', $createdByID)->get();
            $headerName        = $request->headerName;
            $models = [
                'Case Law By Area'    => ['CaseLawByAreaCategory', 'case_law_by_area_description', 'name', 'caseLawByAreaCategories', 'case-law-by-area'],
                'Legislation'         => ['Legislation', 'legislation_description', 'title', 'legislations', 'legislation'],
                'Journals'            => ['Journal', 'journals_description', 'title', 'journals', 'journal'],
                'Research'            => ['Research', 'research_description', 'title', 'researches', 'research'],
                'Practice Tools'      => ['PracticeToolCategory', 'practice_tools_description', 'name', 'practiceToolCategories', 'practice-tool'],
                'Recent Developments' => ['RecentDevelopmentCategory', 'recent_developments_description', 'name', 'recentDevelopmentCategories', 'recent-development', 'recent_development_image']
            ];
            $description = '';
            if (isset($models[$headerName])) {
                [$modelClass, $descKey, $field, $variableName, $viewFolder, $imageKey] = array_merge($models[$headerName], [null, null]);
                $modelInstance  = "App\\Models\\$modelClass";
                $$variableName  = ($letter === 'All') ? $modelInstance::whereIn('created_by', $createdByID)->get() : $modelInstance::where($field, 'like', $letter . '%')->whereIn('created_by', $createdByID)->get();
                foreach ($librarySetting as $librarySettingVal) {
                    if ($librarySettingVal->name == $descKey) {
                        $description    .= $librarySettingVal->value ?? '';
                    }
                }
                if ($headerName === 'Recent Developments' && $imageKey) {
                    $imagePath  = $librarySetting[$imageKey] ?? '/uploads/profile/avatar.png';
                    $imageUrl   = Utility::get_file($imagePath) . (Storage::exists($librarySetting[$imageKey] ?? '') ? '?v=' . time() : '');
                }
                $viewName  = 'libraries.' . $viewFolder . '.filter';
                $html     .= view($viewName, [$variableName => $$variableName])->render();
            }

            return response()->json(['html' => $html, 'description' => $description, 'imageUrl' => $imageUrl]);
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function caseLawByAreaSearchData(Request $request)
    {
        $html   = '';
        $letter = $request->input('text');

        $caseLawByAreas = [];
        if ($letter) {
            $caseLawByAreas = CaseLawByArea::with('category')->where('title', 'LIKE', $letter . '%')->get();
            $groupedCaseLawByAreas = $caseLawByAreas->groupBy(function ($item) {
                return $item->category->name ?? 'No category';
            });
        }
        $html     .= view('libraries.case-law-by-area.filter', ['groupedCaseLawByAreas' => $groupedCaseLawByAreas])->render();
        return response()->json(['html' => $html]);
    }

    public function journalSearchData(Request $request)
    {
        $html   = '';
        $letter = $request->input('text');
        $journals = [];
        if ($letter) {
            $journals = Journal::where('title', 'LIKE', $letter . '%')->get();
        }
        $html     .= view('libraries.journal.filter', ['journals' => $journals])->render();
        return response()->json(['html' => $html]);
    }

    public function show($id, $slug)
    {
        $modelMap = [
            'case-law-by-area' => CaseLawByArea::class,
            'journal' => Journal::class,
            'legislation' => Legislation::class,
            'research' => Research::class,
            'practice-tool' => PracticeTool::class,
            'recent-development' => RecentDevelopment::class,
        ];
        if (!array_key_exists($slug, $modelMap)) {
            abort(404, 'Model not found for the provided slug.');
        }
        $modelClass = $modelMap[$slug];
        $libraryData = $modelClass::findOrFail($id);
        if ($slug === 'journal' && (!isset($libraryData->site_link) || empty($libraryData->site_link))) {
            return redirect()->back()->with('error', 'No site link available.');
        }
        return view('libraries.show', compact('libraryData'));
    }
}
