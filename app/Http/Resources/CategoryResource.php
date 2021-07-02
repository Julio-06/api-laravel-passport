<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            //SOLO SE MOSTRARA LA RELACION EN EL CASO DE QUE LA HAYAMOS SOLICITADO
            'posts' => PostResource::collection($this->whenLoaded('posts'))
        ];
    }
}
