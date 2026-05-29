<?php

namespace App\Http\Controllers;

use App\Models\Matchday;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Team;

class HomeController extends Controller
{
    public function index() : View {
        $data = Matchday::dashboard();
        $teams_data = Team::dashboard();
        return view('home.index', compact('data', 'teams_data'));
    }
}
