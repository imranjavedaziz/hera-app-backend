<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ReportUserRequest;
use App\Helpers\AuthHelper;
use App\Models\ReportUser;
use DB;

class ReportUserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/v1/report-user",
     *     description="Report a user",
     *     operationId="report-a-user",
     *     tags={"Report User"},
     *     summary="Report a user",
     *     description="Report a user on MBC portal.",
     *     @OA\RequestBody(
     *        required = true,
     *        description = "",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="to_user_id",
     *                type="integer",
     *                example=3
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

    public function reportUser(ReportUserRequest $request)
    {
        try {
            $input = $request->all();
            $input[FROM_USER_ID] = AuthHelper::authenticatedUser()->id;
            $reportUser = ReportUser::where($input)->first();
            if ($reportUser !== null) {
                return response()->Error(trans(trans('messages.already_user_reported')));
            }
            $reportUser = ReportUser::create($input);
            if ($reportUser) {
                DB::commit();
                $response = response()->Success(trans('messages.user_report'), $reportUser);
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
