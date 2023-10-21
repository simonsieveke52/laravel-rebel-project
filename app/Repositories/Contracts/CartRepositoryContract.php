<?php

namespace App\Repositories\Contracts;

use App\{Product, Shipping};
use Illuminate\Support\Collection;

interface CartRepositoryContract
{
    public function addToCart(Product $product, int $int, $options = []);

    public function getCartItems() : Collection;

    public function remove(int $rowId);

    public function countItems() : int;

    public function getSubTotal();

    public function getTotal();

    public function updateQuantityInCart(int $rowId, int $quantity);

    public function findItem(int $rowId);
}
