<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Vehicle\BulkDestroyVehicle;
use App\Http\Requests\Admin\Vehicle\DestroyVehicle;
use App\Http\Requests\Admin\Vehicle\IndexVehicle;
use App\Http\Requests\Admin\Vehicle\StoreVehicle;
use App\Http\Requests\Admin\Vehicle\UpdateVehicle;
use App\Models\Vehicle;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VehiclesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexVehicle $request
     * @return array|Factory|View
     */
    public function index(IndexVehicle $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Vehicle::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['capacity', 'id', 'model', 'reg_number', 'vehicle_type'],

            // set columns to searchIn
            ['id', 'model', 'reg_number', 'vehicle_type']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.vehicle.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.vehicle.create');

        return view('admin.vehicle.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreVehicle $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreVehicle $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Vehicle
        $vehicle = Vehicle::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/vehicles'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/vehicles');
    }

    /**
     * Display the specified resource.
     *
     * @param Vehicle $vehicle
     * @throws AuthorizationException
     * @return void
     */
    public function show(Vehicle $vehicle)
    {
        $this->authorize('admin.vehicle.show', $vehicle);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Vehicle $vehicle
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Vehicle $vehicle)
    {
        $this->authorize('admin.vehicle.edit', $vehicle);


        return view('admin.vehicle.edit', [
            'vehicle' => $vehicle,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateVehicle $request
     * @param Vehicle $vehicle
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateVehicle $request, Vehicle $vehicle)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Vehicle
        $vehicle->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/vehicles'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/vehicles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyVehicle $request
     * @param Vehicle $vehicle
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyVehicle $request, Vehicle $vehicle)
    {
        $vehicle->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyVehicle $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyVehicle $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Vehicle::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
