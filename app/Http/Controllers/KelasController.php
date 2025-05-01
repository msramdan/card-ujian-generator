<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Http\Requests\Kelas\{StoreKelaRequest, UpdateKelaRequest};
use Illuminate\Contracts\View\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\{JsonResponse, RedirectResponse};
use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};

class KelasController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // 'auth',

            // TODO: uncomment this code if you are using spatie permission
            // new Middleware('permission:kela view', only: ['index', 'show']),
            // new Middleware('permission:kela create', only: ['create', 'store']),
            // new Middleware('permission:kela edit', only: ['edit', 'update']),
            // new Middleware('permission:kela delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            $kelas = Kelas::query();

            return DataTables::of($kelas)
                ->addColumn('action', 'kelas.include.action')
                ->toJson();
        }

        return view('kelas.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('kelas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKelaRequest $request): RedirectResponse
    {

        Kelas::create($request->validated());

        return to_route('kelas.index')->with('success', __('The kela was created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kela): View
    {
        return view('kelas.show', compact('kela'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kela): View
    {
        return view('kelas.edit', compact('kela'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKelaRequest $request, Kelas $kela): RedirectResponse
    {

        $kela->update($request->validated());

        return to_route('kelas.index')->with('success', __('The kela was updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kela): RedirectResponse
    {
        try {
            $kela->delete();

            return to_route('kelas.index')->with('success', __('The kela was deleted successfully.'));
        } catch (\Exception $e) {
            return to_route('kelas.index')->with('error', __("The kela can't be deleted because it's related to another table."));
        }
    }
}
