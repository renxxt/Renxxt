<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;
use App\Http\Resources\UserResource AS User;

class UserController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')){
            $data = $request->validate([
                'email' => [ 'required', 'email:rfc,dns' ],
                'password' => [ 'required', 'string' ]
            ]);

            $user = $this->user->getUser($data['email']);
            if (!$user) {
                $messageData = [
                    'type' => "danger",
                    'message' => "帳號密碼錯誤"
                ];
                return back()->with('messageData', [$messageData]);
            }

            if ($user['state'] == 1) {
                $messageData = [
                    'type' => "danger",
                    'message' => "帳號已移除"
                ];
                return back()->with('messageData', [$messageData]);
            }

            if (!Hash::check($data['password'], $user['password'])) {
                $messageData = [
                    'type' => "danger",
                    'message' => "帳號密碼錯誤"
                ];
                return back()->with('messageData', [$messageData]);
            }

            Auth::login($user);
            session()->put('order', $user['order']);
            return redirect()->route('userManagement.list');
        } else {
            return view('login');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function forgetPwd()
    {
        return view('forgetPwd');
    }

    public function emailVerify(Request $request)
    {
        $data = $request->validate([
            'email' => [ 'required', 'email:rfc,dns' ]
        ]);

        $result = $this->user->getUser($data['email']);
        if (!$result) {
            $messageData = [
                'type' => "danger",
                'message' => "信箱錯誤"
            ];
            return back()->with('messageData', [$messageData]);
        }

        if ($result['state'] == 1) {
            $messageData = [
                'type' => "danger",
                'message' => "帳號已移除"
            ];
            return back()->with('messageData', [$messageData]);
        }

        $user = [
            'id' => $result['userID'],
            'email' => $result['email']
        ];
        $mail = Mail::to($user['email'])->send((new OrderShipped())->verificationEmail($user));

        if (!$mail) {
            $messageData = [
                'type' => "danger",
                'message' => "信件寄送失敗，請重試或連絡相關單位，謝謝！"
            ];
            return back()->with('messageData', [$messageData]);
        } else {
            $messageData = [
                'type' => "success",
                'message' => "信件已寄送，請前往信箱收信"
            ];
            return back()->with('messageData', [$messageData]);
        }
    }

    public function verify($id, $hash)
    {
        return view('resetPwd', ['id' => $id, 'hash' => $hash]);
    }

    public function resetPwd(Request $request)
    {
        $data = $request->validate([
            'id' => [ 'required', 'integer' ],
            'hash' => [ 'required', 'string' ],
            'password' => [ 'required', 'string', 'confirmed' ]
        ]);

        $result = $this->user->show($data['id']);
        if (!$result) {
            $messageData = [
                'type' => "danger",
                'message' => "無該使用者"
            ];
            return back()->with('messageData', [$messageData]);
        }

        if (!sha1($result['email']) === $data['hash']) {
            $messageData = [
                'type' => "danger",
                'message' => "無該使用者"
            ];
            return back()->with('messageData', [$messageData]);
        }

        $data['password'] = Hash::make($data['password']);
        $result = $this->user->resetPwd($data);
        if ($result) {
            $messageData = [
                'type' => "success",
                'message' => "重置密碼成功"
            ];
            return redirect()->route('login')->with('messageData', [$messageData]);
        }

        $messageData = [
            'type' => "danger",
            'message' => "重置密碼失敗"
        ];
        return back()->with('messageData', [$messageData]);
    }

    public function profile(Request $request)
    {
        $data = [];
        $data['userID'] = Auth::user()->userID;
        $result = $this->user->list($data);

        return view('profile', ['result' => $result]);
    }

    public function update(Request $request)
    {
        $userID = $request->input('userID');
        $data = $request->validate([
            'userID' => [ 'required', 'integer' ],
            'name' => [ 'required', 'string' ],
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::unique('users')->ignore($userID, 'userID')
            ],
            'phonenumber' => [ 'required', 'digits:10' ],
        ]);

        $result = $this->user->update($data);
        return redirect()->route('profile');
    }
}
