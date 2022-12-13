<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Traits\ParentsToBeMatchedDonerTrait;
use Facades\{
    App\Services\SubscriptionService,
};
use App\Helpers\AuthHelper;

class ParentsToBeDashboardController extends Controller
{
    use ParentsToBeMatchedDonerTrait;

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
            $subscriptionStatus = SubscriptionService::getSubscriptionStatus(AuthHelper::authenticatedUser()->id);
            $limit = $limit_cards = isset($request->limit) && ($request->limit > ZERO) ? $request->limit : DASHBOARD_PAGE_LIMIT;
            $page = isset($request->page) && ($request->page > ZERO) ? $request->page : ONE;
            $data = $this->myMatchedDonars();
            if ($subscriptionStatus == SUBSCRIPTION_TRIAL) {
                $limit_cards = SubscriptionService::getDailiyTrailCardLimit(AuthHelper::authenticatedUser()->id);
                $page = ONE;
                $status = ONE;
            }
            if ($limit_cards == ZERO) {
                $data = [];
                $data = array_slice($data, ZERO, $limit_cards);
                $status = TWO;
            }else{
                $limit = $limit_cards;
                $data = array_slice($data, ZERO, $limit);
                $status = ($data == []) ? THREE: ONE;
            }
            $collection = collect($data);
            $currentPageResults = $collection->slice(($page - 1) * $limit, $limit)->values();
            $matchedDonars = new LengthAwarePaginator($currentPageResults, $collection->count(), $limit, $page, []);
            $response = response()->json([MESSAGE => trans('messages.common_msg.data_found'),DATA => [], STATUS => THREE],Response::HTTP_OK);
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }

        return $response;
    }
}
