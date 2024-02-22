<?php

namespace App\Controllers;

use App\Models\User;
use Core\Controller;
use ReallySimpleJWT\Token;
use function Core\{
    getAuthToken,
    authId
};

class BaseApiController extends Controller
{
    public function before(string $action, array $params = []): bool
    {
        $token = getAuthToken();
        $user = User::find(authId());

        if (!Token::validate($token, $user->password)) {
            throw new \Exception('Token is invalid', 422);
        }

        return true;
    }
}
