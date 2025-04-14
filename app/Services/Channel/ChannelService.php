<?php

namespace App\Services\Channel;

use App\DTO\ChannelDTO;
use App\Models\Channel;
use Exception;

class ChannelService
{
    private Channel $channel;

    public function setChannel(Channel $channel): self
    {
        $this->channel = $channel;
        return $this;
    }

    public function list(): array
    {
        try {
            $channels = Channel::where('user_id', currentUser()->id)
                ->get();

            return [
                'status' => true,
                'data' => $channels
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage(),
                'statusCode' => 400
            ];
        }
    }

    public function store(ChannelDTO $data): array
    {
        try {
            $channel = Channel::create([
                'name' => $data->name,
                'description' => $data->description,
                'image' => $data->image,
                'user_id' => $data->user_id,
            ]);

            return [
                'status' => true,
                'data' => $channel
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage(),
                'statusCode' => 400
            ];
        }
    }

    public function update(ChannelDTO $data): array
    {
        try {
            $this->channel->update([
                'name' => $data->name,
                'description' => $data->description,
                'image' => $data->image,
                'user_id' => $data->user_id,
            ]);

            return [
                'status' => true,
                'data' => $this->channel
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage(),
                'statusCode' => 400
            ];
        }
    }

    public function delete(): array
    {
        try {
            $this->channel->delete();

            return [
                'status' => true,
                'data' => ['deletedChannel' => $this->channel->name]
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage(),
                'statusCode' => 400
            ];
        }
    }
}
