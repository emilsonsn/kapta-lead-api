<?php

namespace App\Services\Link;

use App\Constants\ResponseMessage;
use App\DTO\LinkDTO;
use App\Models\Link;
use Exception;

class LinkService
{
    private Link $link;

    public function setLink(Link $link): self
    {
        $this->link = $link;
        return $this;
    }

    public function list(): array
    {
        try {
            $links = Link::where('user_id', currentUser()->id)
                ->get();

            return [
                'status' => true,
                'data' => $links
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage(),
                'statusCode' => 400
            ];
        }
    }

    public function store(LinkDTO $linkDTO): array
    {
        try {
            $currentUser = currentUser();

            $plan = $currentUser->plan;

            if (!$plan || $currentUser->links()->count() >= $plan->subscriptionPlan->limit) {
                throw new Exception(ResponseMessage::LINK_LIMIT_REACHED);
            }

            $link = Link::create([
                'channel_id' => $linkDTO->channel_id,
                'user_id' => currentUser()->id,
                'description' => $linkDTO->description,
                'destination_url' => $linkDTO->destination_url,
                'hash' => $linkDTO->hash ?? md5(uniqid())
            ]);

            return [
                'status' => true,
                'data' => $link
            ];
        } catch (Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage(),
                'statusCode' => 400
            ];
        }
    }

    public function update(LinkDTO $linkDTO): array
    {
        try {
            $this->link->update([
                'channel_id' => $linkDTO->channel_id,
                'description' => $linkDTO->description,
                'destination_url' => $linkDTO->destination_url,
            ]);

            return [
                'status' => true,
                'data' => $this->link
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
            $this->link->delete();

            return [
                'status' => true
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
