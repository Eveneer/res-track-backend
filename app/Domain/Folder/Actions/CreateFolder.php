<?php

declare(strict_types=1);

namespace App\Domain\Folder\Actions;

use App\Domain\Folder\Folder;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateFolder
{
    use AsAction;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['sometimes', 'exists:folders,id']
        ];
    }

    public function handle(array $data): Folder
    {
        return Folder::create($data);
    }

    public function asController(ActionRequest $request): Folder
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(Folder $folder): array
    {
        return [
            'message' => 'Folder created',
            'data' => [
                'folder' => $folder
            ]
        ];
    }
}