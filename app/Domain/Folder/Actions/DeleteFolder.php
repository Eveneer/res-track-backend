<?php

declare(strict_types=1);

namespace App\Domain\Folder\Actions;

use App\Domain\Folder\Folder;
use App\Domain\Resource\Actions\DeleteResource;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteFolder
{
    use AsAction;

    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:folders,id']
        ];
    }

    public function handle(array $data): bool
    {
        $folder = Folder::find($data['id']);
        $flag = true;

        foreach ($folder->resources()->get() as $resource) {
            $flag = $flag && DeleteResource::run(['resource_id' => $resource->id])['data']['is_deleted'];
        }

        foreach ($folder->subFolders()->get() as $sub_folder) {
            $flag = $flag && DeleteFolder::run(['id' => $sub_folder->id])['data']['is_deleted'];
        }

        if ($flag) {
            return $folder->delete();
        }

        return false;
    }

    public function asController(ActionRequest $request): bool
    {
        return $this->handle($request->validated());
    }

    public function jsonResponse(bool $is_deleted): array
    {
        $message = $is_deleted ? 'Folder deleted' :'Folder could not be deleted';
        
        return [
            'message' => $message,
            'data' => [
                'message' => $message,
                'is_deleted' => $is_deleted
            ]
        ];
    }
}