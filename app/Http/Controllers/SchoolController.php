<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;

class SchoolController extends Controller
{
    public function search(Request $request)
    {
        $q = $request->get('q', '');

        if (!$q) {
            return response()->json([]);
        }

        $schools = School::where('name', 'LIKE', "%{$q}%")
            ->orWhere('city', 'LIKE', "%{$q}%")

            ->get(['id','name','city','prefecture']);

        return response()->json($schools);
    }
}
