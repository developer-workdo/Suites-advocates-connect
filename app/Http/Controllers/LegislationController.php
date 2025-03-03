<?php

namespace App\Http\Controllers;

use App\DataTables\LegislationDataTable;
use App\Models\Legislation;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LegislationController extends Controller
{
    public function index(LegislationDataTable $dataTable)
    {
        if (Auth::user()->can('manage legislation')) {
            return $dataTable->render('libraries.legislation.index');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('create legislation')) {
            return view('libraries.legislation.create');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('create legislation')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'title'       => 'required|string|unique:legislations,title',
                    'description' => 'required|string',
                    'document'    => 'nullable|file|mimes:pdf,docx,doc,rtf',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $legislation = new Legislation();
            $legislation['title']       = $request->title;
            $legislation['description'] = $request->description;
            if ($request->hasFile('document')) {
                $documentName   = $request->title . '.' . $request->document->getClientOriginalExtension();
                $dir            = 'uploads/legislation/';
                $path           = Utility::upload_file($request, 'document', $documentName, $dir, []);
                if ($path['flag'] == 0) {
                    return redirect()->back()->with('error', __($path['msg']));
                }
                $legislation['document'] = $path['url'];
            }
            $legislation['created_by']  = Auth::user()->creatorId();
            $legislation->save();
            return redirect()->back()->with('success', __('Legislation successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function edit($id)
    {
        if (Auth::user()->can('edit legislation')) {
            $legislation = Legislation::find($id);
            return view('libraries.legislation.edit', compact('legislation'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit legislation')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'title'       => 'required|string|unique:legislations,title,' . $id,
                    'description' => 'required|string',
                    'document'    => 'nullable|file|mimes:pdf,docx,doc,rtf',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $legislation  = Legislation::find($id);
            $legislation['title']       = $request->title;
            $legislation['description'] = $request->description;
            if ($request->hasFile('document')) {
                $filenameWithExt = $request->title;
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('document')->getClientOriginalExtension();
                $fileNameToStore = $filename . '.' . $extension;
                $dir = 'uploads/legislation/';
                $path = Utility::upload_file($request, 'document', $fileNameToStore, $dir, []);

                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->route('users.index', Auth::user()->id)->with('error', __($path['msg']));
                }

                $legislation->document = $url;
            }
            $legislation->save();
            return redirect()->back()->with('success', __('Legislation successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->can('delete legislation')) {
            $legislation = Legislation::find($id);
            $legislation->delete();
            return redirect()->back()->with('success', __('Legislation successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
