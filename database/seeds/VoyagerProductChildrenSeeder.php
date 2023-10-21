<?php

use App\Product;
use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataType;

class VoyagerProductChildrenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataType = DataType::where('name', 'products')->first();

        \DB::table('data_rows')->where('field', 'item_id')->delete();

        \DB::table('data_rows')
            ->where('data_type_id', $dataType->id)
            ->where('field', 'quantity')->update([
                'display_name' => 'Quantity'
            ]);

        \DB::table('data_rows')
            ->whereIn('field', ['parent_id', 'product_hasmany_product_relationship'])
            ->where('data_type_id', $dataType->id)
            ->delete();

        \DB::table('data_rows')
            ->whereIn('field', [
                'secondary_slug',
                'inventory_status',
                'is_fba',
                'brand_id',
                'manufacture_id',
                'availability_id',
                'homepage_order',
                'item_features',
                'clicks_counter',
                'sales_count',
                'created_at',
                'frozen',
                '_lft',
                '_rgt',
            ])
            ->where('data_type_id', $dataType->id)
            ->update([
                'browse' => 0,
                'read' => 0,
                'edit' => 0,
                'add' => 0,
            ]);

        \DB::table('data_rows')->whereIn('field', [
                'google_product_category',
                'searchable_text',
                'vendor_weight',
                'free_shipping',
                'size_uom',
                'size',
                'pack',
                'price_backup',
                'slug',
                'short_description',
                'description',
                'upc',
                'mpn',
            ])
            ->where('data_type_id', $dataType->id)
            ->update(['browse' => 0]);

        $order = \DB::table('data_rows')->where('data_type_id', $dataType->id)->max('order');

        $rows = [
            [
                'data_type_id' => $dataType->id,
                'field' => 'parent_id',
                'type' => 'text',
                'display_name' => 'Parent id',
                'required' => 0,
                'browse' => 0,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => '{}',
            ],
            [
                'data_type_id' => $dataType->id,
                'field' => 'product_hasmany_product_relationship',
                'type' => 'relationship',
                'display_name' => 'Parent',
                'required' => 0,
                'browse' => 1,
                'read' => 1,
                'edit' => 1,
                'add' => 1,
                'delete' => 1,
                'details' => json_encode([
                    'model' => Product::class,
                    'table' => 'products',
                    'type' => 'belongsTo',
                    'column' => 'parent_id',
                    'key' => 'id',
                    'label' => 'name',
                    'pivot_table' => '',
                    'pivot' => '0',
                    'taggable' => '0',
                ]),
            ],
        ];

        

        foreach ($rows as $key => &$row) {
            $row['order'] = $order + 1 + $key;
        }

        \DB::table('data_rows')->insert($rows);

        Product::fixTree();
    }
}
