<?php

namespace App\Http\Controllers;

use App\DTO\LinkDTO;
use App\Http\Requests\LinkRequest;
use App\Models\Link;
use App\Services\Link\LinkService;
use Illuminate\Http\JsonResponse;
use App\Constants\ResponseMessage;

class LinkController extends BaseController
{
    public function __construct(private LinkService $linkService)
    {
    }
    public function list(): JsonResponse
    {
        $result = $this->linkService->list();

        return $this->response($result);
    }

    public function store(LinkRequest $request): JsonResponse
    {
        $data = $request->validated();
        $linkDTO = new LinkDTO(...$data);

        $result = $this->linkService->store($linkDTO);

        if ($result['status']) {
            $result['message'] = ResponseMessage::LINK_CREATED;
        }

        return $this->response($result);
    }

    public function update(LinkRequest $request, Link $link): JsonResponse
    {
        $data = $request->validated();
        $linkDTO = new LinkDTO(...$data);

        $result = $this->linkService
            ->setLink($link)
            ->update($linkDTO);

        if ($result['status']) {
            $result['message'] = ResponseMessage::LINK_UPDATED;
        }

        return $this->response($result);
    }

    public function delete(Link $link): JsonResponse
    {
        $result = $this->linkService
            ->setLink($link)
            ->delete();

        if ($result['status']) {
            $result['message'] = ResponseMessage::LINK_DELETED;
        }

        return $this->response($result);
    }
}
