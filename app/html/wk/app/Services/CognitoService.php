<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;
use RuntimeException;

class CognitoService
{
    private CognitoIdentityProviderClient $client;
    private string $client_id;
    private string $pool_id;

    /**
     * construct
     */
    public function __construct(CognitoIdentityProviderClient $client, string $clientId, string $poolId)
    {
        $this->client = $client;
        $this->client_id = $clientId;
        $this->pool_id = $poolId;
    }

    /**
     * ユーザー登録と承認
     */
    public function signUpAndConfirm()
    {
        try {
            $user_name = uniqid('guest_', false);
            $password = substr(bin2hex(random_bytes(12)), 0, 12);

            Log::debug("name: $user_name, password: $password");

            $this->signUp($user_name, $password);
            $this->confirm($user_name);

            return $this->auth($user_name, $password);
        } catch (CognitoIdentityProviderException $e) {
            throw $e;
        }
    }

    /**
     * ユーザー登録
     *
     * @param string $user_name user name.
     * @param string $password user password.
     * @return void
     */
    public function signUp(String $user_name, String $password): void
    {
        $response = $this->client->signUp([
            'ClientId' => $this->client_id,
            'Password' => $password,
            'Username' => $user_name
        ]);

        Log::debug("cognite sighn up response: " . var_export($response, true));
    }

    /**
     * ユーザー承認
     *
     * @param string $user_name user name.
     * @return void
     */
    public function confirm(String $user_name): void
    {
        $response = $this->client->AdminConfirmSignUp([
            'Username' => $user_name,
            'UserPoolId' => $this->pool_id,
        ]);

        Log::debug("cognite confirm response: " . var_export($response, true));
    }

    /**
     * 認証
     *
     * @param String $name login user
     * @param String $password login password
     * @return array|bool 成功した場合トークン情報
     */
    public function auth($name, $password)
    {
        try {
            $response = $this->client->adminInitiateAuth([
                'AuthFlow' => 'ADMIN_NO_SRP_AUTH',
                'AuthParameters' => [
                    'USERNAME' => $name,
                    'PASSWORD' => $password,
                ],
                'ClientId' => $this->client_id,
                'UserPoolId' => $this->pool_id
            ]);

            Log::debug("cognite auth response: " . var_export($response, true));

            $auth_result = $response->search('AuthenticationResult');

            return [
                'user_name' => $name,
                'id_token' => $auth_result["IdToken"],
                'access_token' => $auth_result["AccessToken"],
                'refresh_token' => $auth_result["RefreshToken"]
            ];
        } catch (CognitoIdentityProviderException $e) {
            throw $e;
        }
    }

    /**
     * アクセストークンからユーザーを取得する。
     * @param string $access_token アクセストークン
     * @return bool ture:ユーザーを取得した場合
     */
    public function getUser(string $access_token): bool
    {
        try {
            $response = $this->client->getUser([
                'AccessToken' => $access_token,
            ]);

            return !!$response;
        } catch (RuntimeException $e) {
            Log::warning('ユーザーが取得できませんでした。: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * トークンをリフレッシュする。
     * @param string $refresh_token リフレッシュトークン
     * @return array 成功した場合トークン情報
     */
    public function refreshToken(string $refresh_token): array
    {
        try {
            $response = $this->client->adminInitiateAuth([
                'AuthFlow' => 'REFRESH_TOKEN_AUTH',
                'AuthParameters' => [
                    'REFRESH_TOKEN' => $refresh_token,
                ],
                'ClientId' => $this->client_id,
                'UserPoolId' => $this->pool_id
            ]);

            Log::debug("cognite refresh response: " . var_export($response, true));

            $auth_result = $response->search('AuthenticationResult');

            return [
                'id_token' => $auth_result["IdToken"],
                'access_token' => $auth_result["AccessToken"],
            ];
        } catch (CognitoIdentityProviderException $e) {
            throw $e;
        }
    }
}
