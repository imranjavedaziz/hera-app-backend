<?php

namespace App\Services;

use App\Models\DeviceRegistration;
use App\Models\User;

class FcmService
{
    public function registerDevice($data)
    {
        $device = DeviceRegistration::where(DEVICE_TOKEN, $data[DEVICE_TOKEN])
            ->orWhere(DEVICE_ID, $data[DEVICE_ID])
            ->first();

        if (empty($device)) {
            $device = new DeviceRegistration;
        }
        $device->user_id = $data[USER_ID];
        $device->device_id = $data[DEVICE_ID];
        $device->device_token = $data[DEVICE_TOKEN];
        $device->device_type = $data[DEVICE_TYPE];
        $device->status_id = 1;
        $device->save();
        return true;
    }

    public function deactivateRegisterDevice($user_id)
    {
        return DeviceRegistration::where(USER_ID, $user_id)->update([STATUS_ID=>2]);
    }
}
