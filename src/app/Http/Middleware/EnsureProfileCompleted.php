<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureProfileCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        // POSTは必ず通す（保存できないと永遠に終わらない）
        if ($request->isMethod('post')) {
            return $next($request);
        }

        $user = $request->user();

        // 未認証は認証画面へ
        if ($user && !$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        // // 初回プロフィール未完了ならプロフィールへ
        // if ($user && !$user->profile_completed) {
        //     return redirect()->route('profile.setup');
        // }

        return $next($request);
    }
}
