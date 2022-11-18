<?php

//define number
define('ZERO', 0);
define('ONE', 1);
define('TWO', 2);
define('THREE', 3);
define('FOUR', 4);
define('FIVE', 5);
define('SIX', 6);
define('SEVEN', 7);
define('EIGHT', 8);
define('NINE', 9);
define('TEN', 10);

//define status number 
define('ACTIVE', 1);  // Approved
define('INACTIVE', 2);// Inactive
define('PENDING', 3); // Pending
define('REJECTED', 4); // Rejected or Unapproved or Canceled
define('DELETED', 5); // Deleted

//define role number 
define('ADMIN', 1);
define('PARENTS_TO_BE', 2);
define('SURROGATE_MOTHER', 3);
define('EGG_DONER', 4);
define('SPERM_DONER', 5);

//define keys
define('SUCCESS', 'success');
define('DATA', 'data');
define('MESSAGE', 'message');
define('TOKEN', 'token');
define('ERRORS', 'errors');
define('AS', 'as');
define('DESC', 'desc');
define('ASC', 'asc');
define('NULLABLE', 'nullable');
define('MIDDLEWARE', 'middleware');
define('AUTHORIZATION', 'Authorization');
define('RESULTS', 'results');
define('BASIC', 'basic');
define('DASHBOARD_PAGE_LIMIT', '10');
define('ADMIN_PAGE_LIMIT', '10');
define('PTB', '1');
define('DONAR', '2');
define('LIKE', 'like');
define('MATCH_REQUEST', 'match_request');

//request status code
define('HTTP_DELETED_ACCOUNT', 402);

//request validation variables
define('REQUIRED', 'required');
define('SOMETIMES', 'sometimes');
define('EMAIL_MAX_LENGTH', 'max:60');
define('BAIL', 'bail');
define('NUMERIC', 'numeric');
define('STRING', 'string');
define('UNIQUE', 'unique');
define('IMAGE_MIMES', 'mimes:jpeg,png');
define('VIDEO_MIMES', 'mimes:mp4,ogx,oga,ogv,ogg,webm');
define('MAX_LIMIT_FIVE_THOUSAND', 'max:5000');
define('MIN_ONE', 'min:1');
define('IN_ZERO_ONE', 'in:0,1');

define('EMAIL_CODE', 'email_code');
define('ARRAYY', 'array');
define('IDS', 'ids');
define('ALL_IDS', 'ids_array.*');
define('IDS_ARRAY', 'ids_array');

define('UNIQUE_USERS_EMAIL', 'unique:users,email');
define('UNIQUE_USERS_PHONE', 'unique:users,phone_no');

define('EXISTS_USERS_ID', 'exists:users,id');
define('EXISTS_GENDERS_ID', 'exists:genders,id');
define('EXISTS_SEXUAL_ORIENTATIONS_ID', 'exists:sexual_orientations,id');
define('EXISTS_RELATIONSHIP_STATUSES_ID', 'exists:relationship_statuses,id');
define('EXISTS_ROLES_ID', 'exists:roles,id');
define('EXISTS_STATE_ID', 'exists:states,id');
define('EXISTS_HEIGHTS_ID', 'exists:heights,id');
define('EXISTS_RACES_ID', 'exists:races,id');
define('EXISTS_ETHNICITIES_ID', 'exists:ethnicities,id');
define('EXISTS_WEIGHTS_ID', 'exists:weights,id');
define('EXISTS_HAIR_COLOURS_ID', 'exists:hair_colours,id');
define('EXISTS_EYE_COLOURS_ID', 'exists:eye_colours,id');
define('EXISTS_EDUCATION_ID', 'exists:education,id');
define('EXISTS_PROFILE_MATCH_ID', 'exists:profile_matches,id');
define('EXISTS_EMAIL_VERIFICATION_ID', 'exists:email_verifications,otp');
define('EXISTS_DONER_GALLERIES_ID', 'exists:doner_galleries,id,user_id');

