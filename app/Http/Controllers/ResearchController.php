<?php

namespace App\Http\Controllers;

use App\DataTables\ResearchDataTable;
use App\Models\Research;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ResearchController extends Controller
{
    public function index(ResearchDataTable $dataTable)
    {
        if (Auth::user()->can('manage research')) {
            return $dataTable->render('libraries.research.index');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('create research')) {
            return view('libraries.research.create');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create research')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'title'       => 'required|string|max:191|unique:researches,title',
                    'description' => 'required|string',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $research = new Research();
            $research['title']       = $request->title;
            $research['description'] = $request->description;
            $research['created_by']  = Auth::user()->creatorId();
            $research->save();
            return redirect()->back()->with('success', __('Research successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit research')) {
            $research = Research::find($id);
            return view('libraries.research.edit', compact('research'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit research')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'title'       => 'required|string|max:191|unique:researches,title,'.$id,
                    'description' => 'required|string',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $research  = Research::find($id);
            $research['title']       = $request->title;
            $research['description'] = $request->description;
            $research->save();
            return redirect()->back()->with('success', __('Research successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->can('delete research')) {
            $research = Research::find($id);
            $research->delete();
            return redirect()->back()->with('success', __('Research successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
