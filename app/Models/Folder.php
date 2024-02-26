<?php

namespace App\Models;

use Core\Model;

class Folder extends Model
{
    const SHARED_FOLDER = "Shared";
    const GENERAL_FOLDER = "General";

    public static string|null $tableName = 'folders';

    public int|null $user_id;
    public string $title, $created_at, $updated_at;
}