<?php

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Controller;
use App\Models\User as ModelsUser;
use Illuminate\Support\Facades\Auth;
use App\User;

class SlackController extends Controller
{
    private $client_id;
    private $client_secret;
    private $redirect_uri;

    public function __construct()
{
    // ログイン用ルートであるため、guest ミドルウェアをかまします
    $this->middleware('guest');

    $this->client_id = env('const.SLACK_CLIENT_ID');
    $this->client_secret = env('SLACK_CLIENT_SECRET');
    $this->redirect_uri = env("SLACK_REDIRECT_CALLBACK_URL");
}
public function redirect()
{
    $state = csrf_token();
    $nonce = uniqid();
    request()->session()->put('nonce', $nonce);

    $to = "https://slack.com/openid/connect/authorize" .
        "?response_type=code" .
        "&scope=openid,email" .
        "&state={$state}" .
        "&nonce={$nonce}" .
        "&client_id={$this->client_id}" .
        "&redirect_uri={$this->redirect_uri}";

    return redirect($to);
}
public function callback()
{
    // state の検証
    if (csrf_token() !== request('state')) {
        abort(401);
    }

    // id_token のリクエスト
    $client = new \GuzzleHttp\Client();
    $res = $client->request('POST', "https://slack.com/api/openid.connect.token", [
        'form_params' => [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'code' => request('code'),
            'redirect_uri' => $this->redirect_uri
        ]
    ]);

    // レスポンスのステータスチェック
    $status = $res->getStatusCode();
    if ($status !== 200) {
        abort(401);
    }
    $contents = json_decode($res->getBody()->getContents());
    if (!$contents->ok) {
        abort(401);
    }

    // JWT の payload の取得
    $id_token = explode('.', $contents->id_token);
    $payload = json_decode(base64_decode($id_token[1]));

    // nonce の検証
    $session_nonce = request()->session()->pull('nonce');
    if ($session_nonce !== $payload->nonce) {
        abort(401);
    }

    // ユーザの取得
    $user = ModelsUser::where('email', $payload->email)->firstOrFail();

    // ログイン処理
    Auth::login($user);
    request()->session()->regenerate();
    return redirect('/home');
}

}
