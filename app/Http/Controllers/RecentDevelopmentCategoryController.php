<?php

namespace App\Http\Controllers;

use App\DataTables\RecentDevelopmentCategoryDataTable;
use App\Models\RecentDevelopmentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RecentDevelopmentCategoryController extends Controller
{
    public function index(RecentDevelopmentCategoryDataTable $dataTable)
    {
        if (Auth::user()->can('manage recent development category')) {
            return $dataTable->render('libraries.recent-development-categories.index');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('create recent development category')) {
            return view('libraries.recent-development-categories.create');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create recent development category')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|unique:recent_development_categories,name',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $recentDevelopmentCategory               = new RecentDevelopmentCategory();
            $recentDevelopmentCategory['name']       = $request->name;
            $recentDevelopmentCategory['created_by'] = Auth::user()->creatorId();
            $recentDevelopmentCategory->save();
            return redirect()->back()->with('success', __('Recent Development Category successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit recent development category')) {
            $recentDevelopmentCategory = RecentDevelopmentCategory::find($id);
            return view('libraries.recent-development-categories.edit', compact('recentDevelopmentCategory'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit recent development category')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|unique:recent_development_categories,name,' . $id,
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $recentDevelopmentCategory       = RecentDevelopmentCategory::find($id);
            $recentDevelopmentCategory->name = $request->name;
            $recentDevelopmentCategory->save();
            return redirect()->back()->with('success', __('Recent Development Category successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->can('delete recent development category')) {
            $category = RecentDevelopmentCategory::find($id);
            $category->delete();
            return redirect()->back()->with('success', __('Recent Development Category successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
