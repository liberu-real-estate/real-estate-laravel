<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSettings;
use Illuminate\Http\Request;

class SiteSettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSettings::first();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'currency' => 'required|string',
            // Ajoutez d'autres validations si nécessaire
        ]);

        $settings = SiteSettings::first();
        if ($settings) {
            $settings->update($request->all());
        } else {
            SiteSettings::create($request->all());
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}
