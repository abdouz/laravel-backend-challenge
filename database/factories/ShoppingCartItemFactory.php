<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\ShoppingCart;
use Faker\Generator as Faker;

$factory->define(ShoppingCart::class, function ($cart_id) {
    $items = [
        ['cart_id' => $cart_id, 'product_id' => 1, 'attributes' => 'Color:White,Size:S', 'quantity' => 3, 'buy_now' => 0, 'added_on' => now()],
        ['cart_id' => $cart_id, 'product_id' => 2, 'attributes' => 'Color:Black,Size:M', 'quantity' => 5, 'buy_now' => 0, 'added_on' => now()],
        ['cart_id' => $cart_id, 'product_id' => 3, 'attributes' => 'Color:Red,Size:L', 'quantity' => 7, 'buy_now' => 0, 'added_on' => now()],
        ['cart_id' => $cart_id, 'product_id' => 4, 'attributes' => 'Color:Orange,Size:XL', 'quantity' => 6, 'buy_now' => 0, 'added_on' => now()],
        ['cart_id' => $cart_id, 'product_id' => 5, 'attributes' => 'Color:Yellow,Size:XXL', 'quantity' => 4, 'buy_now' => 0, 'added_on' => now()],
        ['cart_id' => $cart_id, 'product_id' => 6, 'attributes' => 'Color:Green,Size:S', 'quantity' => 2, 'buy_now' => 0, 'added_on' => now()],
        ['cart_id' => $cart_id, 'product_id' => 7, 'attributes' => 'Color:Blue,Size:M', 'quantity' => 20, 'buy_now' => 0, 'added_on' => now()],
        ['cart_id' => $cart_id, 'product_id' => 8, 'attributes' => 'Color:Indigo,Size:M', 'quantity' => 110, 'buy_now' => 0, 'added_on' => now()],
        ['cart_id' => $cart_id, 'product_id' => 9, 'attributes' => 'Color:White,Size:M', 'quantity' => 22, 'buy_now' => 0, 'added_on' => now()],
        ['cart_id' => $cart_id, 'product_id' => 10, 'attributes' => 'Color:Black,Size:XXL', 'quantity' => 22, 'buy_now' => 0, 'added_on' => now()],
    ];
    return $items[rand(0, 9)];
});
