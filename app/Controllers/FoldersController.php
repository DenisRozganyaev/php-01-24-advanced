<?php

namespace App\Controllers;

use App\Models\Folder;
use App\Models\Note;
use App\Validators\Folders\CreateFolderValidator;
use App\Validators\Folders\UpdateFolderValidator;
use Enums\SQL;
use function Core\authId;
use function Core\requestBody;

class FoldersController extends BaseApiController
{
    public function index()
    {
        return $this->response(
            body: Folder::where('user_id', value: authId())
            ->or('user_id', SQL::IS)
            ->orderBy([
                'user_id' => 'ASC',
                'title' => 'ASC'
            ])
            ->get()
        );
    }

    public function show(int $id)
    {
        $folder = Folder::find($id);

        if (!$folder) {
            return $this->response(404, errors: ['message' => 'Folder not found']);
        }

        return $this->response(body: $folder->toArray());
    }

    public function store()
    {
        $data = array_merge(
            requestBody(),
            ['user_id' => authId()]
        );
        $validator = new CreateFolderValidator();

        if ($validator->validate($data) && $folder = Folder::create($data)) {
            return $this->response(body: $folder->toArray());
        }

        return $this->response(422, errors: $validator->getErrors());
    }

    public function update(int $id)
    {
        $folder = Folder::find($id);

        if (!$folder || is_null($folder->user_id) || $folder->user_id !== authId()) {
            return $this->response(403, errors: [
                'message' => 'This resource is forbidden for you'
            ]);
        }

        $data = [
            ...requestBody(),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $validator = new UpdateFolderValidator();

        if ($validator->validate($data) && $folder = $folder->update($data)) {
            return $this->response(body: $folder->toArray());
        }

        return $this->response(422, errors: $validator->getErrors());
    }

    public function destroy($id)
    {
        $folder = Folder::find($id);

        if (!$folder) {
            return $this->response(404, errors: ['message' => 'Resource not found']);
        }

        if (is_null($folder->user_id) || $folder->user_id !== authId()) {
            return $this->response(403, errors: ['message' => 'This resource is forbidden for you']);
        }

        $result = Folder::destroy($id);

        if (!$result) {
            return $this->response(422, errors: ['message' => 'Oops, smth went wrong']);
        }

        return $this->response();
    }

    public function notes(int $folder_id)
    {
        $folder = Folder::find($folder_id);

        if (!is_null($folder->user_id) && $folder->user_id !== authId()) {
            return $this->response(403, errors: ['message' => 'This resource is forbidden for you']);
        }

        $notes = match($folder->title) {
            Folder::GENERAL_FOLDER => Note::where('folder_id', value: $folder_id)
                ->and('user_id', value: authId())->get(),
            default => Note::where('folder_id', value: $folder_id)->get()
        };

        return $this->response(body: $notes);
    }
}
