<?php

declare(strict_types=1);

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Factory|View
    {
        $search = $request->query('search');

        $activities = Activity::query()
            ->with(['causer', 'subject'])
            ->where('causer_id', Auth::id())
            ->when($search, function ($query, string $value): void {
                $query->where('description', 'like', sprintf('%%%s%%', $value));
            })->latest()
            ->paginate(10);

        return view('app.activities.index', [
            'activities' => $activities,
            'search'     => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Factory|View
    {
        return view('app.activities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Factory|View
    {
        $activity = Activity::query()->find($id);

        return view('app.activities.show', ['activity' => $activity]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Factory|View
    {
        return view('app.activities.edit', ['id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        Activity::query()->where('causer_id', Auth::id())->delete();

        return back()->with('success', 'Activity logs cleared successfully.');
    }
}
