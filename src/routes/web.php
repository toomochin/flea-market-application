<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileSetupController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StripeController; // ✨ Stripeコントローラーを追加

/*
|--------------------------------------------------------------------------
| PG01 / PG02 商品一覧（トップ）
|--------------------------------------------------------------------------
*/
Route::get('/', [ItemController::class, 'index'])->name('items.index');

/*
|--------------------------------------------------------------------------
| PG05 商品詳細
|--------------------------------------------------------------------------
*/
Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');

/*
|--------------------------------------------------------------------------
| PG03 / PG04 Auth
|--------------------------------------------------------------------------
*/
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

/*
|--------------------------------------------------------------------------
| Email Verify
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->name('verification.send');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('profile.edit');
    })->name('verification.verify');
});

/*
|--------------------------------------------------------------------------
| 認証後（本体）
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'profile.completed'])->group(function () {

    /*
    | PG06 商品購入
    |--------------------------------------------------------------------------
    */
    Route::get('/purchase/{item}', [PurchaseController::class, 'create'])
        ->name('purchases.create');
    Route::get('/purchase/confirm/{item}', [PurchaseController::class, 'confirm'])
        ->name('purchases.confirm');
    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])
        ->name('purchases.store');

    /*
    | ✨ 追加：Stripe 決済関連のルーティング
    | 既存の仕様に合わせ、{item} で商品データを受け取れるように設計
    |--------------------------------------------------------------------------
    */
    // Stripe決済画面へリダイレクト
    Route::post('/purchase/{item}/checkout', [StripeController::class, 'checkout'])
        ->name('stripe.checkout');
    // 決済成功時の戻り先
    Route::get('/purchase/success/{item}', [StripeController::class, 'success'])
        ->name('stripe.success');
    // 決済キャンセル時の戻り先
    Route::get('/purchase/cancel/{item}', [StripeController::class, 'cancel'])
        ->name('stripe.cancel');

    /*
    | PG07 送付先住所変更
    |--------------------------------------------------------------------------
    */
    Route::get('/purchase/address/{item}', [ProfileController::class, 'editAddress'])
        ->name('purchase.address.edit');
    Route::post('/purchase/address/{item}', [ProfileController::class, 'updateAddress'])
        ->name('purchase.address.update');

    /*
    | PG08 商品出品
    |--------------------------------------------------------------------------
    */
    Route::get('/sell', [ItemController::class, 'create'])
        ->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])
        ->name('items.store');

    /*
    | PG09 / PG11 / PG12 マイページ
    |--------------------------------------------------------------------------
    */
    Route::get('/mypage', [MypageController::class, 'show'])
        ->name('mypage.show');

    /*
    | PG10 プロフィール編集
    |--------------------------------------------------------------------------
    */
    Route::get('/mypage/profile', [ProfileSetupController::class, 'edit'])
        ->name('profile.edit');
    Route::post('/mypage/profile', [ProfileSetupController::class, 'update'])
        ->name('profile.update');

    /*
    | お気に入り / コメント
    |--------------------------------------------------------------------------
    */
    Route::post('/item/{item}/favorite', [FavoriteController::class, 'toggle'])
        ->name('items.favorite');

    Route::post('/item/{item}/comments', [CommentController::class, 'store'])
        ->name('items.comments.store');
});