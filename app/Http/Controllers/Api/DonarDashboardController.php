<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\AuthHelper;
use Facades\{
    App\Services\DonarDashboardService,
};

class DonarDashboardController extends Controller
{
    /**
     * @OA\Get(
     *      path="/v1/donar-profile-card",
     *      operationId="donar-profile-card",
     *      tags={"User"},
     *      summary="donar-profile-card",
     *      description="donar-profile-card",
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
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
    public function getDonarProfileCard(Request $request)
    {
        try {
            $limit    = isset($request->limit) && ($request->limit > ZERO) ? $request->limit : DASHBOARD_PAGE_LIMIT;
            $donarProfileCard = DonarDashboardService::getDonarProfileCard();
            $profileCards = $donarProfileCard->paginate($limit);
            $response = response()->Success(trans('messages.common_msg.data_found'), $profileCards);
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
