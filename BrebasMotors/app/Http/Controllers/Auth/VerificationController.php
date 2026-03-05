<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('show');
    }

    public function show()
    {
        return view('auth.verify');
    }

    public function verify(Request $request, $id, $hash)
    {
        // Placeholder: not implemented
        return redirect('/');
    }

    public function resend(Request $request)
    {
        return back()->with('status', 'Email verification is not configured.');
    }
}
