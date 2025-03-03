<?php

namespace App\Http\Controllers;

use App\DataTables\CaseLawByAreaDataTable;
use App\Models\CaseLawByArea;
use App\Models\CaseLawByAreaCategory;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CaseLawByAreaController extends Controller
{
    public function index(CaseLawByAreaDataTable $dataTable)
    {
        if (Auth::user()->can('manage case law by area')) {
            return $dataTable->render('libraries.case-law-by-area.index');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('create case law by area')) {
            $caseLawByAreaCategories = CaseLawByAreaCategory::where('created_by', Auth::user()->creatorId())->pluck('name', 'id');
            return view('libraries.case-law-by-area.create', compact('caseLawByAreaCategories'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function companyCaseLawByAreaCreate($caseLawByAreaCategoryId)
    {
        if (Auth::user()->can('create case law by area')) {
            return view('libraries.case-law-by-area.create', compact('caseLawByAreaCategoryId'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create case law by area')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'case_law_by_area_category' => 'required|integer|exists:case_law_by_area_categories,id',
                    'title'                     => 'required|string',
                    'description'               => 'required|string',
                    'document'                  => 'nullable|file|mimes:pdf,docx,doc,rtf',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $caseLawByArea = new CaseLawByArea();
            $caseLawByArea['case_law_by_area_category_id']  = $request->case_law_by_area_category;
            $caseLawByArea['title']                         = $request->title;
            $caseLawByArea['description']                   = $request->description;
            if ($request->hasFile('document')) {
                $documentName = $request->title . '.' . $request->document->getClientOriginalExtension();
                $dir          = 'uploads/case-law-documents/';
                $path         = Utility::upload_file($request, 'document', $documentName, $dir, []);
                if ($path['flag'] == 0) {
                    return redirect()->back()->with('error', __($path['msg']));
                }
                $caseLawByArea['document']   = $path['url'];
            }
            if ($request->has('is_company_specific')) {
                $caseLawByArea['created_by'] = Auth::user()->creatorId();
            }
            $caseLawByArea->save();
            return redirect()->back()->with('success', __('Case Law By Area successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit case law by area')) {
            $caseLawByArea           = CaseLawByArea::find($id);
            $caseLawByAreaCategories = CaseLawByAreaCategory::where('created_by', Auth::user()->creatorId())->pluck('name', 'id');
            return view('libraries.case-law-by-area.edit', compact('caseLawByArea', 'caseLawByAreaCategories'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit case law by area')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'case_law_by_area_category' => 'required|integer|exists:case_law_by_area_categories,id',
                    'title'                     => 'required|string',
                    'description'               => 'required|string',
                    'document'                  => 'nullable|file|mimes:pdf,docx,doc,rtf',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $caseLawByArea  = CaseLawByArea::find($id);
            $caseLawByArea['case_law_by_area_category_id']  = $request->case_law_by_area_category;
            $caseLawByArea['title']                         = $request->title;
            $caseLawByArea['description']                   = $request->description;
            if ($request->hasFile('document')) {
                $filenameWithExt = $request->title;
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('document')->getClientOriginalExtension();
                $fileNameToStore = $filename . '.' . $extension;
                $dir             = 'uploads/case-law-documents/';
                $path            = Utility::upload_file($request, 'document', $fileNameToStore, $dir, []);

                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->route('users.index', Auth::user()->id)->with('error', __($path['msg']));
                }
                $caseLawByArea->document = $url;
            }
            $caseLawByArea->save();
            return redirect()->back()->with('success', __('Case Law By Area successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->can('delete case law by area')) {
            $caseLawByArea = CaseLawByArea::find($id);
            $caseLawByArea->delete();
            return redirect()->back()->with('success', __('Case Law By Area successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