define('NAME_REGEX', 'regex:/^[a-zA-Z\s\. ]+$/');
define('EMAIL_REGEX', 'regex:/^([a-zA-Z0-9\+_\-]+)(\.[a-zA-Z0-9\+_\-]+)*@([a-zA-Z0-9\-]+\.)+[a-zA-Z]{2,6}$/');
define('PASSWORD_REGEX', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$!%^*?&])[A-Za-z\d@#$!%^*?&]{8,}$/');

define('REQUIRED_WITHOUT_VIDEO', 'required_without:video');
define('REQUIRED_WITHOUT_IMAGE', 'required_without:image');

//Request Validation Error Custom Messages Variables
define('EMAIL_UNIQUE', 'email.unique');
define('PASS_REGEX', 'password.regex');
define('PRO_PIC_MAX', 'profile_pic.max');
define('TO_USER_ID_UNIQUE', 'to_user_id.unique');
define('STATE_IDS_ELEMENTS_EXISTS', 'state_ids.*.exists');
define('IMAGE_REQUIRED_WITHOUT', 'image.required_without');
define('CURRENT_PASSWORD_REQ', 'current_password.required');
define('NEW_PASSWORD_REQ', 'new_password.required');
define('CONFIRM_PASSWORD_REQ', 'confirm_password.required');
define('CODE_EXISTS', 'code.exists');
define('ALL_IDS_EXISTS', 'ids_array.*.exists');

// define migration text
define('DATETIME', 'datetime');
define('CASCADE', 'cascade');

define('ROLE_COMMENT', '2 => Parents To Be, 3 => Surrogate Mother, 4 => Egg User, 5=>Sperm Doner');
define('REGISTRATION_STEP_COMMENT', '1 => Registration Form Filled, 2 => registration and profile form Fille, 3 => All step done');
define('USER_MATCHES_STATUS_COMMENT', '1 => Pending for approval, 2 => Approved and matched, 3 => Rejected by PTB, 4=> Rejected by Doner');
define('USER_SUBSCRIPTION_STATUS_COMMENT', '0 => disabled, 1 => enalbed');
define('FEEDBACK_LIKE_COMMENT', '0=>thumbs up, 1=>thumbs down');
define('FEEDBACK_SKIP_COMMENT', '0=>not skip, 1=>skip');
define('DEACTIVATED_BY_COMMENT', '0 => Activated, 1 => deactivated by admin, 2 => deactivated by user');
define('DELETED_BY_COMMENT', '0 => Nt deleted, 1 => deleted by admin, 2 => deleted by user');

define('USE_UPDATE_CURRENT_TIME', 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');

//define status text 
define('ACTIVE_STATUS', 'Active');
define('INACTIVE_STATUS', 'InActive');
define('PENDING_STATUS', 'Pending');
define('REJECTED_STATUS', 'Rejected');
define('DELETED_STATUS', 'Deleted');

//common table column
define('ID', 'id');
define('STATUS', 'status');
define('CREATED_AT', 'created_at');
define('UPDATED_AT', 'updated_at');
define('DELETED_AT', 'deleted_at');

//define user table column
define('USERS', 'users');
define('PROFILE_PIC', 'profile_pic');
define('ROLE_ID', 'role_id');
define('USERNAME', 'username');
define('FIRST_NAME', 'first_name');
define('MIDDLE_NAME', 'middle_name');
define('LAST_NAME', 'last_name');
define('EMAIL', 'email');
define('EMAIL_VERIFIED', 'email_verified');
define('EMAIL_VERIFIED_AT', 'email_verified_at');
define('DOB', 'dob');
define('PASSWORD', 'password');
define('STATUS_ID', 'status_id');
define('REGISTRATION_STEP', 'registration_step');
define('SUBSCRIPTION_STATUS', 'subscription_status');
define('RECENT_ACTIVITY', 'recent_activity');
define('REMEMBER_TOKEN', 'remember_token');
define('REASON_ID', 'reason_id');
define('DEACTIVATED_BY', 'deactivated_by');
define('DELETED_BY', 'deleted_by');

//define password_resets table column
define('PASSWORD_RESETS', 'password_resets');

//define roles table column
define('ROLES', 'roles');

//define statuses table column
define('STATUSES', 'statuses');
define('NAME', 'name');

//define phone varification table column
define('PHONE_VERIFICATIONS', 'phone_verifications');
define('COUNTRY_CODE', 'country_code');
define('PHONE_NO', 'phone_no');
define('OTP', 'otp');
define('MAX_ATTEMPT', 'max_attempt');
define('OTP_BLOCK_TIME', 'otp_block_time');

//define state table column
define('STATES', 'states');
define('CODE', 'code');

//define city table column
define('CITIES', 'cities');
define('STATE_ID', 'state_id');

//define gender table column
define('GENDERS', 'genders');

//define sexual orientation table column
define('SEXUAL_ORIENTATIONS', 'sexual_orientations');

//define Relationship status table column
define('RELATIONSHIP_STATUSES', 'relationship_statuses');

//define race table column
define('RACES', 'races');

//define ethnicity table column
define('ETHNICITIES', 'ethnicities');

//define height table column
define('HEIGHTS', 'heights');

//define weight table column
define('WEIGHTS', 'weights');

//define hair colour table column
define('HAIR_COLOURS', 'hair_colours');

//define eye colour table column
define('EYE_COLOURS', 'eye_colours');

//define education table column
define('EDUCATION', 'education');

//define user profile table column
define('USER_PROFILES', 'user_profiles');
define('USER_ID', 'user_id');
define('GENDER_ID', 'gender_id');
define('SEXUAL_ORIENTATION_ID', 'sexual_orientations_id');
define('RELATIONSHIP_STATUS_ID', 'relationship_status_id');
define('STATE', 'state');
define('OCCUPATION', 'occupation');
define('BIO', 'bio');
define('GENDER', 'gender');

//define Location table column
define('LOCATIONS', 'locations');
define('ADDRESS', 'address');
define('CITY_ID', 'city_id');
define('ZIPCODE', 'zipcode');
define('LATITUDE', 'latitude');
define('LONGITUDE', 'longitude');

//define parents preferences table column
define('PARENTS_PREFERENCES', 'parents_preferences');
define('ROLE_ID_LOOKING_FOR', 'role_id_looking_for');
define('AGE', 'age');
define('HEIGHT', 'height');
define('RACE', 'race');
define('ETHNICITY', 'ethnicity');
define('HAIR_COLOUR', 'hair_colour');
define('EYE_COLOUR', 'eye_colour');

//define doner attribute table column
define('DONER_ATTRIBUTES', 'doner_attributes');
define('HEIGHT_ID', 'height_id');
define('RACE_ID', 'race_id');
define('MOTHER_ETHNICITY_ID', 'mother_ethnicity_id');
define('FATHER_ETHNICITY_ID', 'father_ethnicity_id');
define('WEIGHT_ID', 'weight_id');
define('HAIR_COLOUR_ID', 'hair_colour_id');
define('EYE_COLOUR_ID', 'eye_colour_id');
define('EDUCATION_ID', 'education_id');
define('WEIGHT', 'weight');

//define doner gallery table column
define('DONER_GALLERIES', 'doner_galleries');
define('FILE_NAME', 'file_name');
define('FILE_URL', 'file_url');
define('FILE_TYPE', 'file_type');

// date formats
define('YMD_FORMAT', 'Y-m-d');
define('DATE_TIME', "Y-m-d H:i:s");

//lang messages constant
define('LANG_SOMETHING_WRONG', 'messages.common_msg.something_went_wrong');
define('LANG_DATA_FOUND', 'messages.common_msg.data_found');
define('LANG_DATA_NOT_FOUND', 'messages.common_msg.no_data_found');

//define profile matches table column
define('PROFILE_MATCHES', 'profile_matches');
define('FROM_USER_ID', 'from_user_id');
define('TO_USER_ID', 'to_user_id');

//define matched statuses 
define('PENDING_FOR_APPROVAL', 1);
define('APPROVED_AND_MATCHED', 2);
define('REJECTED_BY_PTB', 3);
define('REJECTED_BY_DONAR', 4);

//define profile matches relationship and function constants
define('FROMUSER', 'fromUser');
define('TOUSER', 'toUser');

define('LOCATION_VALUE', 45);
define('AGE_VALUE', 45);
define('RACE_VALUE', 45);
define('ETHNICITY_VALUE', 45);
define('HEIGHT_VALUE', 45);
define('HAIR_COLOUR_VALUE', 45);
define('EYE_COLOUR_VALUE', 45);
define('EDUCATION_VALUE', 45);
define('CRITERIA_WEIGHT', 360);
define('MATCH_VALUE', 'match_value');

//define user relationship and function constants
define('ROLE', 'role');
define('USERPROFILE', 'userProfile');
define('DONERATTRIBUTE', 'donerAttribute');
define('LOCATION', 'location');
define('PARENTSPREFERENCE', 'parentsPreference');
define('DONERPHOTOGALLERY', 'donerPhotoGallery');
define('DONERVIDEOGALLERY', 'donerVideoGallery');
define('DONER_PHOTO_GALLERY', 'doner_photo_gallery');
define('DONER_VIDEO_GALLERY', 'doner_video_gallery');
define('CURRENT_PASSWORD', 'current_password');
define('NEW_PASSWORD', 'new_password');
define('CONFIRM_PASSWORD', 'confirm_password');
define('CONFIRM_NEW_PASSWORD', 'confirm_new_password');

//define doner attributes relationship and function constants
define('MOTHER_ETHNICITY', 'mother_ethnicity');
define('FATHER_ETHNICITY', 'father_ethnicity');
define('AS_CONNECT', ') AS ');

//define user profile relationship and function constants
define('SEXUAL_ORIENTATION', 'sexual_orientation');
define('RELATIONSHIP_STATUS', 'relationship_status');

define('LOCATION_PREFERENCE', '1,2');
define('AGE_PREFERENCE', '21-28, 28-35');
define('RACE_PREFERENCE', '2,3');
define('ETHNICITY_PREFERENCE', '2,3');
define('HEIGHT_PREFERENCE', '60-70');
define('HAIR_COLOUR_PREFERENCE', '1,2');
define('EYE_COLOUR_PREFERENCE', '1,2');
define('EDUCATION_PREFERENCE', '1,2');

// define doner gallery relationship and function constants
define('OLD_FILE_NAME', 'old_file_name');
define('FILE', 'file');
define('MIME', 'mime');
define('VIDEO', 'video');
define('IMAGE', 'image');

//define subscription plan table column
define('SUBSCRIPTION_PLANS', 'subscription_plans');
define('PRICE', 'price');
define('INTERVAL', 'interval');
define('INTERVAL_COUNT', 'interval_count');
define('DESCRIPTION', 'description');
define('IOS_PRODUCT', 'ios_product');
define('ANDROID_PRODUCT', 'android_product');
define('FOR_WHOM', 'for_whom');

//define subscription table column
define('SUBSCRIPTION_PLAN_ID', 'subscription_plan_id');
define('CURRENT_PERIOD_START', 'current_period_start');
define('CURRENT_PERIOD_END', 'current_period_end');
define('SUBSCRIPTION_ID', 'subscription_id');
define('ORIGINAL_TRANSACTION_ID', 'original_transaction_id');
define('PRODUCT_ID', 'product_id');
define('PURCHASE_TOKEN', 'purchase_token');
define('DEVICE_TYPE', 'device_type');
define('CANCELED_AT', 'canceled_at');
define('MAIL_STATUS', 'mail_status');

//define ios webhook variable
define('NOTIFICATION_TYPE', 'notification_type');
define('AUTORENEW_STATUS', 'autorenew_status');
define('START_DATE', 'start_date');
define('IOS', 'ios');
define('ANDROID', 'android');

//define android webhook variable
define('SUBSCRIPTION_RENEWED', 2);
define('SUBSCRIPTION_CANCELED', 3);
define('SUBSCRIPTION_PURCHASED', 4);
define('SUBSCRIPTION_EXPIRED', 13);

//define payment table column
define('PAYMENT', 'payments');
define('PAYMENT_FOR', 'payment_for');
define('PAYMENT_ID', 'payment_id');
define('COST', 'cost');
define('PAYMENT_GATEWAY', 'payment_gateway');

//define subscription status
define('SUBSCRIPTION_DISABLED', 0);
define('SUBSCRIPTION_ENABLED', 1);
define('SUBSCRIPTION_TRIAL', 2);

// define doner dashboard relationship and function constants
define('KEYWORD', 'keyword');
define('STATE_IDS', 'state_ids');
define('STATE_IDS_ARRAY', 'state_ids_array');
define('STATE_IDS_ELEMENTS', 'state_ids_array.*');

//define notification table column
define('RECIPIENT_ID', 'recipient_id');
define('SENDER_ID', 'sender_id');
define('TITLE', 'title');
define('NOTIFY_TYPE', 'notify_type');
define('READ_AT', 'read_at');

//define device registration table column
define('DEVICE_REGISTRATIONS', 'device_registrations');
define('DEVICE_ID', 'device_id');
define('DEVICE_TOKEN', 'device_token');

// Notification Vairables 
define('PROFILE_MATCH_ID', 'profile_match_id');
define('NOTIFICATION', 'notification');

define('SENT_REQUEST', 'sent_request');
define('APPROVED_REQUEST', 'approved_request');
define('REJECTED_REQUEST', 'rejected_request');

//define feedbacks table column
define('FEEDBACK', 'feedback');
define('IS_SKIP', 'is_skip');

// define Email verification table column
define('EMAIL_VERIFICATIONS', 'email_verifications');

//define enquiry form table column
define('ENQUIRY_FORMS', 'enquiry_forms');
define('ENQUIRING_AS', 'enquiring_as');
define('ADMIN_REPLY', 'admin_reply');
define('REPLIED_AT', 'replied_at');
define('USER_TIMEZONE', 'user_timezone');
define('TIMEZONE', 'timezone');

// define enquiry form relationship and function constants
define('USER', 'user');

//Admin 
define('ADMIN_NAME', 'Admin');
define('ADMIN_PHONE', '1111111111');

// Admin inquiry
define('INQUIRY', 'inquiry');
define('INQUIRIES', 'inquiries');
define('UTC', "UTC");

// ios receipt data
define('LATEST_RECEIPT_INFO', "latest_receipt_info");
define('TRANSACTION_ID', "transaction_id");
define('PURCHASE_DATE', "purchase_date");
define('EXPIRES_DATE', "expires_date");
