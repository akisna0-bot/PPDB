<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index()
    {
        $applicant = Applicant::with(['major', 'wave', 'files', 'payments'])
            ->where('user_id', auth()->id())
            ->first();
            
        return view('status.index', compact('applicant'));
    }
}