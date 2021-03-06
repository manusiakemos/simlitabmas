<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WidgetResource extends JsonResource
{

    public function toArray($request)
    {
        $total  = session()->get('total');
        return [
            'ss_id' => $this->ss_id,
            'label' => $this->label,
            'value' => $this->value,
            'total' => $total,
            'percent' => $this->value / $total * 100
        ];
    }
}
