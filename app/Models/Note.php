<?php

namespace App\Models;

use Core\Model;

class Note extends Model
{
    public static string|null $tableName = 'notes';

    public int $user_id, $folder_id;
    public string $title, $content, $created_at, $updated_at;
    public bool $pinned, $completed;
}
