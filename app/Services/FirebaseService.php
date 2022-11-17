<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Kreait\Firebase\Database\Transaction;
use Kreait\Firebase\Exception\Database\ReferenceHasNotBeenSnapshotted;
use Kreait\Firebase\Exception\Database\TransactionFailed;
use Kreait\Firebase\Exception\ApiException;
use App\Helpers\CustomHelper;
use Facades\{
    App\Services\SubscriptionService,
};

/**
 * Class FirebaseService
 * @package App\Services
 */
class FirebaseService
{
    protected $database;
    protected $server;
    protected $table;
    protected $friendsKey;

    public function __construct()
    {
        $this->database   = app('firebase.database');
        $this->serverName = config('app.env');
        $this->tableName  = $this->serverName .'/'. 'Users';
        $this->friendsKey = 'Friends';
    }

    public function createFriend($sender,$reciever, $msg = '') {
        $msgId = ($sender->id > $reciever->id) ? $sender->id : $reciever->id;
        return [
            "deviceToken" => "devicetoken",
            "message" => $msg,
            "msgId" => $msgId."-".time(),
            "read" => ZERO,
            "feedback_status" => ZERO,
            "recieverId" => $reciever->id,
            "recieverImage" => $reciever->profile_pic,
            "recieverName" => CustomHelper::fullName($reciever),
            "recieverUserName" => $reciever->username,
            "recieverSubscription" => SubscriptionService::getSubscriptionStatus($reciever->id),
            "senderId" => $sender->id,
            "status_id" => ACTIVE,
            "senderImage" => $sender->profile_pic,
            "senderName"  => CustomHelper::fullName($sender),
            "senderUserName" => $reciever->username,
            "senderSubscription" => SubscriptionService::getSubscriptionStatus($sender->id),
            "currentRole" => isset($reciever->role_id)?$reciever->role_id:ZERO,
            MATCH_REQUEST => [FROM_USER_ID => $sender->id, TO_USER_ID => $reciever->id, STATUS => ONE],
            "time" => time(),
            "type" => "Text"
        ];
    }

