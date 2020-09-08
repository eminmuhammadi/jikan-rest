<?php


namespace App\Http\Resources\V4;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CharacterMangaCollection extends ResourceCollection
{

    /**
     * The resource that this resource collects.

     */
    public $collects = 'App\Http\Resources\V4\CharacterMangaResource';

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
}