<?php

declare(strict_types=1);

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Http\Requests\Super\SettingStoreRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SaeedHosan\Useful\Support\EnvEditor;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Factory|View
    {
        return view('super.settings.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SettingStoreRequest $request): RedirectResponse
    {
        $data = Arr::map($request->validated(), function (mixed $value, string $key) {

            if ($value instanceof UploadedFile) {

                $filename = sprintf('%s.%s', Str::slug($key), $value->getClientOriginalExtension());
                $path     = $value->storeAs('images', $filename, 'public');

                return Storage::url($path);
            }

            return $value;
        });

        foreach ($data as $key => $value) {
            EnvEditor::put($key, $value);
        }

        return back()->with('success', 'Settings was successfully saved!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): void
    {
        //
    }
}
