<?php

namespace App\Http\Controllers;

use App\DataTables\PracticeToolCategoryDataTable;
use App\Models\PracticeToolCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PracticeToolCategoryController extends Controller
{
    public function index(PracticeToolCategoryDataTable $dataTable)
    {
        if (Auth::user()->can('manage practice tool category')) {
            return $dataTable->render('libraries.practice-tool-categories.index');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('create practice tool category')) {
            return view('libraries.practice-tool-categories.create');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create practice tool category')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|unique:practice_tool_categories,name',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $practiceToolCategory               = new PracticeToolCategory();
            $practiceToolCategory['name']       = $request->name;
            $practiceToolCategory['created_by'] = Auth::user()->creatorId();
            $practiceToolCategory->save();
            return redirect()->back()->with('success', __('Practice Tool Category successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit practice tool category')) {
            $practiceToolCategory = PracticeToolCategory::find($id);
            return view('libraries.practice-tool-categories.edit', compact('practiceToolCategory'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit practice tool category')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|unique:practice_tool_categories,name,' . $id,
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $practiceToolCategory       = PracticeToolCategory::find($id);
            $practiceToolCategory->name = $request->name;
            $practiceToolCategory->save();
            return redirect()->back()->with('success', __('Practice Tool Category successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->can('delete practice tool category')) {
            $category = PracticeToolCategory::find($id);
            $category->delete();
            return redirect()->back()->with('success', __('Practice Tool Category successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
