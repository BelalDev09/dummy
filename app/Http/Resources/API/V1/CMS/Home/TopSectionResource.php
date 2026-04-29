<?php

namespace App\Http\Resources\API\V1\CMS\Home;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TopSectionResource extends JsonResource
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
            'title' => $this->title,
            'sub_title' => $this->sub_title,
            'gallery' => collect($this->gallery ?? [])->map(function ($img) {
                return $img ? asset('storage/' . $img) : null;
            })->filter()->values(),
            'status' => $this->status,
        ];
    }
}
