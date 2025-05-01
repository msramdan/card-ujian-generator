<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Http\Requests\Jurusans\{StoreJurusanRequest, UpdateJurusanRequest};
use Illuminate\Contracts\View\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\{JsonResponse, RedirectResponse};
use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};

class JurusanController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            // 'auth',

            // TODO: uncomment this code if you are using spatie permission
            // new Middleware('permission:jurusan view', only: ['index', 'show']),
            // new Middleware('permission:jurusan create', only: ['create', 'store']),
            // new Middleware('permission:jurusan edit', only: ['edit', 'update']),
            // new Middleware('permission:jurusan delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            $jurusans = Jurusan::query();

            return DataTables::of($jurusans)
                ->addColumn('action', 'jurusan.include.action')
                ->toJson();
        }

        return view('jurusan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('jurusan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJurusanRequest $request): RedirectResponse
    {

        Jurusan::create($request->validated());

        return to_route('jurusan.index')->with('success', __('The jurusan was created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Jurusan $jurusan): View
    {
        return view('jurusan.show', compact('jurusan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jurusan $jurusan): View
    {
        return view('jurusan.edit', compact('jurusan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJurusanRequest $request, Jurusan $jurusan): RedirectResponse
    {

        $jurusan->update($request->validated());

        return to_route('jurusan.index')->with('success', __('The jurusan was updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jurusan $jurusan): RedirectResponse
    {
        try {
            $jurusan->delete();

            return to_route('jurusan.index')->with('success', __('The jurusan was deleted successfully.'));
        } catch (\Exception $e) {
            return to_route('jurusan.index')->with('error', __("The jurusan can't be deleted because it's related to another table."));
        }
    }
}
