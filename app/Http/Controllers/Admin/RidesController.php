<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Ride\BulkDestroyRide;
use App\Http\Requests\Admin\Ride\DestroyRide;
use App\Http\Requests\Admin\Ride\IndexRide;
use App\Http\Requests\Admin\Ride\StoreRide;
use App\Http\Requests\Admin\Ride\UpdateRide;
use App\Models\Ride;
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

class RidesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexRide $request
     * @return array|Factory|View
     */
    public function index(IndexRide $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Ride::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['client_id', 'driver_id', 'end_loc_latitude', 'end_loc_longitude', 'id', 'start_loc_latitude', 'start_loc_longitude'],

            // set columns to searchIn
            ['end_loc_latitude', 'end_loc_longitude', 'id', 'start_loc_latitude', 'start_loc_longitude']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.ride.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.ride.create');

        return view('admin.ride.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRide $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreRide $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Ride
        $ride = Ride::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/rides'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/rides');
    }

    /**
     * Display the specified resource.
     *
     * @param Ride $ride
     * @throws AuthorizationException
     * @return void
     */
    public function show(Ride $ride)
    {
        $this->authorize('admin.ride.show', $ride);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Ride $ride
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Ride $ride)
    {
        $this->authorize('admin.ride.edit', $ride);


        return view('admin.ride.edit', [
            'ride' => $ride,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRide $request
     * @param Ride $ride
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateRide $request, Ride $ride)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Ride
        $ride->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/rides'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/rides');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyRide $request
     * @param Ride $ride
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyRide $request, Ride $ride)
    {
        $ride->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyRide $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyRide $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Ride::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
