<?php

namespace App\Http\Controllers;

use App\DataTables\PracticeToolDataTable;
use App\Models\PracticeTool;
use App\Models\PracticeToolCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PracticeToolController extends Controller
{
    public function index(PracticeToolDataTable $dataTable)
    {
        if (Auth::user()->can('manage practice tool')) {
            return $dataTable->render('libraries.practice-tool.index');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('create practice tool')) {
            $practiceToolCategories = PracticeToolCategory::where('created_by', Auth::user()->creatorId())->pluck('name', 'id');
            return view('libraries.practice-tool.create', compact('practiceToolCategories'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function companyPracticeToolCreate($practiceToolCategoryId)
    {
        if (Auth::user()->can('create practice tool')) {
            return view('libraries.practice-tool.create', compact('practiceToolCategoryId'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create practice tool')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'practice_tool_category' => 'required|integer|exists:practice_tool_categories,id',
                    'title'                  => 'required|string|max:191',
                    'description'            => 'required|string',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $practiceTool = new PracticeTool();
            $practiceTool['practice_tool_category_id'] = $request->practice_tool_category;
            $practiceTool['title']                     = $request->title;
            $practiceTool['description']               = $request->description;
            if ($request->has('is_company_specific')) {
                $practiceTool['created_by']            = Auth::user()->creatorId();
            }
            $practiceTool->save();
            return redirect()->back()->with('success', __('Practice Tool successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit practice tool')) {
            $practiceTool           = PracticeTool::find($id);
            $practiceToolCategories = PracticeToolCategory::where('created_by', Auth::user()->creatorId())->pluck('name', 'id');
            return view('libraries.practice-tool.edit', compact('practiceTool', 'practiceToolCategories'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit practice tool')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'practice_tool_category' => 'required|integer|exists:practice_tool_categories,id',
                    'title'                  => 'required|string|max:191',
                    'description'            => 'required|string',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $practiceTool  = PracticeTool::find($id);
            $practiceTool['practice_tool_category_id']  = $request->practice_tool_category;
            $practiceTool['title']                      = $request->title;
            $practiceTool['description']                = $request->description;
            $practiceTool->save();
            return redirect()->back()->with('success', __('Practice Tool successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->can('delete practice tool')) {
            $practiceTool = PracticeTool::find($id);
            $practiceTool->delete();
            return redirect()->back()->with('success', __('Practice Tool successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
