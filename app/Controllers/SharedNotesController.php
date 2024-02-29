<?php

namespace App\Controllers;

use App\Models\Note;
use App\Models\SharedNote;
use App\Validators\SharedNoteValidator;
use function Core\requestBody;

class SharedNotesController extends BaseApiController
{
    protected SharedNoteValidator $validator;

    public function __construct()
    {
        $this->validator = new SharedNoteValidator();
    }


    public function add(int $note_id)
    {
        $data = [
            'note_id' => $note_id,
            ...requestBody()
        ];

        if (
            $this->validator->validate($data) &&
            !$this->validator->isNoteSharedWithUser($data) &&
            $sharedNote = SharedNote::create($data)
        ) {
            $note = Note::find($sharedNote->note_id);
            return $this->response(body: $note->toArray());
        }

        return $this->response(422, errors: $this->validator->getErrors());
    }

    public function remove(int $note_id)
    {
        $data = [
            'note_id' => $note_id,
            ...requestBody()
        ];

        if ($this->validator->validate($data) && $this->validator->isNoteSharedWithUser($data)) {
            $sharedNote = SharedNote::where('note_id', value: $data['note_id'])
                ->and('user_id', value: $data['user_id'])
                ->take();

            if (!$sharedNote) {
                throw new \Exception('Shared note does not exists', 404);
            }

            SharedNote::destroy($sharedNote->id);

            return $this->response(body: $sharedNote->toArray());
        }

        return $this->response(422, errors: $this->validator->getErrors());
    }
}
