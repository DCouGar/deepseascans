<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TempAdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'seriesCount' => 0,
            'chaptersCount' => 0
        ]);
    }
}
