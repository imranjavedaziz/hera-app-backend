<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileDetailsRequest;
use DB;
use App\Helpers\AuthHelper;
use Facades\{
    App\Services\UserProfileService,
};
use App\Models\Subscription;

class UserProfileController extends Controller
{
    /**
     * @OA\Get(
     *      path="/v1/doner-profile-details",
     *      operationId="doner-profile-details",
     *      tags={"User"},
     *      summary="Get doner-profile-details",
     *      description="Get doner-profile-details",
     *      @OA\Parameter(
     *          name="user_id",
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
     *          response=302,
     *          description="Subscription Expired"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *      security={ {"bearer": {}} },
     *  )
     */
    public function getDonerProfileDetails(ProfileDetailsRequest $request)
    {
        try {
            $user = AuthHelper::authenticatedUser();
            $matchRequest = UserProfileService::profileMatchRequest($user->id, $request->user_id);
            if($user->subscription_status == SUBSCRIPTION_DISABLED && (empty($matchRequest) || (!empty($matchRequest)&& $matchRequest->status != TWO))) {
                return response()->json([DATA => []], 422);
            }
            $doner_profile_details_data = UserProfileService::getDonerProfileDetails($request->all());
            if ($doner_profile_details_data) {
                $response = response()->Success(trans(LANG_DATA_FOUND), $doner_profile_details_data);
            } else {
                $response = response()->Error(trans(LANG_DATA_NOT_FOUND));
            }
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
    /**
     * @OA\Get(
     *      path="/v1/ptb-profile-details",
     *      operationId="ptb-profile-details",
     *      tags={"User"},
     *      summary="Get ptb-profile-details",
     *      description="Get ptb-profile-details",
     *      @OA\Parameter(
     *          name="user_id",
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
    public function getPtbProfileDetails(ProfileDetailsRequest $request)
    {
        try {
            $ptb_profile_details_data = UserProfileService::getPtbProfileDetails($request->all());
            if ($ptb_profile_details_data) {
                $response = response()->Success(trans(LANG_DATA_FOUND), $ptb_profile_details_data);
            } else {
                $response = response()->Error(trans(LANG_DATA_NOT_FOUND));
            }
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
