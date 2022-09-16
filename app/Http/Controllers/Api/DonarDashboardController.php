<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\AuthHelper;
use Facades\{
    App\Services\DonarDashboardService,
};
use App\Http\Requests\PtbProfileCardRequest;

class DonarDashboardController extends Controller
{
    /**
     * @OA\Get(
     *      path="/v1/ptb-profile-card",
     *      operationId="ptb-profile-card",
     *      tags={"User"},
     *      summary="ptb-profile-card",
     *      description="ptb-profile-card",
     *     @OA\Parameter(
     *         description="Keyword => Min 3 characters",
     *         in="query",
     *         name="keyword",
     *         @OA\Schema(
     *             type="string"
     *         )
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
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
    public function getPtbProfileCard(PtbProfileCardRequest $request)
    {
        try {
            $limit = isset($request->limit) && ($request->limit > ZERO) ? $request->limit : DASHBOARD_PAGE_LIMIT;
            $donarProfileCard = DonarDashboardService::getPtbProfileCard($request->all());
            $profileCards = $donarProfileCard->paginate($limit);
            $response = response()->Success(trans('messages.common_msg.data_found'), $profileCards);
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
