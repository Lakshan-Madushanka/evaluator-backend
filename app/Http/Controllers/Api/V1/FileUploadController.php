<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\MediaResource;
use App\Http\Services\FileUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FileUploadController extends Controller
{
    public function index(string $type, string $modelId, FileUploadService $uploadService): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $medias = $uploadService->getAllFiles($type, $modelId);

        return MediaResource::collection($medias);
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function store(string $type, string $id, Request $request, FileUploadService $uploadService): JsonResponse
    {
        $model = $uploadService->validateEndPoint($type, $id);

        $uploadService->checkAuthorized($type);

        $validatedFiles = $uploadService->validateUploadedFiles($request, $type);

        $uploadedImages = $uploadService->execute($model, $validatedFiles);

        return MediaResource::collection($uploadedImages)
            ->toResponse($request)
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function massDelete(string $type, Request $request, FileUploadService $uploadService): JsonResponse
    {
        $uploadService->checkAuthorized($type);

        $validatedIds = $uploadService->validateDeletableFiles($request);

        $uploadService->massDeleteFiles($validatedIds);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }

    public function changeOrder(string $type, Request $request, FileUploadService $uploadService)
    {
        $uploadService->checkAuthorized($type);

        $order = $uploadService->validateToChangeOrder($request);

        $uploadService->changeOrder($order);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
