<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Http\Requests\UserStoreRequest;
use App\Models\InfoUrl;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Models\User;
use App\Models\PushNotificationUser;
use Illuminate\Support\Str;
use App\Http\Requests\AuthRequests\AppUserLoginRequest;
use App\Http\Requests\AuthRequests\AppUserSignupRequest;



class AuthController extends ApiController
{

    public function login(AppUserLoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            $errors = [
                'password' => 'Sorry, we do not recognize these credentials'
            ];
            return $this->errorResponse("Sorry, we do not recognize these credentials", $errors, 610, $errors);
        }

        $user = User::where(['email' => $credentials['email']])->with('roles')->first();
        if ($user == null) {
            $errors = [
                'password' => 'Sorry, we do not recognize these credentials'
            ];
            return $this->errorResponse("Sorry, we do not recognize these credentials", $errors, 610, $errors);
            // return $this->errorResponse("Sorry, we do not recognize these credentials", null, 610);
        }

        $roleNames = $user->roles->pluck('name')->all();
        $rolesForUserInDB = ['app_user'];
        if (!array_intersect($roleNames, $rolesForUserInDB)) {
            $errors = [
                'password' => 'Sorry, we do not recognize these credentials'
            ];
            return $this->errorResponse("Sorry, we do not recognize these credentials", $errors, 610, $errors);
            // return $this->errorResponse("Sorry, we do not recognize these credentials", null, 610);
        }

        if ($user->is_active == false) {
            return $this->errorResponse("This account has been disabled. Please contact support", null, 610);
        }

        if ($request->push_platform_id == null) {
            $errors = [
                'push_platform_id' => ['Platform id is required']
            ];
            return $this->errorResponse("Platform id is required", $errors, 610, $errors);
        }

        if (!in_array($request->push_platform_id, [1, 2])) {
            $errors = [
                'push_platform_id' => ['Invalid platform id']
            ];
            return $this->errorResponse("Invalid platform id", $errors, 610, $errors);
        }

        $push_notifications = PushNotificationUser::updateOrCreate(['device_token' => $request->device_token], [
            'device_token' => $request->device_token,
            'device_id' => $request->device_id,
            'user_id' => $user->id,
            'status' => true,
            'date_updated' => \Carbon\Carbon::now(),
            'push_platform_id' => $request->push_platform_id
        ]);

        $data = [
            'token' => $token,
            'user' => $user,
            'push_notification' => $push_notifications
        ];

        return $this->successResponse("User logged in successfully", $data);
    }

    public function signup(AppUserSignupRequest $request)
    {


            if (!in_array($request->push_platform_id, [1, 2])) {
                $errors = [
                    'push_platform_id' => ['Invalid platform id']
                ];
                return $this->errorResponse("Invalid platform id", $errors, 610, $errors);
            }

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
//                'isNotificationEnabled' => true,
//                'is_active' => true,
            ];

            DB::beginTransaction();

            $userCreated = User::create($data)->assignRole('app_user');

            $push_notifications = PushNotificationUser::updateOrCreate(['device_token' => $request->device_token], [
                'device_token' => $request->device_token,
                'device_id' => $request->device_id,
                'user_id' => $userCreated->id,
                'status' => true,
                'date_updated' => \Carbon\Carbon::now(),
                'push_platform_id' => $request->push_platform_id
            ]);

            DB::commit();

            $credentials = $request->only(['email', 'password']);

            if (!$token = auth('api')->attempt($credentials)) {
                return $this->errorResponse("Incorrect email or password");
            }

            // $user = User::where(['email' => $credentials['email']])->first();

            $data = [
                'token' => $token,
                // 'token_type' => 'bearer',
                // 'expires_in' => auth('api')->factory()->getTTL() * 60,
                'user' => $userCreated,
                'push_notification' => $push_notifications
            ];


            return $this->successResponse("User logged in successfully", $data);
    }

    public function userDetail(User $user)
    {
        try {
            $user = User::where('id', Auth::id())->with('roles')->first();
            return $this->successResponse("User data", $user);
        } catch (\Throwable $th) {
            return $this->exceptionResponse($th);
        }
    }

    public function logout(Request $request)
    {
        if (!$request->has('push_notification_id') || !$request->has('device_token')) {
            $errors = [
                'push_notification_id' => ['Push notification id is required'],
                'device_token' => ['Device token is required']
            ];
            return $this->errorResponse('Push notification id or device token is required', $errors, 404, $errors);
        }
        if ($request->has('push_notification_id')) {
            PushNotificationUser::where('id', $request['push_notification_id'])->forceDelete();
        } else if ($request->has('device_token')) {
            PushNotificationUser::where('device_token', $request['device_token'])->forceDelete();
        }

        auth('api')->logout();
        return $this->successResponse("User logged out successfully");
    }
}
