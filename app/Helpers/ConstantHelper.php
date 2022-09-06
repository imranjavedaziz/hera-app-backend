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
define('MESSAGE', 'message');
define('TOKEN', 'token');
define('ERRORS', 'errors');
define('AS', 'as');
define('DESC', 'desc');
define('ASC', 'asc');
define('REQUIRED', 'required');
define('NUMERIC', 'numeric');
define('NULLABLE', 'nullable');
define('MIDDLEWARE', 'middleware');
define('AUTHORIZATION', 'Authorization');
define('RESULTS', 'results');
define('BASIC', 'basic');
define('EMAIL', 'email');
define('PASSWORD', 'password');
define('STATUS', 'status');
define('UPDATED_AT', 'updated_at');

//define status text 
define('ACTIVE_STATUS', 'Active');
define('INACTIVE_STATUS', 'InActive');
define('PENDING_STATUS', 'Pending');
define('REJECTED_STATUS', 'Rejected');
define('DELETED_STATUS', 'Deleted');

//define statuses table column
define('NAME', 'name');

//define phone varification table column
define('COUNTRY_CODE', 'country_code');
define('PHONE_NO', 'phone_no');
define('OTP', 'otp');
define('MAX_ATTEMPT', 'max_attempt');
define('OTP_BLOCK_TIME', 'otp_block_time');
