<?php

namespace App\Http\Controllers;

use App\Models\RentalApplication;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function applications()
    {
        $applications = RentalApplication::where('tenant_id', auth()->id())
            ->with('property')
            ->latest()
            ->get();

        return view('tenant.applications', compact('applications'));
    }
}