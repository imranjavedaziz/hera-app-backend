<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Helpers\AuthHelper;
use Facades\{
    App\Services\StateService,
    App\Services\FirebaseService,
};
use DB;

class StateController extends Controller
{
   /**
     * @OA\Get(
     *      path="/v1/states",
     *      operationId="states",
     *      tags={"User"},
     *      summary="Get name of states",
     *      description="Get name of states",
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
     *  )
     */
    public function getStates()
    {
        try {
            $states = StateService::getStates();
            if ($states) {
                $response = response()->Success(trans('messages.common_msg.data_found'), $states);
            } else {
                $response = response()->Error(trans('messages.common_msg.no_data_found'));
            }
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Get(
     *      path="/v1/update-firebase-chat",
     *      operationId="update-firebas-chat",
     *      tags={"User"},
     *      summary="Get name of states",
     *      description="Get name of states",
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
     *  )
     */
    public function updateFirebaseChat()
    {
        try {
            ini_set('max_execution_time', 0);
            FirebaseService::createAdminFirebaseChatUser();
            $response = response()->Success(trans('messages.common_msg.data_found'));
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
