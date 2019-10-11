<?php

namespace App\Http\Controllers\API;

use App\Hobby;
use App\Http\Controllers\Controller;
use App\Mail\HobbyMailNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;

class HobbyController extends Controller
{

    public function getAllUserHobby(Request $request)
    {
        $hobbies = Hobby::where('user_id', $request->user_id)->get();
        return response([
            'status' => true,
            'hobby' => $hobbies,
        ], config('constants.status.HTTP_OK'));
    }

    public function getHobbyDetails(Request $request)
    {
        $hobby = Hobby::find($request->hobby_id);
        return response([
            'status' => true,
            'hobby' => $hobby,
        ], config('constants.status.HTTP_OK'));
    }

    public function addHobby(Request $request)
    {
        $user = User::find($request->user_id);
        $data['content'] = $request->hobby_content;
        $data['title'] = $request->hobby_title;
        $data['user_id'] = $user->id;
        $hobby = Hobby::create($data);

        if ($hobby) {
            $details['name'] = $user->name;
            $details['hobby_title'] = $hobby->title;
            $details['hobby_action'] = "Created";
            Mail::to($user)->send(new HobbyMailNotification($details));
            $number = $user->phone;
            $message = "Hobby Created Successfully";
            $this->sendSMS($message,$number);
            return response([
                'status' => true,
                'message' => 'Successfully Created',
                'hobbies' => $hobby,
            ], config('constants.status.HTTP_OK'));
        } else {
            return response([
                'status' => false,
                'message' => 'Error While Creating',
            ], config('constants.status.HTTP_OK'));
        }
    }

    public function editHobby(Request $request)
    {
        $hobby = Hobby::where('id', $request->hobby_id)->where('user_id', $request->user_id)->first();
        $user = User::find($hobby->user_id);

        $data['content'] = $request->hobby_content;
        $data['title'] = $request->hobby_title;

        $updateHobby = Hobby::where('id', $hobby->id)->update($data);
        if ($updateHobby) {
            $details['name'] = $user->name;
            $details['hobby_title'] = $hobby->title;
            $details['hobby_action'] = "Updated";
            Mail::to($user)->send(new HobbyMailNotification($details));
            $number = $user->phone;
            $message = "Hobby Updated Successfully";
            $this->sendSMS($message,$number);
            return response([
                'status' => true,
                'message' => 'Successfully Updated',
                'hobbies' => $updateHobby,
            ], config('constants.status.HTTP_OK'));
        } else {
            return response([
                'status' => false,
                'message' => 'Error While Updating',
            ], config('constants.status.HTTP_OK'));
        }
    }

    public function deleteHobby(Request $request)
    {
        $hobby = Hobby::find($request->hobby_id);
        $user = User::find($request->user_id);

        $deleteHobby = Hobby::where('id', $request->hobby_id)->where('user_id', $request->user_id)->delete();
        if ($deleteHobby) {
            $details['name'] = $user->name;
            $details['hobby_title'] = $hobby->title;
            $details['hobby_action'] = "Deleted";
            Mail::to($user)->send(new HobbyMailNotification($details));
            $number = $user->phone;
            $message = "Hobby Deleted Successfully";
            $this->sendSMS($message,$number);
            return response([
                'status' => true,
                'message' => 'Successfully Deleted',
            ], config('constants.status.HTTP_OK'));
        } else {
            return response([
                'status' => false,
                'message' => 'Error While Deleting',
            ], config('constants.status.HTTP_OK'));
        }
    }

    private function sendSMS($message, $number)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $client = new Client($sid, $token);
        $sendsms = $client->messages->create(
            $number,
            [
                'from' => env('TWILIO_FROM'),
                'body' => $message,
            ]
        );
        if($sendsms){
            return true;
        }else{
            return false;
        }
    }
}
