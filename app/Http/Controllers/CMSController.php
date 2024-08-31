<?php

namespace App\Http\Controllers;

use App\Models\HomepageSection;
use Illuminate\Http\Request;

class CMSController extends Controller
{
    public function index()
    {
        $sections = HomepageSection::all();
        return view('cms.manage', compact('sections'));
    }

    public function edit($section_name)
    {
        $section = HomepageSection::where('section_name', $section_name)->first();
        if (!$section) {
            return redirect()->route('cms.index')->with('error', 'Section not found.');
        }
        return view('cms.manage', compact('section'));
    }

    public function update(Request $request, $section_name)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $section = HomepageSection::where('section_name', $section_name)->first();
        if (!$section) {
            return redirect()->route('cms.index')->with('error', 'Section not found.');
        }

        $section->content = $request->input('content');
        $section->save();

        return redirect()->route('cms.index')->with('success', 'Section updated successfully.');
    }
}
