<?php

namespace App\Http\Controllers;

use App\Services\SiteService;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

class SiteController extends Controller
{
    public function __construct(private SiteService $siteService)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'server' => "",
            "sites" => $this->siteService->list(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $this->siteService->save(request()->post());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id)
    {
        $this->siteService->update($id, request()->post());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->siteService->remove($id);
    }

    public function check() {
        $this->siteService->runBackground(true);
    }
}
