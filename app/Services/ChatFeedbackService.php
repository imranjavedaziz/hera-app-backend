<?php

namespace App\Services;

use App\Models\Feedback;
use App\Models\CtaNextStep;
use DB;
use App\Jobs\SendNextStepsMail;
use App\Models\ChatMedia;
use App\Helpers\AuthHelper;
use App\Models\User;

class ChatFeedbackService
{
    public function saveChatFeedback($sender_id, $input)
    {
        $feedback = Feedback::where(SENDER_ID, $sender_id)->where(RECIPIENT_ID, $input[RECIPIENT_ID])->first();

        if(!$feedback){
            $feedback = new Feedback();
        }

        $feedback->like = $input[LIKE];
        $feedback->message = !empty($input[MESSAGE]) ? $input[MESSAGE] : NULL;
        $feedback->sender_id = $sender_id;
        $feedback->recipient_id = $input[RECIPIENT_ID];
        $feedback->is_skip = $input[IS_SKIP];
        if($feedback->save()){
            $message = $input[IS_SKIP] ? __('messages.chat.feedback.skip') : __('messages.chat.feedback.saved');
            return [SUCCESS => true, MESSAGE => $message, DATA => $feedback];
        }
        return [SUCCESS => false];
    }

    public function saveNextSteps($formUserId, $toUserId)
    {
        $ctaNextSteps = CtaNextStep::where(FROM_USER_ID, $formUserId)->where(TO_USER_ID, $toUserId)->first();
        if(!empty($ctaNextSteps)){
            return [SUCCESS => true, MESSAGE => __('messages.chat.nextSteps_exits'), DATA => $ctaNextSteps];
        }
        $ctaNextSteps = new CtaNextStep();
        $ctaNextSteps->from_user_id = $formUserId;
        $ctaNextSteps->to_user_id = $toUserId;
        if($ctaNextSteps->save()){
            SendNextStepsMail::dispatch($formUserId, $toUserId);
            $toUser = User::where(ID,$toUserId)->first();
            $message = __('messages.chat.nextSteps', ['usertype' => $toUser->role->name, USERNAME => $toUser->username]);
            return [SUCCESS => true, MESSAGE => $message, DATA => $ctaNextSteps];
        }
        return [SUCCESS => false];
    }

    public function saveChatMedia($uploadDocument, $input) {
        $chatMedia = new ChatMedia();
        $chatMedia->from_user_id = AuthHelper::authenticatedUser()->id;
        $chatMedia->to_user_id = $input[TO_USER_ID];
        $chatMedia->url = $uploadDocument[FILE_URL];
        if($chatMedia->save()){
            return [SUCCESS => true, DATA => $this->getFileSIze($input[FILE])];
        }
        return [SUCCESS => false];
    }

    public function getFileSIze($file) {
        $sizeInBytes = $file->getSize();
        $sizeInKB = $sizeInBytes / 1024;
        if ($sizeInKB < 1024) {
            return number_format($sizeInKB, 2) . ' KB';
        } else {
            $sizeInMB = $sizeInKB / 1024;
            return number_format($sizeInMB, 2) . ' MB';
        }
    }

    public function getChatMedia($to_user_id)  {
        $from_user_id = AuthHelper::authenticatedUser()->id;
        return ChatMedia::select(ID,FROM_USER_ID, TO_USER_ID, URL, UPDATED_AT, CREATED_AT)
        ->where(function ($query) use ($from_user_id, $to_user_id) {
            $query->where(FROM_USER_ID, $from_user_id);
            $query->where(TO_USER_ID, $to_user_id);  
        })
        ->orWhere(function ($query) use ($from_user_id, $to_user_id) {
            $query->where(FROM_USER_ID, $to_user_id);
            $query->where(TO_USER_ID, $from_user_id);  
        })->orderBY(CREATED_AT, DESC);
    }
}