    public function createAdminFriends($newUser) {
        try {
            $response = NULL;
            /***\Log::info(" createAdminFriends : " . json_encode($newUser));***/
            $admin = User::where(EMAIL,config('constants.ADMIN_EMAIL'))->first();
            $adminFriendData = $this->createFriend($admin,$newUser);
            if ($this->database->getReference($this->tableName)->getSnapshot()->hasChild($admin->id) === false){
                $this->database->getReference($this->tableName)->set($admin->id);
                $this->database->getReference($this->tableName.'/'.$admin->id)->set($this->friendsKey);
                $this->database->getReference($this->tableName.'/'.$admin->id.'/'.$this->friendsKey)->set($newUser->id);
                $response = $this->database->getReference($this->tableName.'/'.$admin->id.'/'.$this->friendsKey.'/'.$newUser->id)->set($adminFriendData);
            }
            /***Add Admin Friends ***/
            if ($this->database->getReference($this->tableName)->getSnapshot()->hasChild($admin->id.'/'.$this->friendsKey.'/'.$newUser->id) === false){
                $this->database->getReference($this->tableName)->update([$admin->id.'/'.$this->friendsKey.'/'.$newUser->id => '']);
                $response = $this->database->getReference($this->tableName.'/'.$admin->id.'/'.$this->friendsKey.'/'.$newUser->id)->set($adminFriendData);
            }
            /***Create New User ***/
            $friendoOfAdminData = $this->createFriend($newUser,$admin);
            if ($this->database->getReference($this->tableName)->getSnapshot()->hasChild($newUser->id) === false){
                $this->database->getReference($this->tableName)->update([$newUser->id => '']);
                $this->database->getReference($this->tableName.'/'.$newUser->id)->set($this->friendsKey);
                $this->database->getReference($this->tableName.'/'.$newUser->id.'/'.$this->friendsKey)->set($admin->id);
                $response = $this->database->getReference($this->tableName.'/'.$newUser->id.'/'.$this->friendsKey.'/'.$admin->id)->set($friendoOfAdminData);
            }
       } catch (ApiException $e) {
            $request = $e->getRequest();
            $response = $e->getResponse();
            $response = $response->getBody();
            echo $request->getUri().PHP_EOL;
            echo $request->getBody().PHP_EOL;
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return $response;
    }

    public function createChatFriends($user1,$user2) {
        try {
            $response = NULL;
            $msg = "";
            $chatUser1FriendData = $this->createFriend($user1,$user2,$msg);
            /***Add User 1 Friends ***/
            if ($this->database->getReference($this->tableName)->getSnapshot()->hasChild($user1->id.'/'.$this->friendsKey.'/'.$user2->id) === false){
                $this->database->getReference($this->tableName)->update([$user1->id.'/'.$this->friendsKey.'/'.$user2->id => '']);
                $response = $this->database->getReference($this->tableName.'/'.$user1->id.'/'.$this->friendsKey.'/'.$user2->id)->set($chatUser1FriendData);
            }
            /***Add User 2 Friends ***/
            $msg = "Match Request!";
            $chatUser2FriendData = $this->createFriend($user2,$user1,$msg);
            if ($this->database->getReference($this->tableName)->getSnapshot()->hasChild($user2->id.'/'.$this->friendsKey.'/'.$user1->id) === false){
                $this->database->getReference($this->tableName)->update([$user2->id.'/'.$this->friendsKey.'/'.$user1->id => '']);
                $response = $this->database->getReference($this->tableName.'/'.$user2->id.'/'.$this->friendsKey.'/'.$user1->id)->set($chatUser2FriendData);
            }
       } catch (ApiException $e) {
            $request = $e->getRequest();
            $response = $e->getResponse();
            $response = $response->getBody();
            echo $request->getUri().PHP_EOL;
            echo $request->getBody().PHP_EOL;
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return $response;
    }

    public function removeChatFriends($user1,$user2) {
        try {
            $response = NULL;
            /***Remove User 1 Friends ***/
            if ($this->database->getReference($this->tableName)->getSnapshot()->hasChild($user1->id.'/'.$this->friendsKey.'/'.$user2->id) === true){
                $this->database->getReference($this->tableName.'/'.$user1->id.'/'.$this->friendsKey.'/'.$user2->id)->remove();
            }
            /***Remove User 2 Friends ***/
            if ($this->database->getReference($this->tableName)->getSnapshot()->hasChild($user2->id.'/'.$this->friendsKey.'/'.$user1->id) === true){
                $this->database->getReference($this->tableName.'/'.$user2->id.'/'.$this->friendsKey.'/'.$user1->id)->remove();
            }
       } catch (ApiException $e) {
            $request = $e->getRequest();
            $response = $e->getResponse();
            $response = $response->getBody();
            echo $request->getUri().PHP_EOL;
            echo $request->getBody().PHP_EOL;
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return $response;
    }

    public function updateChatFriends($receiver) {
        try {
            $response = NULL;
            $admin = User::where(EMAIL,config('constants.ADMIN_EMAIL'))->first();
            $users = $this->database->getReference($this->tableName)->getValue();
            if(!empty($users)) {
                foreach($users as $key => $user) {
                    /***Update As Admin Friend ***/
                    if ($key == $admin->id && $this->database->getReference($this->tableName)->getSnapshot()->hasChild($admin->id.'/'.$this->friendsKey.'/'.$receiver->id) === true){
                        $this->database->getReference($this->tableName)->update([$admin->id.'/'.$this->friendsKey.'/'.$receiver->id.'/recieverName' => $receiver->full_name]);
                        $response = $this->database->getReference($this->tableName)->update([$admin->id.'/'.$this->friendsKey.'/'.$receiver->id.'/recieverImage' => $receiver->profile_image]);
                    }
                    if ($key == $receiver->id){
                        $this->updateFriendsAsSenderName($receiver);
                    }
                }
            }
        } catch (ApiException $e) {
            $request = $e->getRequest();
            $response = $e->getResponse();
            $response = $response->getBody();
            echo $request->getUri().PHP_EOL;
            echo $request->getBody().PHP_EOL;
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return $response;
    }

    private function updateFriendsAsSenderName($receiver) {
        $friends = $this->database->getReference($this->tableName.'/'.$receiver->id.'/'.$this->friendsKey)->getValue();
        if(!empty($friends)) {
            foreach($friends as $key => $friend) {
                if ($this->database->getReference($this->tableName.'/'.$receiver->id.'/'.$this->friendsKey)->getSnapshot()->hasChild($key) === true){
                    $this->database->getReference($this->tableName.'/'.$receiver->id.'/'.$this->friendsKey)->update([$key.'/senderName' => $receiver->full_name]);
                    $this->database->getReference($this->tableName.'/'.$receiver->id.'/'.$this->friendsKey)->update([$key.'/senderImage' => $receiver->profile_image]);
                }
            }
        }
    }

    public function updateUserStatus($receiver, $accountStatus, $keyName) {
        $users = $this->database->getReference($this->tableName)->getValue();
        if(!empty($users)) {
            foreach($users as $key => $user) {
                if ($key == $receiver->id){
                    continue;
                }
                $this->updateChatUserAccountStatus($key, $receiver, $accountStatus, $keyName);
            }
        }
    }

    private function updateChatUserAccountStatus($key, $receiver, $accountStatus, $keyName) {
        $friends = $this->database->getReference($this->tableName.'/'.$key.'/'.$this->friendsKey)->getValue();
        if(!empty($friends)) {
            foreach($friends as $keyOne => $friend) {
                if ($keyOne == $receiver->id && $this->database->getReference($this->tableName.'/'.$key.'/'.$this->friendsKey)->getSnapshot()->hasChild($keyOne) === true){
                    $this->database->getReference($this->tableName.'/'.$key.'/'.$this->friendsKey)->update([$receiver->id.'/'.$keyName => $accountStatus]);
                }
            }
        }
        return true;
    }

    /**
     * Used To Create Dummy Data
     */
    public function createAdminFirebaseChatUser() {
        $users = User::whereIn('role_id',[2,3,4,5])->get();
        if(!empty($users)) {
            foreach($users as $user) {
                $this->createAdminFriends($user);
            }
        }
    }

    public function updateMatchRequestStatus($user1,$user2) {
        try {
            $response = NULL;
            /***Update User 1 Friends ***/
            if ($this->database->getReference($this->tableName)->getSnapshot()->hasChild($user1->id.'/'.$this->friendsKey.'/'.$user2->id) === true){
                $this->database->getReference($this->tableName.'/'.$user1->id.'/'.$this->friendsKey)->update([$user2->id.'/message' => ""]);
                $this->database->getReference($this->tableName.'/'.$user1->id.'/'.$this->friendsKey.'/'.$user2->id.'/'.MATCH_REQUEST)->update([FROM_USER_ID => $user1->id, TO_USER_ID => $user2->id,STATUS => TWO]);
            }
            /***Update User 2 Friends ***/
            if ($this->database->getReference($this->tableName)->getSnapshot()->hasChild($user2->id.'/'.$this->friendsKey.'/'.$user1->id) === true){
                $this->database->getReference($this->tableName.'/'.$user2->id.'/'.$this->friendsKey)->update([$user1->id.'/message' => "It's a Match!"]);
                $this->database->getReference($this->tableName.'/'.$user2->id.'/'.$this->friendsKey.'/'.$user1->id.'/'.MATCH_REQUEST)->update([FROM_USER_ID => $user1->id, TO_USER_ID => $user2->id, STATUS => TWO]);
            }
       } catch (ApiException $e) {
            $request = $e->getRequest();
            $response = $e->getResponse();
            $response = $response->getBody();
            echo $request->getUri().PHP_EOL;
            echo $request->getBody().PHP_EOL;
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return $response;
    }
}
