<?php

namespace App\Controllers;

use App\Models\Folder;
use App\Models\Note;
use App\Validators\Folders\CreateFolderValidator;
use App\Validators\Folders\UpdateFolderValidator;
use App\Validators\Notes\CreateNoteValidator;
use Enums\SQL;
use function Core\authId;
use function Core\requestBody;

class NotesController extends BaseApiController
{
    public function index()
    {
        return $this->response(
            body: Note::where('user_id', value: authId())
            ->orderBy([
                'pinned' => 'DESC',
                'completed' => 'ASC',
                'updated_at' => 'DESC'
            ])
            ->get()
        );
    }

    public function show(int $id)
    {
        $note = Note::find($id);

        if (!$note) {
            return $this->response(404, errors: ['message' => 'Note not found']);
        }

        if ($note->user_id !== authId()) {
            return $this->response(403, errors: ['message' => 'This resource is forbidden for you']);
        }

        return $this->response(body: $note->toArray());
    }

    public function store()
    {
        $data = array_merge(
            requestBody(),
            ['user_id' => authId()]
        );
        $validator = new CreateNoteValidator();

        if ($validator->validate($data) && $note = Note::create($data)) {
            return $this->response(body: $note->toArray());
        }

        return $this->response(422, errors: $validator->getErrors());
    }

    public function update(int $id)
    {
    }

    public function destroy($id)
    {
    }
}
