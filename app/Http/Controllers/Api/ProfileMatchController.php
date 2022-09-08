<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileMatchRequest;
use App\Helpers\AuthHelper;
use Facades\{
    App\Services\ProfileMatchService,
};
use DB;
class ProfileMatchController extends Controller
{
    /**
     * @OA\Post(
     *     path="/v1/profile-match-request",
     *     description="User profile match request",
     *     operationId="profile-match-request",
     *     tags={"User"},
     *     summary="User profile match request",
     *     description="User profile match request for MBC portal.",
     *     @OA\RequestBody(
     *        required = true,
     *        description = "status : 1 => Pending for approval, 2 => Approved and matched, 3 => Rejected by PTB, 4=> Rejected by Doner",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="to_user_id",
     *                type="integer",
     *                example=3
     *             ),
     *             @OA\Property(
     *                property="status",
     *                type="integer",
     *                example=1
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

    public function profileMatchRequest(ProfileMatchRequest $request)
    {
        try {
            DB::beginTransaction();
            $profile_match = ProfileMatchService::profileMatchRequest(AuthHelper::authenticatedUser()->id, $request->all());
            DB::commit();
            if ($profile_match[SUCCESS]) {
                $response = response()->Success($profile_match[MESSAGE], $profile_match[DATA]);
            } else {
                $response = response()->Error(trans(LANG_SOMETHING_WRONG));
            }
        } catch (\Exception $e) {
            DB::rollback();
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Get(
     *      path="/v1/get-profile-matches",
     *      operationId="get-profile-matches",
     *      tags={"User"},
     *      summary="get profile matches",
     *      description="get profile matches",
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
    public function getProfileMatches()
    {
        try {
            $profile_match = ProfileMatchService::getProfileMatches(AuthHelper::authenticatedUser()->id);
            if ($profile_match) {
                $response = response()->Success(trans(LANG_DATA_FOUND), $profile_match);
            } else {
                $response = response()->Error(trans(LANG_DATA_NOT_FOUND));
            }
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

}
