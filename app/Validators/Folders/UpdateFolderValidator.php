<?php

namespace App\Validators\Folders;

use App\Models\Folder;
use App\Validators\BaseValidator;
use Enums\SQL;
use function Core\authId;

class UpdateFolderValidator extends CreateFolderValidator
{
    protected array $skip = ['user_id', 'updated_at'];
}
