<?php

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SlackController extends Controller
{
    private $client_id;
    private $client_secret;
    private $redirect_uri;
    private $driver;

    public function __construct()
{
    // ログイン用ルートであるため、guest ミドルウェアをかまします
    $this->middleware('guest');

    $this->driver = 'slack';
    $this->client_id = env('SLACK_CLIENT_ID');
    $this->client_secret = env('SLACK_CLIENT_SECRET');
    $this->redirect_uri = env("APP_URL") . 'auth/slack/callback';
}
public function redirect()
{
    // $state = csrf_token();
    // $nonce = uniqid();
    // request()->session()->put('nonce', $nonce);
    // // https://slack.com/oauth/authorize?client_id=6600306677556.6600338585860&scope=identify&redirect_uri=https://rabbit-gratan.onrender.com/auth/slack/callback
    // $to = "https://slack.com/oauth/authorize?" .
    // "redirect_uri={$this->redirect_uri}" .
    //     "&client_id={$this->client_id}" .
    //     "&response_type=code" .
    //     "&scope=openid,email" .
    //     "&state={$state}" .
    //     "&nonce={$nonce}" ;

    // return redirect($to);
    return redirect(Socialite::with('slack')->redirect()->getTargetUrl());
}
public function callback()
{
    $socialiteUser = Socialite::driver($this->driver)->stateless()->user();
    logger('socialiteUser', [$socialiteUser]);
    $user = User::where('email',$socialiteUser->getId() . $this->driver,)->first();
    if( $user ) {
        $user->update([
            'avatar' => $socialiteUser->avatar,
            'provider' => 'slack',
            'provider_id' => $socialiteUser->id,
            'access_token' => $socialiteUser->token
        ]);
    } else {
        $user = User::create([
            'name' => $socialiteUser->nickname,
            // 'email' => $socialiteUser->getEmail(),
            'email' => $socialiteUser->getId() . $this->driver,
            'avatar' => $socialiteUser->getAvatar(),
            'provider' =>$this->driver,
            'provider_id' => $socialiteUser->getId(),
            'access_token' => $socialiteUser->token,
            'password' => ''
        ]);
    }
    logger('UserData', [$user]);
    // login the user
    Auth::login($user);
    // $token = $user->createToken('Token Name')->accessToken;
    $token = \Auth::user()->createToken('name')->accessToken;
    return redirect()->intended(RouteServiceProvider::HOME);




    // // id_token のリクエスト
    // $client = new \GuzzleHttp\Client();
    // $res = $client->request('POST', "https://slack.com/api/openid.connect.token", [
    //     'form_params' => [
    //         'client_id' => $this->client_id,
    //         'client_secret' => $this->client_secret,
    //         'code' => request('code'),
    //         'redirect_uri' => $this->redirect_uri
    //     ]
    // ]);

    // // レスポンスのステータスチェック
    // $status = $res->getStatusCode();
    // if ($status !== 200) {
    //     abort(401);
    // }
    // $contents = json_decode($res->getBody()->getContents());
    // if (!$contents->ok) {
    //     abort(401);
    // }

    // // JWT の payload の取得
    // $id_token = explode('.', $contents->id_token);
    // $payload = json_decode(base64_decode($id_token[1]));

    // // nonce の検証
    // $session_nonce = request()->session()->pull('nonce');
    // if ($session_nonce !== $payload->nonce) {
    //     abort(401);
    // }

    // // ユーザの取得
    // $user = ModelsUser::where('email', $payload->email)->firstOrFail();

    // // ログイン処理
    // Auth::login($user);
    // request()->session()->regenerate();
    // return redirect('/home');
// }


}
}
