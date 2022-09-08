<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Facades\{
    App\Services\ParentsToBeMatchedDonerService,
};

class ParentsToBeDashboardController extends Controller
{
    /**
     * @OA\Get(
     *      path="/v1/parents-matched-doner",
     *      operationId="parents-matched-doner",
     *      tags={"Parents To Be Dashboard"},
     *      summary="Get Parents matched donars.",
     *      description="Get Parents matched donars.",
     *      @OA\Response(
     *          response=200,
     *          description="Parents matched donars found successfully.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      security={ {"bearer": {}} },
     *  )
     */
    public function matchedDonars(Request $request)
    {
        try {
            $limit = isset($request->limit) && ($request->limit > ZERO) ? $request->limit : DASHBOARD_PAGE_LIMIT;
            $page = isset($request->page) && ($request->page > ZERO) ? $request->page : ONE;
            $collection = collect(ParentsToBeMatchedDonerService::myMatchedDonars());
            $currentPageResults = $collection->slice(($page - 1) * $limit, $limit)->values();
            $matchedDonars = new LengthAwarePaginator($currentPageResults, $collection->count(), $limit , $page, []);
            $response = response()->Success(trans('messages.common_msg.data_found'), $matchedDonars);
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }

        return $response;
    }
}
