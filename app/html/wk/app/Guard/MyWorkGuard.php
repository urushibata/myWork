<?php

namespace App\Guard;

use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Auth\CognitoUserProvider;
use Illuminate\Support\Facades\Log;

class MyWorkGuard extends SessionGuard implements StatefulGuard
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
        $user_name = $this->session->has('user_name')
            ? $this->session->get('user_name')
            : uniqid('guest_', false);

        if (!$this->session->get('user_name')) {
            Log::debug("create new user: ${user_name}");
        }

        $this->session->put('user_name', $user_name);

        $this->setUser($this->provider->retrieveById($user_name));

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
}
