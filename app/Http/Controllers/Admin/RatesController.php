<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Rate\BulkDestroyRate;
use App\Http\Requests\Admin\Rate\DestroyRate;
use App\Http\Requests\Admin\Rate\IndexRate;
use App\Http\Requests\Admin\Rate\StoreRate;
use App\Http\Requests\Admin\Rate\UpdateRate;
use App\Models\Rate;
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

class RatesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexRate $request
     * @return array|Factory|View
     */
    public function index(IndexRate $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Rate::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['base_rate', 'id', 'rate_increment', 'type'],

            // set columns to searchIn
            ['id', 'type']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.rate.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.rate.create');

        return view('admin.rate.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRate $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreRate $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Rate
        $rate = Rate::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/rates'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/rates');
    }

    /**
     * Display the specified resource.
     *
     * @param Rate $rate
     * @throws AuthorizationException
     * @return void
     */
    public function show(Rate $rate)
    {
        $this->authorize('admin.rate.show', $rate);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Rate $rate
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Rate $rate)
    {
        $this->authorize('admin.rate.edit', $rate);


        return view('admin.rate.edit', [
            'rate' => $rate,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRate $request
     * @param Rate $rate
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateRate $request, Rate $rate)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Rate
        $rate->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/rates'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/rates');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyRate $request
     * @param Rate $rate
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyRate $request, Rate $rate)
    {
        $rate->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyRate $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyRate $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Rate::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
