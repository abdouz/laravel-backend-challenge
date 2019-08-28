<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\ShoppingCart;

class ShoppingCartControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->test_cart_id = str_replace('-', '', (string) Str::uuid());
    }

    public function testGenerateUniqueId()
    {
        $response = $this->json('GET', '/shoppingcart/generateUniqueId');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'cart_id'
            ]);
    }

    public function testAddProduct()
    {
        $items = [
            ['cart_id' => $this->test_cart_id, 'product_id' => 1, 'attributes' => 'Color:White,Size:S', 'quantity' => 3],
            ['cart_id' => $this->test_cart_id, 'product_id' => 2, 'attributes' => 'Color:Black,Size:M', 'quantity' => 5],
            ['cart_id' => $this->test_cart_id, 'product_id' => 3, 'attributes' => 'Color:Red,Size:L', 'quantity' => 7],
            ['cart_id' => $this->test_cart_id, 'product_id' => 4, 'attributes' => 'Color:Orange,Size:XL', 'quantity' => 6],
            ['cart_id' => $this->test_cart_id, 'product_id' => 5, 'attributes' => 'Color:Yellow,Size:XXL', 'quantity' => 4],
            ['cart_id' => $this->test_cart_id, 'product_id' => 6, 'attributes' => 'Color:Green,Size:S', 'quantity' => 2],
            ['cart_id' => $this->test_cart_id, 'product_id' => 7, 'attributes' => 'Color:Blue,Size:M', 'quantity' => 20],
            ['cart_id' => $this->test_cart_id, 'product_id' => 8, 'attributes' => 'Color:Indigo,Size:M', 'quantity' => 110]
        ];

        foreach( $items as $item )
        {
            $response = $this->json('POST', '/shoppingcart/add', $item);
            // print_r($item);
            // echo $response->getContent();
            $response->assertStatus(201)
                ->assertJsonStructure([
                    'item_id',
                    'cart_id',
                    'product_id',
                    'attributes',
                    'quantity'
                ]);
        }
    }

    public function testGetCartDetails()
    {
        $response = $this->json('GET', '/shoppingcart/'.$this->test_cart_id);
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'item_id',
                    'cart_id',
                    'name',
                    'attributes',
                    'product_id',
                    'image',
                    'price',
                    'discounted_price',
                    'quantity',
                    'subtotal']]);
    }

    public function testUpdateItemQnt()
    {
        $shopping_cart_item = factory(\App\Models\ShoppingCart::class)->create(['cart_id' => $this->test_cart_id]);
        $old_qnt = $shopping_cart_item->quantity;
        $new_qnt = 11 + $old_qnt;

        $response = $this->json('PUT', '/shoppingcart/update/'.$shopping_cart_item->item_id, 
            ['quantity' =>  $new_qnt]);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'quantity' => $new_qnt])
            ->assertJsonStructure([
                    'item_id',
                    'cart_id',
                    'attributes',
                    'product_id',
                    'quantity']);

        $shopping_cart_item->forceDelete();
    }

    public function testEmptyCart()
    {
        for($i=0; $i< 5; $i++)
        {
            factory(\App\Models\ShoppingCart::class)->create(['cart_id' => $this->test_cart_id]);
        }

        $response = $this->json('DELETE', '/shoppingcart/empty/'.$this->test_cart_id);
        $response->assertStatus(200)
            ->assertJsonStructure([]);
    }

    public function testRemoveItemFromCart()
    {
        $shopping_cart_item = factory(\App\Models\ShoppingCart::class)->create(['cart_id' => $this->test_cart_id]);

        $response = $this->json('DELETE', '/shoppingcart/removeProduct/'.$shopping_cart_item->item_id);
        $response->assertStatus(200)
            ->assertJsonStructure(['message']);
    }

    public function testCreateOrder()
    {
        for($i=0; $i< 5; $i++)
        {
            factory(\App\Models\ShoppingCart::class)->create(['cart_id' => $this->test_cart_id]);
        }

        $response = $this->json('POST', '/orders', ['cart_id' => $this->test_cart_id, 'shipping_id' => 1, 'tax_id' => 1]);
        $response->assertStatus(200)
            ->assertJsonStructure(['order_id']);
    }

    public function tearDown() : void
    {
        DB::table('shopping_cart')->truncate();
        //DB::table('order_detail')->truncate();
        //DB::table('orders')->truncate();
        parent::tearDown();
    }
}