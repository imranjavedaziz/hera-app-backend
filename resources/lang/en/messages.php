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

    'logged_in' => 'You have successfully logged in.',
    'logged_out' => 'You have successfully logged out of HERA.',
    'email_already_exists' => 'The email address you entered already exists. Please use a different email address to complete the registration.',
    'phone_already_exists' => 'The mobile number you entered has already been registered. Please try again with a different number.',
    'phone_not_exists' => 'The mobile number you entered is not registered. Please check if you have entered the correct number.',
    'signup_success' => 'You have successfully registered on HERA.',
    'invalid_user_pass' => 'Oops! Looks like your password didn\'t match. Please try again.',
    'logout_from_other_device_on_pwd_change' => 'Your password has been changed. Please login again using the updated password.',
    'invalid_user_phone' => 'The mobile number you entered is not registered. Please check if you have entered the correct number.',
    'invalid_access_token' => 'Invalid access token.',
    'refresh_token' => 'Refresh token generated successfully.',
    'access_denied' => 'Sorry! You are not authorized to access this page.',
    'invalid_url' => 'Please enter valid url.',
    'invalid_method' => 'Please enter valid method.',
    'endpoint_not_found' => 'Endpoint not found.',
    'email_not_verify' => 'Your email address is not verified. Please verify.',
    'MOBILE_OTP_SENT_SUCCESS'=> 'A verification code has been sent to your phone. Please enter the code below to verify your account.',
    'MOBILE_OTP_SUCCESS' => 'Your verification code has been verified. You may now proceed.',
    'MOBILE_OTP_FAIL' => 'The verification code you entered is not correct. Please check your code and try again.',
    'MOBILE_OTP_EXPIRED' => 'The verification code you entered is expired. Please request a new code and try again.',
    'MOBILE_OTP_EXCEEDED_ATTEMPT' => 'You have exhausted your 5 attempts to verify your mobile number. Please try again after 24 hours.',
    'INVALID_MOBILE' => 'The mobile number you entered is not in our system. Please enter a valid mobile number.',
    'PHONE_NO_LIMIT' => 'Your mobile number must be 10 digits long. Please enter a valid mobile number.',
    'common_msg' =>[
        'something_went_wrong' => 'Something went wrong.',
        'data_found' => 'Data found.',
        'no_data_found' => 'No data found.',
        'data_deleted' => 'You have successfully deleted all of your unwanted media.',
    ],
    'request_validation' => [
        'error_msgs' =>[
            'email_unique' => 'The email address you entered already exists. Please use another email address to complete the registration.',
            'pass_regex' => 'Your password must start with an alphabet, be 8 to 20 characters long, and contain at least 1 numeric digit, 1 special character, 1 uppercase, and 1 lowercase letter.',
            'pro_pic_max' => 'The image you uploaded is too large. The maximum file size is 5MB. Please re-upload a smaller image.',
            'pro_doc_max' => 'The PDF you have uploaded is too large. The maximum file size allowed is 5MB. Please re-upload a smaller file.',
            'state_id_array_max_three' => 'You can only select a maximum of 3 states.',
            'state_id_exists' => 'The state you have selected is invalid. Please select a valid state from the list.',
            'image_required_without' => 'You must upload either a video or an image.',
            'current_password_req' => 'Please enter your current password to continue.',
            'new_password_req' => 'Please enter a new password.',
            'confirm_password_req' => 'Please enter your new password again to confirm.',
            'code_exists' => 'Your verification code didn\'t match. Please double-check the code you received and try again.'
        ],
    ],
    'register' => [
        'success' => 'You have successfully registered on HERA.',
        'profile_success' => 'Your profile details have been saved.',
        'preferences_save_success' => 'Your preferences have been set successfully.',
        'attributes_save_success' => 'Your attributes have been saved successfully.',
        'gallery_save_success' => 'You have successfully created your gallery.',
        'gallery_save_old_file_error' => 'The file you are trying to update does not exist.',
        'gallery_save_only_one_at_a_time' => 'You can\'t upload both an image and a video at the same time.',
        'gallery_id_not_found' => 'The gallery item you requested does not exist.',
        'gallery_data_delete_success' => 'You have successfully deleted all of your unwanted media.',
        'gallery_max_image_upload' => 'You can upload only 6 images into gallery.',
        'gallery_max_video_upload' => 'You can upload only 1 video into gallery.',
        'device_saved' => 'Device data has been saved successfully.',
    ],
    'profile_match' => [
        'request_sent' => 'You have successfully sent a match request to :name.',
        'request_approved' => 'It\'s a match. Please be patient and wait for the intended parent to initiate the conversation.',
        'request_rejected' => 'The match request has been rejected.',
    ],
    'profile_update' => [
        'image' => 'Your display picture has been updated successfully.',
        'profile_data' => 'Your profile has been updated.'
    ],
    'chat' => [
        'feedback' => [
            'skip' => 'Skipped',
            'saved' => 'Thank you for your feedback.',
        ],
        'nextSteps' => 'Thank you for showing interest in :usertype :username profile.',
        'nextSteps_exits' => 'You have already created request.',
    ],
    'subscription_created' => 'Your subscription has been successfully purchased. You will now have access to all of the premium features.',
    'reset_password_invalid_user' => 'The user ID you entered does not exist. Please check and try again.',
    'account_deactive' => 'Your account has been deactivated. You can reactivate it at any time by logging in.',
    'account_active' => 'Your account has been reactivated and is now ready for use.',
    'verify_email_send_success' => 'Verification code has been sent to your email address. Please check your email and enter the code to verify.',
    'invalid_email_otp' => 'Your verification code didn\'t match. Please double-check the code you received and try again.',
    'email_verified_success' => 'Your email has been verified successfully.',
    'email_already_verified' => 'Your email address is already verified.',
    'password_does_not_match' => 'The passwords you entered do not match. Please try again.',
    'password_matched' => 'Password matched successfully.',
    'account_delete_success' => 'Your account has been deleted. Thank you for using our service.',
    'user_account_deleted' => 'Your account has been deleted and is no longer available.',
    'user_account_deactivated_by_admin' => 'Your account has been deactivated by Admin. Please contact HERA Support to get it re-activated.',
    'user_account_deleted_by_admin' => 'Your account has been deleted by Admin. Please contact HERA Support for more information.',
    'user_account_imported_by_admin' => 'Your account has been deactivated by admin. Please contact HERA support.',
    'admin' => [
        'invalid_credentail' => 'Your username or password is not valid. Please check your credentials and try again.',
        'account_deactive' => 'User has been deactivated.',
        'account_active' => 'User has been activated.',
        'account_delete' => 'User has been deleted.',
        'reply_sent' => 'Your reply has been sent successfully!',
    ],
    'change_password' => [
        'invalid_authentication' => 'Invalid authentication.',
        'old_password_does_not_match' => 'The password you entered does not match your current password. Please try again.',
        'new_password_can_not_be_old_password' => 'Your new password must be different from your current password. Please enter a new password.',
        'change_password_success' => 'Your password has been changed. Please log in with your new password.',
    ],
    'enquiry' => [
        'success' => 'Your query has been submitted successfully. HERA support will reach out to you via email.',
    ],
    'sent_push_notification' => 'Notification sent successfully.',
    'user_report' => 'Thank you for reporting this user.',
    'already_user_reported' => 'This user has already been reported.',
    'notify_status_active' => 'Push notifications have been disabled. You will no longer receive push notifications from this app.',
    'notify_status_in_active' => 'Push notifications turned off successfully!',
    'subscription_expire' => 'Your subscription has expired. Please renew your subscription to continue using this feature.',
    'trial_subscription_expire' => 'Your trial period is over. Please subscribe to continue using this feature.',
    'bulk_import' => [
        'success' => 'Users Import started. Email notification will be sent once imported.',
        'file_type' => 'The file you are trying to upload is not a valid CSV or Excel file. Please try again with a valid file.',
        'file_max' => 'The document you have uploaded is too large. The maximum file size allowed is 50MB. Please upload a smaller file.',
    ],
    'payment' => [
        'payment_request' => 'Your payment request has been sent successfully.',
        'request_rejected' => 'Payment request declined.',
        'invalid_request' => 'The payment request you have submitted does not belong to this account. Please try again.',
        'save_kyc' => 'Your KYC details have been saved. You can now start using the Payment feature.',
        'payment_transfer' => 'Your payment has been successfully transferred.',
        'request_already_paid' => 'Payment has been marked as paid.',
    ],
    'no_active_subscription_found' => "You do not have an active subscription.",
    'subscription_canceled' => "Your subscription has been cancelled successfully. You will not be charged again.",
];
