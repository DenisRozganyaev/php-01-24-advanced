<?php

namespace App\Controllers;

use Core\Controller;

class UsersController extends Controller
{
    public function edit(int $id)
    {
        return $this->response(body: [
            'id' => $id
        ]);
    }
}
