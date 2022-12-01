<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\EnquiryRequest;
use DB;
use App\Helpers\AuthHelper;
use Facades\{
    App\Services\UserEnquiryService,
};
use App\Models\User;

class EnquiryController extends Controller
{
    /**
     * @OA\Get(
     *      path="/v1/roles",
     *      operationId="roles",
     *      tags={"User"},
     *      summary="Get roles",
     *      description="Get roles",
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
    public function getRoles()
    {
        try {
            $roles = UserEnquiryService::getRoles();
            if ($roles) {
                $response = response()->Success(trans('messages.common_msg.data_found'), $roles);
            } else {
                $response = response()->Error(trans('messages.common_msg.no_data_found'));
            }
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Post(
     *     path="/v1/enquiry",
     *     description="User enquiry",
     *     operationId="user-enquiry",
     *     tags={"User"},
     *     summary="User enquiry",
     *     description="User enquiry for MBC portal.",
     *     @OA\RequestBody(
     *        required = true,
     *        description = "User profile enquiry",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="user_timezone",
     *                type="string",
     *                example="Asia/Kolkata"
     *             ),
     *             @OA\Property(
     *                property="name",
     *                type="string",
     *                example="Xyz"
     *             ),
     *             @OA\Property(
     *                property="email",
     *                type="string",
     *                example="xyz@yopmail.com"
     *             ),
     *             @OA\Property(
     *                property="country_code",
     *                type="string",
     *                example="+1"
     *             ),
     *             @OA\Property(
     *                property="phone_no",
     *                type="string",
     *                example="1234567890"
     *             ),
     *             @OA\Property(
     *                property="enquiring_as",
     *                type="integer",
     *                example=2
     *             ),
     *             @OA\Property(
     *                property="message",
     *                type="string",
     *                example="abcd"
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
     *  )
     */
    public function enquiry(EnquiryRequest $request)
    {
        try {
            DB::beginTransaction();
            $enquiry = UserEnquiryService::enquiry($request->all());
            if ($enquiry) {
                DB::commit();
                $response = response()->Success(trans('messages.enquiry.success'), $enquiry);
            } else {
                DB::rollback();
                $response = response()->Error(trans(LANG_SOMETHING_WRONG));
            }
        } catch (\Exception $e) {
            DB::rollback();
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
