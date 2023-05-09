<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Messages Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'logged_in' => 'You have successfully logged-in',
    'logged_out' => 'You have successfully logged out of HERA.',
    'email_already_exists' => 'Entered email already exist. Please use another email to complete the registration.',
    'phone_already_exists' => 'Entered mobile number already exist. Please use another mobile number to register.',
    'phone_not_exists' => 'Entered mobile number is not registered. Please check if you have entered correct mobile number.',
    'signup_success' => 'You have successfully registered on HERA!',
    'invalid_user_pass' => 'You have entered incorrect password. Please use valid credentials & try again.',
    'logout_from_other_device_on_pwd_change' => 'Your password has changed. Please login again using the updated password.',
    'invalid_user_phone' => 'Entered mobile number is not registered. Please check if you have entered correct mobile number.',
    'invalid_access_token' => 'Invalid access token.',
    'refresh_token' => 'Refresh token generated successfully.',
    'access_denied' => 'Sorry! You are not authorized to access this page.',
    'invalid_url' => 'Please enter valid url.',
    'invalid_method' => 'Please enter valid method.',
    'endpoint_not_found' => 'Endpoint not found.',
    'email_not_verify' => 'Your email address is not verified. Please verify it.',
    'MOBILE_OTP_SENT_SUCCESS'=> 'Verification code is sent successfully on your mobile number.',
    'MOBILE_OTP_SUCCESS' => 'Verification code verified sucessfully.',
    'MOBILE_OTP_FAIL' => 'You have entered invalid verification code. Please enter a valid verification code & try again.',
    'MOBILE_OTP_EXPIRED' => 'Your verification code has expired. Please send another code for verification.',
    'MOBILE_OTP_EXCEEDED_ATTEMPT' => 'You have exhausted your 5 attempts. Please try after 24 hours to verify your mobile number.',
    'INVALID_MOBILE' => 'You have entered an invalid mobile number. Please try again with a valid number.',
    'PHONE_NO_LIMIT' => 'Mobile number should be of 10 digit. Please check again.',
    'common_msg' =>[
        'something_went_wrong' => 'Something went wrong.',
        'data_found' => 'Data found.',
        'no_data_found' => 'No data found.',
        'data_deleted' => 'Media deleted successfully!',
    ],
    'request_validation' => [
        'error_msgs' =>[
            'email_unique' => 'Entered email already exist. Please use another email to complete the registration.',
            'pass_regex' => 'Password should start with an alphabet, should be 8 to 20 characters long and contain atleast 1 numeric digit, 1 special character, 1 uppercase and 1 lowercase.',
            'pro_pic_max' => 'You have uploaded an image of more than 5MB size. Please re-upload a smaller size image.',
            'pro_doc_max' => 'You have uploaded an document of more than 5MB size. Please re-upload a smaller size document.',
            'state_id_array_max_three' => 'You can select maximum 3 states.',
            'state_id_exists' => 'You have selected an invalid state. Please select a valid state from the list.',
            'image_required_without' => 'Either video or image is required.',
            'current_password_req' => 'Please enter current password.',
            'new_password_req' => 'Please enter a new password.',
            'confirm_password_req' => 'Please enter the new password to confirm.',
            'code_exists' => 'You have entered an invalid verification code. Please enter a valid verification code to verify.'
        ],
    ],
    'register' => [
        'success' => 'You have successfully registered on HERA!',
        'profile_success' => 'Your profile details have been saved successfully!',
        'preferences_save_success' => 'You have set your preferences successfully!',
        'attributes_save_success' => 'Your attributes have been saved successfully!',
        'gallery_save_success' => 'You have successfully created your gallery!',
        'gallery_save_old_file_error' => 'No such file exists to update.',
        'gallery_save_only_one_at_a_time' => 'You can upload either image or video at a time.',
        'gallery_id_not_found' => 'No such gallery item exists.',
        'gallery_data_delete_success' => 'Media deleted successfully!',
        'gallery_max_image_upload' => 'You can upload only 6 images into gallery.',
        'gallery_max_video_upload' => 'You can upload only 1 video into gallery.',
        'device_saved' => 'Device data has been saved successfully.',
    ],
    'profile_match' => [
        'request_sent' => 'Match request sent to :name successfully!',
        'request_approved' => 'It\'s a match. Please wait for the intended parent to initiate the conversation.',
        'request_rejected' => 'Match request rejected successfully!',
    ],
    'profile_update' => [
        'image' => 'Display picture updated successfully!',
        'profile_data' => 'Profile updated successfully!'
    ],
    'chat' => [
        'feedback' => [
            'skip' => 'Skipped',
            'saved' => 'Thank you for sharing your feedback.',
        ],
        'nextSteps' => 'Next steps save successfully!',
        'nextSteps_exits' => 'You have already created request.',
    ],
    'subscription_created' => 'Subcription created successfully.',
    'reset_password_invalid_user' => 'Entered user id does not exist. Please check & try again.',
    'account_deactive' => 'Account deactivated successfully!',
    'account_active' => 'Account re-activated successfully!',
    'verify_email_send_success' => 'Verification code sent successfully on your email!',
    'invalid_email_otp' => 'You have entered invalid verification code. Please enter a valid verification code & try again.',
    'email_verified_success' => 'Email verified sucessfully!',
    'email_already_verified' => 'Email already verified.',
    'password_does_not_match' => 'Please enter correct password & try again.',
    'password_matched' => 'Password matched successfully.',
    'account_delete_success' => 'Account deleted successfully!',
    'user_account_deleted' => 'Your account has been deleted and no longer available.',
    'user_account_deactivated_by_admin' => 'Your account has been deactivated by admin. Please contact HERA Support to get it re-activated.',
    'user_account_deleted_by_admin' => 'Your account has been deleted by admin. Please contact HERA support.',
    'user_account_imported_by_admin' => 'Your account has been deactivated by admin. Please contact HERA support.',
    'admin' => [
        'invalid_credentail' => 'Please login with valid credentials.',
        'account_deactive' => 'User deactivated successfully!',
        'account_active' => 'User activated successfully!',
        'account_delete' => 'User deleted successfully!',
        'reply_sent' => 'Reply sent successfully!',
    ],
    'change_password' => [
        'invalid_authentication' => 'Invalid authentication.',
        'old_password_does_not_match' => 'You have entered incorrect current password. Please use valid password & try again.',
        'new_password_can_not_be_old_password' => 'You cannot use your current password as new password. Please set another one.',
        'change_password_success' => 'Password changed successfully!',
    ],
    'enquiry' => [
        'success' => 'Query submitted successfully. HERA support will reach out to you via email.',
    ],
    'sent_push_notification' => 'Notification sent successfully.',
    'user_report' => 'User reported successfully!',
    'already_user_reported' => 'This user has already been reported.',
    'notify_status_active' => 'Push notifications turned on successfully. Now, you will get notified for all the future events.',
    'notify_status_in_active' => 'Push notifications turned off successfully!',
    'subscription_expire' => 'Your subscription has expired. Please renew your subscription now to use this feature.',
    'trial_subscription_expire' => 'Your trial period is over. Please subscribe now to use this feature.',
    'bulk_import' => [
        'success' => 'The Users Import has begun and we will notify you via email once the sheet has been successfully imported.',
        'file_type' => 'Only csv and excel files are allowed to be uploaded.',
        'file_max' => 'You have uploaded an document of more than 50MB size. Please upload a smaller size document.',
    ],
    'payment' => [
        'payment_request' => 'Payment request sent successfully!',
        'request_rejected' => 'Payment request rejected successfully!',
        'invalid_request' => 'Payment request is not belongs to this account.',
        'save_kyc' => 'Kyc details save successfully!',
        'payment_transfer' => 'Payment amount transfer successfully!',
    ],
];
