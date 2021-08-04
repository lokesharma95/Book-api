<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Outputing data in formatted data for user
        return [
            'uuid' => $this->id,
            'name' => ucwords($this->name),
            'releaseDate' => Carbon::createFromTimestamp($this->releaseDate)->format('Y-m-d'),
            'authorName' => $this->authorName,
        ];
    }
}
