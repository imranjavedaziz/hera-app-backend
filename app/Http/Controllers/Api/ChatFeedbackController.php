<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\AuthHelper;
use Facades\{
    App\Services\ChatFeedbackService,
};
use App\Http\Requests\SaveChatFeedbackRequest;
use App\Http\Requests\NextStepsRequest;
use DB;

class ChatFeedbackController extends Controller
{
    /**
     * @OA\Post(
     *      path="/v1/chat-feedback",
     *      operationId="chat-feedback",
     *      tags={"User"},
     *      summary="chat-feedback",
     *      description="chat-feedback",
     *      @OA\RequestBody(
     *        required = true,
     *        description = "Save Chat Feedbacks.. <br> like : 0=> thumbs down or skip case, 1=> thumbsup",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="like",
     *                type="integer",
     *                example=1
     *             ),
     *             @OA\Property(
     *                property="recipient_id",
     *                type="integer",
     *                example=0
     *             ),
     *             @OA\Property(
     *                property="is_skip",
     *                type="integer",
     *                example=0
     *             ),
     *         ),
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
    public function saveChatFeedback(SaveChatFeedbackRequest $request)
    {
        try {
            DB::beginTransaction();
            $save_chat_feedback = ChatFeedbackService::saveChatFeedback(AuthHelper::authenticatedUser()->id, $request->all());
            if($save_chat_feedback[SUCCESS]){
                DB::commit();
                $response = response()->Success(trans($save_chat_feedback[MESSAGE]), $save_chat_feedback[DATA]);
            }else {
                $response = response()->Error(trans(LANG_SOMETHING_WRONG));
            }
        } catch (\Exception $e) {
            DB::rollback();
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Post(
     *     path="/v1/next-steps",
     *     description="CTA for next steps",
     *     operationId="next-steps",
     *     tags={"User"},
     *     summary="CTA for next steps",
     *     description="CTA for next steps",
     *     @OA\RequestBody(
     *        required = true,
     *        description = "Doner user id",
     *        @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="to_user_id",
     *                type="integer",
     *                example=3
     *             )
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
    public function saveNextSteps(NextStepsRequest $request)
    {
        try {
            DB::beginTransaction();
            $saveNextSteps = ChatFeedbackService::saveNextSteps(AuthHelper::authenticatedUser()->id, $request->to_user_id);
            if($saveNextSteps[SUCCESS]){
                DB::commit();
                $response = response()->Success(trans($saveNextSteps[MESSAGE]), $saveNextSteps[DATA]);
            }else {
                $response = response()->Error(trans(LANG_SOMETHING_WRONG));
            }
        } catch (\Exception $e) {
            DB::rollback();
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
