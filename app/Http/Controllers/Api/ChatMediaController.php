<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Helpers\AuthHelper;
use Facades\{
    App\Services\UserRegisterService,
    App\Services\ChatFeedbackService,
};
use DB;
use App\Http\Requests\UploadDocumentRequest;
use App\Http\Requests\ChatMediaRequest;

class ChatMediaController extends Controller
{
    /**
     * @OA\Post(
     *      path="/v1/upload-document",
     *      operationId="upload-doc",
     *      tags={"Chat Media"},
     *      summary="Upload document",
     *      description="Upload document.",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 allOf={
     *                     @OA\Schema(
     *                         @OA\Property(
     *                             description="Item image/Document",
     *                             property="file",
     *                             type="string",
     *                             format="binary"
     *                         ),
     *                         @OA\Property(
     *                              description="to_user_id",
     *                              property="to_user_id",
     *                              type="integer",
     *                              example=2
     *                          ),
     *                     )
     *                 }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Request successfully completed.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=417,
     *          description="Expectation Failed"
     *      ),
     *      @OA\Response(
     *          response=409,
     *          description="Conflict",
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
    public function uploadDocument(UploadDocumentRequest $request)
    {
        try {
            $uploadDocument = UserRegisterService::uploadFile($request->all(),'chat/documents');
            if ($uploadDocument) {
                $chatMedia = ChatFeedbackService::saveChatMedia($uploadDocument, $request->all());
                $response = response()->Error(trans(LANG_SOMETHING_WRONG));
                if($chatMedia[SUCCESS]) {
                    $uploadDocument['file_size'] = $chatMedia[DATA];
                    $response = response()->Success(SUCCESS, $uploadDocument);
                }
            } else {
                $response = response()->Error(trans(LANG_SOMETHING_WRONG));
            }
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }

    /**
     * @OA\Get(
     *      path="/v1/chat-media",
     *      operationId="chat-media",
     *      tags={"Chat Media"},
     *      summary="Get chat media list",
     *      description="Get chat media list",
     *       @OA\Parameter(
     *          name="to_user_id",
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
    public function getChatMedia(ChatMediaRequest $request)
    {
        try {
            $limit = isset($request->limit) && ($request->limit > ZERO) ? $request->limit : FIFTEEN;
            $chatMedia = ChatFeedbackService::getChatMedia($request->to_user_id);
            $response = response()->Success(trans('messages.common_msg.data_found'), $chatMedia->paginate($limit));
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
