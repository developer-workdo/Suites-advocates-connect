<?php

namespace App\Http\Controllers;

use App\DataTables\CaseLawByAreaCategoryDataTable;
use App\Models\CaseLawByAreaCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CaseLawByAreaCategoryController extends Controller
{
    public function index(CaseLawByAreaCategoryDataTable $dataTable)
    {
        if (Auth::user()->can('manage case law by area category')) {
            return $dataTable->render('libraries.case-law-by-area-category.index');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('create case law by area category')) {
            return view('libraries.case-law-by-area-category.create');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create case law by area category')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|unique:case_law_by_area_categories,name',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $caseLawByAreaCategory               = new CaseLawByAreaCategory();
            $caseLawByAreaCategory['name']       = $request->name;
            $caseLawByAreaCategory['created_by'] = Auth::user()->creatorId();
            $caseLawByAreaCategory->save();
            return redirect()->back()->with('success', __('Case Law By Area Category successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit case law by area category')) {
            $caseLawByAreaCategory = CaseLawByAreaCategory::find($id);
            return view('libraries.case-law-by-area-category.edit', compact('caseLawByAreaCategory'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit case law by area category')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|unique:case_law_by_area_categories,name,' . $id,
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $caseLawByAreaCategory       = CaseLawByAreaCategory::find($id);
            $caseLawByAreaCategory->name = $request->name;
            $caseLawByAreaCategory->save();
            return redirect()->back()->with('success', __('Case Law By Area Category successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->can('delete case law by area category')) {
            $category = CaseLawByAreaCategory::find($id);
            $category->delete();
            return redirect()->back()->with('success', __('Case Law By Area Category successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
