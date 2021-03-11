<?php

namespace App\Guard;

use App\Facades\CognitoService;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Auth\CognitoUserProvider;
use RuntimeException;

class CognitoGuard extends SessionGuard implements StatefulGuard
{
    /**
     * construct
     *
     * @param string $name
     * @param string
     */
    public function __construct(
        string $name,
        CognitoUserProvider $provider = null,
        Session $session,
        ?Request $request = null
    ) {
        parent::__construct($name, $provider, $session, $request);
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {

        $user_name = $this->session->get('user_name');
        $access_token = $this->session->get('access_token');
        $refresh_token = $this->session->get('refresh_token');

        Log::debug('session id: ' . $this->session->getId());
        Log::debug('user_name: ' . $user_name);
        Log::debug('access token: ' . $access_token);
        Log::debug('refresh token: ' . $refresh_token);

        Log::debug('cookies: ');
        foreach ($this->request->cookies->all() as $key => $value) {
            Log::debug('  key: ' . $key . ', val: ' . $value);
        }

        if ($access_token) {
            if (!CognitoService::getUser($access_token)) {
                try {
                    $tokens = CognitoService::refreshToken($refresh_token);

                    $this->setTokenToSession($tokens);
                } catch (RuntimeException $e) {
                    Log::warning("リフレッシュトークンの取得に失敗しました。新規ユーザーを作成します。");
                    Log::warning($e->getMessage());
                    $this->signUp();
                }
            }

            $this->setUser($this->provider->retrieveById($this->session->get('user_name')));
        } else {
            $this->signUp();
        }

        $this->fireAuthenticatedEvent($this->user);
        return $this->user;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = []): bool
    {
        throw new \RuntimeException('Cognito guard cannot be used for credential based authentication.');
    }

    /**
     * Cognitoにサインアップする。
     */
    private function signUp(): void
    {
        $tokens = CognitoService::signUpAndConfirm();

        $this->setTokenToSession($tokens);
        $this->setUser($this->provider->retrieveById($tokens['user_name']));
        $this->fireLoginEvent($this->user, true);
    }

    /**
     * Cognitoトークンをセッションに保存する。
     * @param array $tokens Cognitoトークン
     */
    private function setTokenToSession(array $tokens): void
    {
        Log::debug('access token: ' . var_export($tokens, true));

        $keys = ['user_name', 'id_token', 'access_token', 'refresh_token'];

        foreach ($keys as $value) {
            if (array_key_exists($value, $tokens) && $tokens[$value]) {
                $this->session->put($value, $tokens[$value]);
            }
        }
    }
}
