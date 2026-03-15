<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileSetupRequest;

class ProfileSetupController extends Controller
{
    // GET /profile/setup (または /mypage/profile)
    public function edit(Request $request)
    {
        return view('profile.setup', ['user' => $request->user()]);
    }

    // POST /profile/setup (または /mypage/profile)
    public function update(ProfileSetupRequest $request)
    {
        $user = $request->user();

        if ($request->hasFile('profile_image')) {
            // 既存画像があれば削除（任意だけど推奨）
            if ($user->profile_image_path) {
                Storage::disk('public')->delete($user->profile_image_path);
            }

            $path = $request->file('profile_image')
                ->store('profile_images/' . $user->id, 'public');

            $user->profile_image_path = $path;
        }

        // $request から直接データを取得して代入
        $user->name = $request->name;
        $user->postcode = $request->postcode;
        $user->address = $request->address;
        $user->building = $request->building ?? null;

        $user->profile_completed = true;
        $user->save();

        return redirect()->intended(route('mypage.show'))->with('success', 'プロフィールを更新しました。');
    }
}