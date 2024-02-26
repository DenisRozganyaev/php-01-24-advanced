<?php

namespace App\Validators\Notes;

use App\Models\Folder;
use App\Models\Note;
use App\Validators\BaseValidator;
use Enums\SQL;
use function Core\authId;

class Base extends BaseValidator
{
    protected array $skip = ['user_id', 'updated_at', 'pinned', 'completed', 'created_at'];

    // validate folder_id
    // check title on duplicate for current user and specific folder

    protected function isBoolean(array $fields, string $key): bool
    {
        if (empty($fields[$key])) {
            return true;
        }

        $result = is_bool($fields[$key]) || $fields[$key] === 1;

        if (!$result) {
            $this->setError(
                $key,
                "[$key] should be boolean"
            );
        }

        return $result;
    }

    protected function validateFolderId(array $fields, bool $isRequired = true): bool
    {
        if (empty($fields['folder_id']) && !$isRequired) {
            return true;
        }

        $shared = Folder::where('title', value: Folder::SHARED_FOLDER)->take();

        if ($fields['folder_id'] === $shared->id) {
            $this->setError('folder_id', 'You can not create notes in shared folder');
            return false;
        }

        return Folder::where('id', value:$fields['folder_id'])
            ->startCondition()
                ->and('user_id', value: authId())
                ->or('user_id', SQL::IS)
            ->endCondition()
            ->exists();
    }

    protected function checkTitleOnDuplicate(string $title, int $folder_id): bool
    {
        $result = Note::where('title', value: $title)
            ->and('folder_id', value: $folder_id)
            ->and('user_id', value: authId())
            ->exists();

        if ($result) {
            $this->setError('title', 'Title with the same name already exists in chosen directory');
        }

        return $result;
    }
}
