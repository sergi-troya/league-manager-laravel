<?php

namespace App\Http\Controllers;

use App\Models\Scorer;
use Illuminate\View\View;

class ScorerController extends Controller
{
    public function index(): View
    {
        $scorers = Scorer::with('player')
            ->orderByDesc('goals')
            ->paginate(15);

        return view('scorers.index', compact('scorers'));
    }
}