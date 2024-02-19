<?php
namespace App\Controllers;

use App\Models\User;
use App\Validators\Auth\RegisterValidator;
use Core\Controller;
use function Core\requestBody;

class AuthController extends Controller
{
    public function signUp()
    {
        $data = requestBody();
        $validator = new RegisterValidator();

        if ($validator->validate($data)) {
            $user = User::create([
                ...$data,
                'password' => password_hash($data['password'], PASSWORD_BCRYPT)
            ]);

            return $this->response(body: $user->toArray());
        }

        return $this->response(errors: $validator->getErrors());
    }

    public function signIn()
    {

    }
}
