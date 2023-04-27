<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\AuthHelper;
use Facades\{
    App\Services\PaymentService,
};

class PaymentController extends Controller
{
    /**
     * @OA\Get(
     *      path="/v1/match-list",
     *      operationId="match-list",
     *      tags={"Payment"},
     *      summary="Get profile match list",
     *      description="Get profile match list",
     *      @OA\Parameter(
     *          name="keyword",
     *          in="query",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
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
    public function getMatchList(Request $request)
    {
        try {
            $limit = isset($request->limit) && ($request->limit > ZERO) ? $request->limit : DASHBOARD_PAGE_LIMIT;
            $matchList = PaymentService::getUsersByProfileMatchAndKeyword(AuthHelper::authenticatedUser()->id, $request->keyword);
            if ($matchList) {
                $response = response()->Success(trans('messages.common_msg.data_found'), $matchList->paginate($limit));
            } else {
                $response = response()->Error(trans('messages.common_msg.no_data_found'));
            }
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
