<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LinkRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'channel_id' => 'required|exists:channels,id',
            'description' => 'required|string',
            'destination_url' => 'required|url',
        ];
    }
}
