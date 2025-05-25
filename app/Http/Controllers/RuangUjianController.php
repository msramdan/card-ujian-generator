<?php

namespace App\Http\Controllers;

use App\Models\RuangUjian;
use App\Http\Requests\RuangUjians\{StoreRuangUjianRequest, UpdateRuangUjianRequest};
use Illuminate\Contracts\View\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\{JsonResponse, RedirectResponse};
use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};

class RuangUjianController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // 'auth',

            // TODO: uncomment this code if you are using spatie permission
            // new Middleware('permission:ruang ujian view', only: ['index', 'show']),
            // new Middleware('permission:ruang ujian create', only: ['create', 'store']),
            // new Middleware('permission:ruang ujian edit', only: ['edit', 'update']),
            // new Middleware('permission:ruang ujian delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            $ruangUjians = RuangUjian::query();

            return DataTables::of($ruangUjians)
                ->addColumn('action', 'ruang-ujian.include.action')
                ->toJson();
        }

        return view('ruang-ujian.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('ruang-ujian.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRuangUjianRequest $request): RedirectResponse
    {

        RuangUjian::create($request->validated());

        return to_route('ruang-ujian.index')->with('success', __('The ruang ujian was created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(RuangUjian $ruangUjian): View
    {
        return view('ruang-ujian.show', compact('ruangUjian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RuangUjian $ruangUjian): View
    {
        return view('ruang-ujian.edit', compact('ruangUjian'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRuangUjianRequest $request, RuangUjian $ruangUjian): RedirectResponse
    {

        $ruangUjian->update($request->validated());

        return to_route('ruang-ujian.index')->with('success', __('The ruang ujian was updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RuangUjian $ruangUjian): RedirectResponse
    {
        try {
            $ruangUjian->delete();

            return to_route('ruang-ujian.index')->with('success', __('The ruang ujian was deleted successfully.'));
        } catch (\Exception $e) {
            return to_route('ruang-ujian.index')->with('error', __("The ruang ujian can't be deleted because it's related to another table."));
        }
    }
}
