<?php

namespace App\Exports;

use App\Order;
use App\Quote;
use App\Address;
use App\OrderProduct;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;

class OutputRegularOrdersExport implements FromCollection, Responsable, WithHeadings
{
    use Exportable;

    /**
     * @var string
     */
    private $writerType = Excel::CSV;

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
        set_time_limit(0);
        ini_set('memory_limit', -1);
        
    	$index = 1;
    	$response = collect();

        OrderProduct::with('order.products', 'order.addresses')
	        ->whereHas('product', function($query) {
	            return $query->withoutGlobalScopes()
                    ->where('frozen', false)
                    ->whereNotNull('vendor_code');
	        })
            ->where('was_outputted', false)
            ->whereDate('created_at', '>=', now()->subDays(15))
            ->withoutGlobalScopes()
            ->get()
            ->pluck('order')
            ->unique()
            ->where('confirmed', true)
            ->where('refunded', false)
            ->whereNotIn('order_status_id', [0, 4])
            ->values()
            ->tap(function($collection) use (&$response, &$index) {

                $collection->each(function($order) use (&$response, &$index) {

                    try {

                        if ($order->products->isEmpty()) {
                            return true;
                        }

                        if ($order->quote instanceof Quote) {
                            return true;
                        }
                        
                        foreach ($order->products as $product) {
                            
                            if ($product->frozen === true) {
                                continue;
                            }

                            if (trim($product->vendor_code) === '') {
                                continue;
                            }

                            if (! ($order->shippingAddress instanceof Address)) {
                                continue;
                            }

                            $response->push([
                                $order->id,
                                $order->email,
                                $order->name,
                                $order->shippingAddress->address_1,
                                $order->shippingAddress->address_2,
                                $order->shippingAddress->city,
                                isset($order->shippingAddress) && isset($order->shippingAddress->state) ? $order->shippingAddress->state->name : '',
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

                            try {
                                $product->pivot->was_outputted = true;
                                $product->pivot->was_outputted_at = now();
                                $product->pivot->save();
                            } catch (\Exception $e) {
                                logger($e->getMessage());       
                            }

                            $order->order_status_id = 3;
                            $order->save();
                            $index++;
                        }

                    } catch (\Exception $e) {
                        logger($e->getMessage());
                    }

                }); 
            });

        return $response;
    }
}
