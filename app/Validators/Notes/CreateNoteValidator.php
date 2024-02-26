<?php

namespace App\Validators\Notes;

class CreateNoteValidator extends Base
{
    protected array $rules = [
        'title' => '/[\w\s\(\)\-]{3,}/i',
        'content' => '/.+/i',
        'folder_id' => '/\d+/i'
    ];

    protected array $errors = [
        'title' => 'Title should contain characters, numbers, and _-() symbols and has length more than 2 symbols',
        'content' => 'Minimum length 1 symbol',
        'folder_id' => 'folder id should be exists in request and has type integer'
    ];

    public function validate(array $fields = []): bool
    {
        return !in_array(false, [
            parent::validate($fields),
            $this->isBoolean($fields, 'pinned'),
            $this->isBoolean($fields, 'completed'),
            $this->validateFolderId($fields),
            !$this->checkTitleOnDuplicate($fields['title'], $fields['folder_id'])
        ]);
    }
}
