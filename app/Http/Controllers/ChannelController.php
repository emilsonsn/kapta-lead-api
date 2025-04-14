<?php

namespace App\Http\Controllers;

use App\DTO\ChannelDTO;
use App\Http\Requests\StoreChannelRequest;
use App\Http\Requests\UpdateChannelRequest;
use App\Models\Channel;
use App\Services\Channel\ChannelService;
use Illuminate\Http\JsonResponse;

class ChannelController extends BaseController
{
    public function __construct(private ChannelService $channelService)
    {
    }

    public function list(): JsonResponse
    {
        $result = $this->channelService->list();

        return $this->response($result);
    }

    public function store(StoreChannelRequest $request): JsonResponse
    {
        $data = $request->validated();
        $dto = ChannelDTO::fromArray($data);

        $result = $this->channelService->store($dto);

        if ($result['status']) {
            $result['message'] = 'Canal criado com sucesso';
        }

        return $this->response($result);
    }

    public function update(UpdateChannelRequest $request, Channel $channel): JsonResponse
    {
        $data = $request->validated();
        $dto = ChannelDTO::fromArray($data);

        $result = $this->channelService
            ->setChannel($channel)
            ->update($dto);

        if ($result['status']) {
            $result['message'] = 'Canal atualizado com sucesso';
        }

        return $this->response($result);
    }

    public function delete(Channel $channel): JsonResponse
    {
        $result = $this->channelService
            ->setChannel($channel)
            ->delete();

        if ($result['status']) {
            $result['message'] = 'Canal deletado com sucesso';
        }

        return $this->response($result);
    }
}
