<?php

namespace App\Http\Controllers;

use App\DataTables\JournalDataTable;
use App\Models\Journal;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JournalController extends Controller
{
    public function index(JournalDataTable $dataTable)
    {
        if (Auth::user()->can('manage journal')) {
            return $dataTable->render('libraries.journal.index');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('create journal')) {
            return view('libraries.journal.create');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create journal')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'title'     => 'required|string|unique:journals,title',
                    'site_link' => 'required|url',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $journal = new Journal();
            $journal['title']       = $request->title;
            $journal['site_link']   = $request->site_link;
            $journal['created_by']  = Auth::user()->creatorId();
            $journal->save();
            return redirect()->back()->with('success', __('Journal successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit journal')) {
            $journal = Journal::find($id);
            return view('libraries.journal.edit', compact('journal'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit journal')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'title'     => 'required|string|unique:journals,title,' . $id,
                    'site_link' => 'required|url',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $journal  = Journal::find($id);
            $journal['title']       = $request->title;
            $journal['site_link']   = $request->site_link;
            $journal->save();
            return redirect()->back()->with('success', __('Journal successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->can('delete journal')) {
            $journal = Journal::find($id);
            $journal->delete();
            return redirect()->back()->with('success', __('Journal successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
