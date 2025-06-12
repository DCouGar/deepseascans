<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Series;
use App\Models\Chapter;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $seriesCount = Series::count();
        $chaptersCount = Chapter::count();
        
        return view('admin.dashboard', compact('seriesCount', 'chaptersCount'));
    }
}
