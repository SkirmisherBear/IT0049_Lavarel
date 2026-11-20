<?php

namespace App\Http\Controllers;

use App\Models\ProgressEntry;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index(Request $request): View
    {
        $entries = ProgressEntry::whereBelongsTo($request->user())
            ->latest('recorded_on')
            ->paginate(10);

        return view('client.progress', compact('entries'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'activity' => ['required', 'string', 'max:100'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:1440'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'recorded_on' => ['required', 'date', 'before_or_equal:today'],
        ]);

        $request->user()->progressEntries()->create($validated);

        return redirect()->route('client.progress')->with('success', 'Progress entry saved.');
    }
}
