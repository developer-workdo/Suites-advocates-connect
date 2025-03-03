<?php

namespace App\Http\Controllers;

use App\DataTables\RecentDevelopmentDataTable;
use App\Models\RecentDevelopment;
use App\Models\RecentDevelopmentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RecentDevelopmentController extends Controller
{
    public function index(RecentDevelopmentDataTable $dataTable)
    {
        if (Auth::user()->can(abilities: 'manage recent development')) {
            return $dataTable->render('libraries.recent-development.index');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('create recent development')) {
            $recentDevelopmentCategories = RecentDevelopmentCategory::where('created_by', Auth::user()->creatorId())->pluck('name', 'id');
            return view('libraries.recent-development.create', compact('recentDevelopmentCategories'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function companyRecentDevelopmentCreate($recentDevelopmentCategoryId)
    {
        if (Auth::user()->can('create recent development')) {
            return view('libraries.recent-development.create', compact('recentDevelopmentCategoryId'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create recent development')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'recent_development_category' => 'required|integer|exists:recent_development_categories,id',
                    'title'                  => 'required|string|max:191',
                    'description'            => 'required|string',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $recentDevelopment = new RecentDevelopment();
            $recentDevelopment['recent_development_category_id'] = $request->recent_development_category;
            $recentDevelopment['title']                          = $request->title;
            $recentDevelopment['description']                    = $request->description;
            if ($request->has('is_company_specific')) {
                $recentDevelopment['created_by']                 = Auth::user()->creatorId();
            }
            $recentDevelopment->save();
            return redirect()->back()->with('success', __('Recent Development successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit recent development')) {
            $recentDevelopment           = RecentDevelopment::find($id);
            $recentDevelopmentCategories = RecentDevelopmentCategory::where('created_by', Auth::user()->creatorId())->pluck('name', 'id');
            return view('libraries.recent-development.edit', compact('recentDevelopment', 'recentDevelopmentCategories'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit recent development')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'recent_development_category' => 'required|integer|exists:recent_development_categories,id',
                    'title'                       => 'required|string|max:191',
                    'description'                 => 'required|string',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $recentDevelopment  = RecentDevelopment::find($id);
            $recentDevelopment['recent_development_category_id'] = $request->recent_development_category;
            $recentDevelopment['title']                          = $request->title;
            $recentDevelopment['description']                    = $request->description;
            $recentDevelopment->save();
            return redirect()->back()->with('success', __('Recent Development successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->can('delete recent development')) {
            $recentDevelopment = RecentDevelopment::find($id);
            $recentDevelopment->delete();
            return redirect()->back()->with('success', __('Recent Development successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
