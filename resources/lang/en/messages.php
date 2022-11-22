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

    'logged_in' => 'Logged in successfully.',
    'logged_out' => 'Logged out successfully.',
    'email_already_exists' => 'Email address is already exists.',
    'phone_already_exists' => 'Phone number is already exists.',
    'phone_not_exists' => 'Mobile number not registered. Please try again.',
    'signup_success' => 'Sign up successful!',
    'invalid_user_pass' => 'Wrong password. Please try again.',
    'invalid_user_phone' => 'Entered mobile number is not registered. Please check again.',
    'invalid_access_token' => 'Invalid access token.',
    'refresh_token' => 'Refresh token generated successfully.',
    'access_denied' => 'Sorry! You are not authorized to access this page.',
    'invalid_url' => 'Please enter valid url.',
    'invalid_method' => 'Please enter valid method.',
    'endpoint_not_found' => 'Endpoint not found.',
    'email_not_verify' => 'Your email address has been not verified, Please verify it.',
    'MOBILE_OTP_SENT_SUCCESS'=> 'OTP has been sent successfully.',
    'MOBILE_OTP_SUCCESS' => 'OTP verified sucessfully.',
    'MOBILE_OTP_FAIL' => 'Incorrect OTP. Please try again.',
    'MOBILE_OTP_EXPIRED' => 'Sorry, verification OTP has been expired.',
    'MOBILE_OTP_EXCEEDED_ATTEMPT' => 'You have exceeded the maximum number of attempts. Please try after 24 hours.',
    'INVALID_MOBILE' => 'Please use a valid mobile number.',
    'PHONE_NO_LIMIT' => 'Mobile number should be of 10 digits.',
    'common_msg' =>[
        'something_went_wrong' => 'Something went wrong.',
        'data_found' => 'Data found.',
        'no_data_found' => 'No data found.',
        'data_deleted' => 'Data deleted successfully.',
    ],
    'request_validation' => [
        'error_msgs' =>[
            'email_unique' => 'Email is already registered with us.',
            'pass_regex' => 'Password should start with an alphabet, should be 8 to 20 characters long and contain atleast 1 numeric digit, 1 special character, 1 uppercase and 1 lowercase.',
            'pro_pic_max' => 'Image size is more than 5MB.',
            'state_id_exists' => 'Selected state is invalid.',
            'image_required_without' => 'Either video or image is required.',
            'current_password_req' => 'Please enter current password.',
            'new_password_req' => 'Please enter new password.',
            'confirm_password_req' => 'Please enter confirm password.',
            'code_exists' => 'Invalid OTP.'
        ],
    ],
    'register' => [
        'success' => 'You have registered successfully!',
        'profile_success' => 'Profile saved Successfully!',
        'preferences_save_success' => 'Preferences saved successfully!',
        'attributes_save_success' => 'Attributes saved successfully!',
        'gallery_save_success' => 'Gallery saved successfully!',
        'gallery_save_old_file_error' => 'No such file exists to update.',
        'gallery_save_only_one_at_a_time' => 'You can upload either image or video at a time.',
        'gallery_id_not_found' => 'No such gallery item exists.',
        'gallery_data_delete_success' => 'Image removed from gallery.',
        'gallery_max_image_upload' => 'You can upload only 6 images into gallery.',
        'gallery_max_video_upload' => 'You can upload only 1 video into gallery.',
        'device_saved' => 'Device data has been saved successfully.',
    ],
    'profile_match' => [
        'request_sent' => 'A match request has been sent to :name.',
        'request_approved' => 'It\'s a match. Profile match request has been approved.',
        'request_rejected' => 'Profile match request gets rejected.',
    ],
    'profile_update' => [
        'image' => 'Image updated successfully.',
        'profile_data' => 'Profile updated successfully.'
    ],
    'chat' => [
        'feedback' => [
            'skip' => 'Skipped',
            'saved' => 'Feeback saved successfully.',
        ],
    ],
    'subscription_created' => 'Subcription created successfully.',
    'reset_password_invalid_user' => 'Sorry, We can\'t find a user with that id.',
    'account_deactive' => 'Account deactivated successfully!.',
    'account_active' => 'Account activated successfully!.',
    'verify_email_send_success' => 'Verify mail has been sent successfully on your email.',
    'invalid_email_otp' => 'Incorrect OTP. Please Try Again.',
    'email_verified_success' => 'Email verified sucessfully.',
    'email_already_verified' => 'Email already verified.',
    'password_does_not_match' => 'Please enter valid credentials.',
    'password_matched' => 'Password matched successfully.',
    'account_delete_success' => 'Your account has been deleted successfully!',
    'user_account_deleted' => 'Your account has been deleted and no longer available.',
    'user_account_deactivated_by_admin' => 'Your account has been deactivated by admin. Please contact admin to get it reactivated.',
    'user_account_deleted_by_admin' => 'Your account has been deleted by admin and no longer available.',
    'admin' => [
        'invalid_credentail' => 'Please login with valid credentials.',
        'account_deactive' => 'User has been deactivated.',
        'account_active' => 'User has been activated successfully.',
        'account_delete' => 'User has been deleted successfully.',
        'reply_sent' => 'You have successfully replied to this inquiry.',
    ],
    'change_password' => [
        'invalid_authentication' => 'Invalid authentication.',
        'old_password_does_not_match' => 'The current password you have entered is incorrect.',
        'new_password_can_not_be_old_password' => 'New password cannot be same as your current password.',
        'change_password_success' => 'Password changed successfully.',
    ],
    'enquiry' => [
        'success' => 'We have registered your query and will reach out to you shortly.',
    ],
    'sent_push_notification' => 'Notification sent successfully.',
];
