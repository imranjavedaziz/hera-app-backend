<?php

namespace App\Services;

use App\Models\Feedback;
use DB;

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
}
