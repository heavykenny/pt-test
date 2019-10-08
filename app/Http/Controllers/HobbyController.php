<?php

namespace App\Http\Controllers;

use App\Hobby;
use App\Mail\HobbyMailNotification;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HobbyController extends Controller
{
    const HTTP_OK = 200;

    public function getAllUserHobby(Request $request)
    {
        $hobbies = Hobby::where('user_id', $request->user_id)->get();
        return response([
            'status' => true,
            'hobby' => $hobbies
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
            $details['name'] = $user->email;
            $details['hobby_title'] = $request->hobby;
            $details['hobby_action'] = "Created";
            Mail::to($user)->send(new HobbyMailNotification($details));
            return response([
                'status' => true,
                'message' => 'Successfully Created',
                'hobbies' => $hobby
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
        $user = User::find($request->user_id);

        $data['content'] = $request->hobby_content;
        $data['title'] = $request->hobby_title;

        $updateHobby = Hobby::where('id', $request->hobby_id)->update([$data]);

        if ($updateHobby) {
            $details['name'] = $user->email;
            $details['hobby_title'] = $request->hobby_title;
            $details['hobby_action'] = "Updated";
            Mail::to($user)->send(new HobbyMailNotification($details));

            return response([
                'status' => true,
                'message' => 'Successfully Updated',
                'hobbies' => $updateHobby
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
        $user = User::find($request->user_id);

        $hobby_id = $request->hobby_id;
        $deleteHobby = Hobby::delete($hobby_id);
        if ($deleteHobby) {
            $details['name'] = $user->email;
            $details['hobby_title'] = $deleteHobby->hobby_title;
            $details['hobby_action'] = "Deleted";
            Mail::to($user)->send(new HobbyMailNotification($details));
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
}
