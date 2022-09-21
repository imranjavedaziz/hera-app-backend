<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Facades\{
    App\Services\FcmService
};
use App\Http\Requests\RegisterDeviceRequest;
use App\Helpers\AuthHelper;
use DB;

class FcmController extends Controller {

    /**
     * @OA\Post(
     *     path="/v1/register-device",
     *     description="User register device",
     *     operationId="register-device",
     *     tags={"Register User Device"},
     *     summary="User register device",
     *     description="User register device for MBC.",
     *     @OA\RequestBody(
     *        required = true,
     *        description = "Register Device",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="device_id",
     *                type="string",
     *                example="gdfgdgdftrdtr"
     *             ),
     *             @OA\Property(
     *                property="device_token",
     *                type="string",
     *                example="abbchhcgcggcgcggcgdgddjjjdxyydd"
     *             ),
     *             @OA\Property(
     *                property="device_type",
     *                type="string",
     *                example="android"
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *     ),
     *     @OA\Response(
     *          response=417,
     *          description="Expectation Failed"
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
    public function registerDevice(RegisterDeviceRequest $request) {
        try {
            DB::beginTransaction();
            $input = $request->all();
            $input[USER_ID] = AuthHelper::authenticatedUser()->id; 
            FcmService::registerDevice($input);
            DB::commit();
            $response = response()->Success(trans('messages.register.device_saved'));
        } catch (\Exception $e) {
            DB::rollback();
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

}
