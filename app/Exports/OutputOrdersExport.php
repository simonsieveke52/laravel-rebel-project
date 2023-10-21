<?php

namespace App\Exports;

use App\Order;
use App\OrderProduct;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;

class OutputOrdersExport implements FromCollection, Responsable, WithHeadings
{
    use Exportable;

    /**
     * @var string
     */
    private $writerType = Excel::CSV;

    /**
     * @var array
     */
    protected $ids;

    /**
     * @param array $ids
     */
    public function __construct(array $ids = [])
    {
        $this->ids = $ids;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'PONumber',
            'Email',
            'ShipToName',
            'ShipToAddress1',
            'ShipToAddress2',
            'ShipToCity',
            'ShipToState',
            'ShipToZip',
            'ShipToPhone',
            'ItemNumber1',
            'ItemNumber2',
            'Description',
            'Quantity',
            'Shipping Code',
            'UOM',
            'Sequence',
            'Export timestamp',
            'Custom Notes',
            'Availability'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $index = 1;
        $response = collect();

        Order::find($this->ids)->each(function($order) use (&$response, &$index) {

            $order->products->each(function($product) use (&$response, $order, &$index) {

                $response->push([
                    $order->id,
                    $order->email,
                    $order->name,
                    $order->shippingAddress->address_1,
                    $order->shippingAddress->address_2,
                    $order->shippingAddress->city,
                    $order->shippingAddress->state->name,
                    $order->shippingAddress->zipcode,
                    $order->phone,
                    $product->vendor_code,
                    '',
                    $product->name,
                    $product->pivot->quantity,
                    $product->getShippingCodeAttribute($order->shippingAddress->zipcode),
                    $product->size_uom,
                    $index,
                    now()->format('Y-m-d H:i:s'),
                    $order->custom_notes,
                    $product->quantity > 0 ? 'in stock' : 'out of stock',
                ]);

                $product->pivot->was_outputted = true;
                $product->pivot->save();

                $index++;
            });
        }); 
      
        return $response;
    }
}
