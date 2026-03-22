<?php

declare(strict_types=1);

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administrator\SettingStoreRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SaeedHosan\Additions\Support\EnvEditor;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('administrator.settings.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SettingStoreRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = Arr::map($request->validated(), function (mixed $value, string $key) {

            if ($value instanceof \Illuminate\Http\UploadedFile) {

                $filename = sprintf('%s.%s', Str::slug($key), $value->getClientOriginalExtension());
                $path     = $value->storeAs('images', $filename, 'public');

                return Storage::url($path);
            }

            return $value;
        });

        foreach ($data as $key => $value) {
            EnvEditor::put($key, $value);
        }

        return redirect()->back()->with('success', 'Settings was successfully saved!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
}
