<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Helpers\AuthHelper;

class NotificationController extends Controller
{
     /**
     * @OA\Get(
     *      path="/v1/new-notification/{notifyType}",
     *      operationId="new-notification",
     *      tags={"Notification"},
     *      summary="Get new notification",
     *      description="Notify Type [1 => match, 2 => subscription, 3 => chat]",
     *      @OA\Parameter(
     *         description="Notify Type",
     *         in="path",
     *         name="notifyType",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
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
    public function getNewNotification(Request $request)
    {
        try {
            $response = response()->Success(trans('messages.common_msg.data_found'), Notification::getUnreadCount(AuthHelper::authenticatedUser()->id, $request->notifyType));
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
