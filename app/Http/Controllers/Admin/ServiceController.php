<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ServiceController extends Controller
{
    public function index(): Factory|View
    {
        $services = Service::with('companies')->latest()->paginate(15);

        return view('admin.services.index', ['services' => $services]);
    }
}
