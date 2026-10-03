<?php

namespace App\Http\Controllers;

use App\Models\Matchday;
use Illuminate\View\View;
use App\Models\Team;
use App\Models\Scorer;

class HomeController extends Controller
{
    public function index() : View {
        $data = Matchday::dashboard();
        $teams_data = Team::dashboard();
        $scorers_data = Scorer::dashboard();
        return view('home.index', compact('data', 'teams_data', 'scorers_data'));
    }
}
