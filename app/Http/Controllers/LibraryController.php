<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\Library;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class LibraryController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('manage library')) {
            $libraries = Library::where('created_by',Auth::user()->creatorId())->get();
            return view('library.index', compact('libraries'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('create library')) {
            $cases = Cases::where('created_by', Auth::user()->id)->pluck('title', 'id')->prepend('Select Case', '');
            return view('library.create', compact('cases'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create library')) {
            $validation = [
                'case_id' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (Library::where('case_id', $value)->exists()) {
                            $fail('The case ID has already been assigned.');
                        }
                    },
                ],
                'legal_document'        => 'required|mimes:pdf,doc,docx|max:20000',
                'template'              => 'required|mimes:pdf,doc,docx|max:20000',
                'case_law'              => 'required|mimes:doc,docx|max:20000',
                'statute_regulation'    => 'required|mimes:pdf|max:20000',
                'practice_guide'        => 'required|mimes:pdf|max:20000',
                'form_checklist'        => 'required|mimes:pdf|max:20000',
                'article_publication'   => 'required|mimes:pdf,doc,docx|max:20000',
                'firm_policy_procedure' => 'required|mimes:pdf|max:20000',
                'research_tool'         => 'required|mimes:jpeg,jpg,png,gif,pdf,svg,txt,doc,docx,txt,html,htm|max:20000',
                'training_material'     => 'required|mimes:pdf|max:20000',
            ];
            $request->validate($validation);
            $library = new Library();
            $library['case_id'] = $request->case_id;
            $library['created_by'] = Auth::user()->creatorId();
            foreach ($request->allFiles() as $key => $file) {
                if (!empty($file)) {
                    $image_size = $file->getSize();
                    $result = Utility::updateStorageLimit(Auth::user()->id, $image_size);
                    if ($result == 1) {
                        $extension = $file->getClientOriginalExtension();
                        $fileNameToStore = $key . '_' . time() . '.' . $extension;
                        $dir = 'uploads/libraries/';

                        $path = Utility::upload_file($request, $key, $fileNameToStore, $dir, []);
                        if ($path['flag'] == 1) {
                            $url = $path['url'];
                        } else {
                            return redirect()->back()->with('error', __($path['msg']));
                        }
                        $library[$key] = $url;
                    }
                }
            }
            $library->save();
            return redirect()->route('libraries.index')->with('success', __('Library successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function show($id)
    {
        if (Auth::user()->can('view library')) {

            $library = Library::find($id);
            if ($library) {
                return view('library.view', compact('library'));
            } else {
                return redirect()->back()->with('error', __('Library Not Found.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit library')) {
            $library = Library::find($id);
            $cases = Cases::where('created_by', Auth::user()->id)->pluck('title', 'id')->prepend('Select Case', '');
            return view('library.edit', compact('library', 'cases'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit library')) {
            $validation = [
                'case_id' => [
                    'required',
                    function ($attribute, $value, $fail) use ($id) {
                        $existingLibrary = Library::where('case_id', $value)->first();
                        if ($existingLibrary && $existingLibrary->id != $id) {
                            $fail('The case ID has already been assigned.');
                        }
                    },
                ],
                'legal_document'        => 'mimes:pdf,doc,docx|max:20000',
                'template'              => 'mimes:pdf,doc,docx|max:20000',
                'case_law'              => 'mimes:doc,docx|max:20000',
                'statute_regulation'    => 'mimes:pdf|max:20000',
                'practice_guide'        => 'mimes:pdf|max:20000',
                'form_checklist'        => 'mimes:pdf|max:20000',
                'article_publication'   => 'mimes:pdf,doc,docx|max:20000',
                'firm_policy_procedure' => 'mimes:pdf|max:20000',
                'research_tool'         => 'mimes:jpeg,jpg,png,svg,gif,pdf,image,txt,doc,docx,txt,html,htm|max:20000',
                'training_material'     => 'mimes:pdf|max:20000',
            ];
            $request->validate($validation);

            $library = Library::find($id);
            $library['case_id'] = $request->case_id;
            foreach ($request->allFiles() as $key => $file) {
                if (!empty($file)) {
                    $image_size = $file->getSize();
                    $result = Utility::updateStorageLimit(Auth::user()->id, $image_size);
                    if ($result == 1) {
                        $extension = $file->getClientOriginalExtension();
                        $fileNameToStore = $key . '_' . time() . '.' . $extension;
                        $dir = 'uploads/libraries/';

                        $path = Utility::upload_file($request, $key, $fileNameToStore, $dir, []);
                        if ($path['flag'] == 1) {
                            $url = $path['url'];
                        } else {
                            return redirect()->back()->with('error', __($path['msg']));
                        }
                        $library[$key] = $url;
                    }
                }
            }
            $library->update();

            return redirect()->route('libraries.index')->with('success', __('Library successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->can('delete library')) {

            $library = Library::find($id);

            if ($library) {
                $documentTypes = [
                    'legal_document',
                    'template',
                    'case_law',
                    'statute_regulation',
                    'practice_guide',
                    'form_checklist',
                    'article_publication',
                    'firm_policy_procedure',
                    'research_tool',
                    'training_material'
                ];

                foreach ($documentTypes as $documentType) {
                    if (isset($library->$documentType)) {
                        $filePath = $library->$documentType;
                        Utility::changeStorageLimit(Auth::user()->creatorId(), $filePath);
                        if (File::exists($filePath)) {
                            File::delete($filePath);
                        }
                    }
                }

                $library->delete();
            }

            return redirect()->route('libraries.index')->with('success', __('Library successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
