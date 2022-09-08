<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileMatchUnmatchRequest;
use DB;
use App\Helpers\AuthHelper;
use Facades\{
    App\Services\ProfileMatchUnmatchService,
};
use App\Models\User;

class ProfileMatchUnmatchController extends Controller
{
    /**
     * @OA\Post(
     *     path="/v1/profile-match-unmatch",
     *     description="User match unmatch profile",
     *     operationId="set-match-unmatch-profile",
     *     tags={"User"},
     *     summary="User match unmatch profile",
     *     description="User match unmatch profile for MBC portal.",
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

    public function profileMatchUnmatch(ProfileMatchUnmatchRequest $request)
    {
        try {
            DB::beginTransaction();
            $profile_match_unmatch = ProfileMatchUnmatchService::profileMatchUnmatch(AuthHelper::authenticatedUser()->id, $request->all());
            DB::commit();
            if ($profile_match_unmatch[SUCCESS]) {
                $response = response()->Success($profile_match_unmatch[MESSAGE], $profile_match_unmatch[DATA]);
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
     *      path="/v1/profile-match-unmatch",
     *      operationId="get-profile-match-unmatch",
     *      tags={"User"},
     *      summary="get profile match unmatch",
     *      description="get profile match unmatch",
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
            $profile_match_unmatch = ProfileMatchUnmatchService::getProfileMatches(AuthHelper::authenticatedUser()->id);
            if ($profile_match_unmatch) {
                $response = response()->Success(trans(LANG_DATA_FOUND), $profile_match_unmatch);
            } else {
                $response = response()->Error(trans(LANG_DATA_NOT_FOUND));
            }
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

}
