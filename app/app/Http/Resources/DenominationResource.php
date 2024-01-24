<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DenominationResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return $this;
  
        return [
            'ShortLanguage' => $this->short_language_label,
            'Language' => $this->language_label->Description,
            'Type' => $this->type_label->Description,
            'Denomination' => $this->Denomination,
        ];
    }
}
