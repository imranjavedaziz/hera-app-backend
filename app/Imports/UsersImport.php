<?php
namespace App\Imports;

use App\Http\ValidationRule;
use App\Models\User;
use App\Traits\SetterDataTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendUserImportSuccessJob;
use Illuminate\Support\Facades\Hash;
use App\Jobs\CreateAdminChatFreiend;
use App\Jobs\UpdateUserNotificationSetting;
use App\Jobs\CreateStripeAccount;
use App\Jobs\CreateStripeCustomer;
use App\Helpers\CustomHelper;

class UsersImport implements ToModel, WithHeadingRow, SkipsOnFailure
{
    use Importable,SetterDataTrait,SkipsFailures;
    private $skippedRecords = [];
    private $existingRecords = [];

    private $totalRecords = 0;
    private $insertedRecords = 0;
    private $skippedRecordsCount = 0;
    private $existingRecordsCount = 0;

    public function rules(): array
    {
        return [
            FIRST_NAME => [REQUIRED],
            MIDDLE_NAME => ValidationRule::MIDDLE_NAME,
            LAST_NAME => [REQUIRED],
            EMAIL => ValidationRule::EMAIL,
            DOB => ValidationRule::DOB,
            PHONE_NO => [ValidationRule::PHONE, Rule::unique('users')],
            ROLE_ID => Rule::in(['INTENDED_PARENT', 'SURROGATE_MOTHER','EGG_DONOR','SPERM_DONOR']),
        ];
    }


    public function model(array $row)
    {
        $this->totalRecords++;

        // Validate the row data
        $validator = Validator::make($row, $this->rules());

        if ($validator->fails()) {
            $this->skippedRecordsCount++;
            $this->skippedRecords[] = [
                ROW => $this->totalRecords,//to get row number
                EMAIL => $row[EMAIL],
                ERRORS => $validator->errors()->toArray()
            ];
            return null;
        }

        try {
            /**$randomPassword = $this->rand_passwd(); **/
            $randomPassword = 'HERAFamily@2023';
            $roleId = $this->getRoles($row[ROLE_ID]);
            $user = User::firstOrCreate([
                EMAIL    => $row[EMAIL],
                PHONE_NO => $row[PHONE_NO]
            ], [
                ROLE_ID    => $roleId,
                PROFILE_PIC    => 'https://mbc-dev-kiwitech.s3.amazonaws.com/chat/documents/BAhVUNjoIiwM81TNc3NdkNCVjxeU6GyyPRP8C30l.jpg',
                FIRST_NAME     => $row[FIRST_NAME],
                LAST_NAME     => $row[LAST_NAME],
                PHONE_NO    => $row[PHONE_NO],
                COUNTRY_CODE    => '+1',
                DOB => date(YMD_FORMAT,strtotime($row[DOB])),
                PASSWORD => Hash::make($randomPassword),
                REGISTRATION_STEP => ONE
            ]);

            if ($user->wasRecentlyCreated) {
                $this->insertedRecords++;
                $username = $this->setUserName($user[ROLE_ID], $user->id);
                $refreshToken = CustomHelper::createRefreshTokenForUser($user);
                User::where(ID, $user->id)->update([STATUS_ID => SIX,USERNAME=>$username, REFRESH_TOKEN=> $refreshToken ]);
                dispatch(new SendUserImportSuccessJob($user, $randomPassword));
                if ($user[ROLE_ID] != PARENTS_TO_BE) {
                    dispatch(new CreateAdminChatFreiend($user));
                    dispatch(new CreateStripeAccount($user));
                }
                dispatch(new UpdateUserNotificationSetting($user->id));
                dispatch(new CreateStripeCustomer($user));
            } else {
                 $this->existingRecordsCount++;
                 $this->existingRecords[] = [
                    ROW => $this->totalRecords,
                    EMAIL => $row[EMAIL],
                    ERRORS => 'Already Exists'
                    ];
            }

        } catch (\Exception $e) {
            Log::error('Skipping row due to error: '.$e->getMessage());
            $this->skippedRecordsCount++;
            $this->skippedRecords[] = [
                ROW => $row,
                ERRORS => [$e->getMessage()]
            ];
            return null;
        }

        return $user;
    }



    public function headingRow(): int
    {
        return ONE;
    }

    public function rand_passwd( $length = EIGHT, $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789' ) {
        return substr( str_shuffle( $chars ), ZERO, $length );
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->skippedRecordsCount++;
            $row = $failure->row();
            $errors = $failure->errors();
            $this->skippedRecords[] = [
                ROW => $row,
                ERRORS => $errors
            ];
        }
    }

    public function getSkippedRecords()
    {
        return $this->skippedRecords;
    }

    public function getExistingRecordsCount()
    {
        return $this->existingRecordsCount;
    }

    public function getExistingRecords()
    {
        return $this->existingRecords;
    }

    public function getTotalRecords()
    {
        return $this->totalRecords;
    }

    public function getInsertedRecords()
    {
        return $this->insertedRecords;
    }

    public function getSkippedRecordsCount()
    {
        return $this->skippedRecordsCount;
    }

    public function getRoles($roleKey) {
        $roles = [
            'INTENDED_PARENT' => 2,
            'SURROGATE_MOTHER' => 3,
            'EGG_DONOR' => 4,
            'SPERM_DONOR' => 5
        ];

        return $roles[$roleKey];
    }
    
}
