<?php

namespace App\Http\Resources\API\V1\CMS\Home;

use Illuminate\Http\Resources\Json\JsonResource;

class CategorySectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'main_text' => $this->main_text,
            'men' => $this->formatSection($this->v1),
            'women' => $this->formatSection($this->v2),
            'children' => $this->formatSection($this->v3),
            'status' => $this->status,
        ];
    }

    private function formatSection($data)
    {
        if (!$data) return null;

        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        return [
            'image' => isset($data['image']) ? asset($data['image']) : null,
            'title' => $data['title'] ?? null,
            'sub_title' => $data['sub_title'] ?? null,
            'button_link' => $data['button_link'] ?? null,
            'button_text' => $data['button_text'] ?? null,
        ];
    }
}
