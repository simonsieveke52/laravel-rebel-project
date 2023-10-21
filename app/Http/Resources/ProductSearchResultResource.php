<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductSearchResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
           'id'           => $this->id,
           'name'         => $this->name,
           'sku'          => $this->sku,
           'vendor_code'  => $this->vendor_code,
           'image'        => $this->main_image,
           'images'       => $this->whenLoaded('images'),
           'price'        => $this->price,
           'cost'         => $this->cost,
           'shippingCost' => $this->shippingCost,
           'pivot'        => [
              'discount_amount' => "0.00",
              'free_shipping'   => false,
              'price'           => $this->price,
              'product_id'      => $this->id,
              'quantity'        => '',
           ],
        ];
    }
}
