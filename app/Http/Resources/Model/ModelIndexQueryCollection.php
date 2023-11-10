<?php

namespace App\Http\Resources\Model;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ModelIndexQueryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'models' => $this->collection->map(function ($model) {
                return [
                    'id' => $model->id,
                    'name' => $model->name,
                    'code' => $model->code,
                    'description' => $model->description,
                    'created_at' => Carbon::parse($model->created_at)->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::parse($model->updated_at)->format('Y-m-d H:i:s'),
                    'deleted_at' => $model->deleted_at
                ];
            }),
            'meta' => [
                'pagination' => [
                    'total' => $this->total(),
                    'count' => $this->count(),
                    'per_page' => $this->perPage(),
                    'current_page' => $this->currentPage(),
                    'total_pages' => $this->lastPage(),
                ],
            ],
        ];
    }
}
