<?php

declare(strict_types=1);

namespace App\Domain\Folder\Actions;

use App\Domain\Folder\Folder;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateFolder
{
    use AsAction;

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:folders,id'],
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['sometimes', 'exists:folders,id']
        ];
    }

    public function handle(array $data): Folder
    {
        $folder = Folder::find($data['id']);
        $folder->fill($data);
        $folder->save();

        return $folder;
    }

    public function asController(ActionRequest $request): Folder
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(Folder $folder): array
    {
        return [
            'message' => 'Folder updated',
            'data' => [
                'folder' => $folder
            ]
        ];
    }
}