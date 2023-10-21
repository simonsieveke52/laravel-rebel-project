<?php

use App\Product;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;

$factory->define(Product::class, function (Faker $faker) {

    // product sku
    $sku = $faker->lexify('?????');

    $name = $faker->sentence;

    $cost = $faker->numberBetween(1, 30);
    $price = $cost * 1.35;

    return [
        'name' => $name,
        'slug' => Str::slug($name),
        'cost' => $cost,
        'price' => $price,
        'status' => 1,
        'quantity' => $faker->numberBetween(0, 100),
        'sku' => $sku . "-" . rand(100,999),
        'upc' => $sku . '-UPC',
        'description' => $faker->text,
        'item_features' => $faker->text,
        'quantity_per_case' => $faker->randomDigit,
        'clicks_counter' => $faker->randomDigit,
        'sales_count' => $faker->randomDigit
    ];
});