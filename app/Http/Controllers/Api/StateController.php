<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Helpers\AuthHelper;
use Facades\{
    App\Services\StateService,
    App\Services\FirebaseService,
    App\Services\UserRegisterService,
};
use DB;
use App\Http\Requests\UploadDocumentRequest;

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

    /**
     * @OA\Post(
     *      path="/v1/upload-document",
     *      operationId="upload-doc",
     *      tags={"Upload"},
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
     *                         )
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
     *  )
     */
    public function uploadDocument(UploadDocumentRequest $request)
    {
        try {
            $uploadDocument = UserRegisterService::uploadFile($request->all(),'chat/documents');
            if ($uploadDocument) {
                $response = response()->Success(SUCCESS, $uploadDocument);
            } else {
                $response = response()->Error(trans(LANG_SOMETHING_WRONG));
            }
        } catch (\Exception $e) {
            $response = response()->Error($e->getMessage());
        }
        return $response;
    }
}
