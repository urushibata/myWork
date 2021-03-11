<?php

namespace App\Guard\Cognito;

use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;

/**
 * JWT検証
 */
class JWTVerifier
{
    /**
     * JWTをデコードする。
     * @param string $jwt
     * @return object|null
     */
    public function decode(string $jwt): ?object
    {
        $tks = explode('.', $jwt);
        if (count($tks) !== 3) {
            return null;
        }
        [$headb64, $_, $_] = $tks;

        try {
            JWT::$leeway = 60;
            $jwks = $this->fetchJWKs();

            $kid = $this->getKid($headb64);
            $jwk = $this->getJWK($jwks, $kid);
            $alg = $this->getAlg($jwks, $kid);

            return JWT::decode($jwt, $jwk, [$alg]);
        } catch (\RuntimeException $e) {
            return null;
        }
    }

    /**
     * CognitoよりJWKを取得する。
     * @return array JWK
     */
    private function fetchJWKs(): array
    {
        $region = env('AWS_DEFAULT_REGION');
        $user_pool = env('AWS_COGNITO_USER_POOL_ID');
        $response = Http::get("https://cognito-idp.${region}.amazonaws.com/${user_pool}/.well-known/jwks.json");
        return json_decode($response->getBody()->getContents(), true) ?: [];
    }

    /**
     * JWT KID取得
     * @param string $headb64 BASE64URLでエンコードされたJWSヘッダー
     * @return string JWT kid
     */
    private function getKid(string $headb64): string
    {
        $decode_head = json_decode(JWT::urlsafeB64Decode($headb64), true);
        if (array_key_exists('kid', $decode_head)) {
            return $headb64['kid'];
        }
        throw new \RuntimeException();
    }

    /**
     * JWKを取得する。
     * @param $jwks Cognitoより取得したJWK
     * @return string JWK
     */
    private function getJWK(array $jwks, string $kid): string
    {
        $keys = JWK::parseKeySet($jwks);
        if (array_key_exists($kid, $keys)) {
            return $keys[$kid];
        }
        throw new \RuntimeException();
    }

    /**
     * 暗号化アルゴリズムを取得する。
     * @param array $jwks Cognitoより取得したJWK
     * @return string アルゴリズム
     */
    private function getAlg(array $jwks, string $kid): string
    {
        if (!array_key_exists('keys', $jwks)) {
            throw new \RuntimeException();
        }

        foreach ($jwks['keys'] as $key) {
            if ($key['kid'] === $kid && array_key_exists('alg', $key)) {
                return $key['alg'];
            }
        }
        throw new \RuntimeException();
    }
}
