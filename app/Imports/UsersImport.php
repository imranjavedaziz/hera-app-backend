<?php
namespace App\Imports;

use Illuminate\Validation\Rule;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use App\Http\ValidationRule;
use App\Traits\SetterDataTrait;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Jobs\SendUserImportSuccessJob;

use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class UsersImport implements ToModel, WithHeadingRow
{
    use Importable,SetterDataTrait,SkipsFailures;

    public function rules(): array
    {
        return [
            FIRST_NAME => ValidationRule::NAME,
            MIDDLE_NAME => ValidationRule::MIDDLE_NAME,
            LAST_NAME => ValidationRule::NAME,
            EMAIL => ValidationRule::EMAIL,
            PASSWORD => ValidationRule::PASSWORD,
            PROFILE_PIC => ValidationRule::PROFILE_PIC,
            DOB => ValidationRule::DOB,
        ];
    }


    public function model(array $row)
    {
        $defaultPassword = DEFAULT_PASSWORD;
        //then make a user if one doesn't already exist with that username
        $user = User::firstOrCreate([
            'email'    => $row['email']
         ], [
            'role_id'    => $row['role_id'] === 'PARENTS_TO_BE' ? 2 : ($row['role_id'] === 'SURROGATE_MOTHER' ? 3 : null),
            'profile_pic'    => $row['profile_pic'],
            'first_name'     => $row['first_name'],
            'last_name'     => $row['last_name'],
            'phone_no'    => $row['phone_no'],
            'country_code'    => '+1',
            'dob' => date(YMD_FORMAT,strtotime($row[DOB])),
            PASSWORD => bcrypt($defaultPassword),
            REGISTRATION_STEP => ONE
         ]);
        if($user){
            $username = $this->setUserName($user[ROLE_ID], $user->id);
            User::where(ID, $user->id)->update([USERNAME=>$username]);
            dispatch(new SendUserImportSuccessJob($user));//to send user credentials over mail
        }
        return $user;
    }

    public function headingRow(): int
    {
        return 1;
    }
}
