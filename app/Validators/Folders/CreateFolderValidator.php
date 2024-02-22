<?php

namespace App\Validators\Folders;

use App\Models\Folder;
use App\Validators\BaseValidator;
use Enums\SQL;
use function Core\authId;

class CreateFolderValidator extends BaseValidator
{
    protected array $rules = [
        'title' => '/[\w\s\(\)\-]{3,}/i'
    ];

    protected array $errors = [
        'title' => 'Title should contain characters, numbers, and _-() symbols and has length more than 2 symbols'
    ];

    protected array $skip = ['user_id'];

    public function validate(array $fields = []): bool
    {
        $result = [
            parent::validate($fields),
            !$this->checkOnDuplicateTitle($fields['title'])
        ];

        return !in_array(false, $result);
    }

    protected function checkOnDuplicateTitle(string $title): bool
    {
        $result = Folder::where('user_id', SQL::EQUAL, authId())
            ->and('title', SQL::EQUAL, $title)
            ->exists();

        if($result) {
            $this->setError('title', 'The folder with the same title already exists');
        }

        return $result;
    }
}
