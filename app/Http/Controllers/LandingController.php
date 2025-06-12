<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Obtener las series paginadas y ordenadas alfabéticamente
        $series = Series::orderBy('name', 'asc')->paginate(12);
        
        return view('landing', compact('series'));
    }
}
