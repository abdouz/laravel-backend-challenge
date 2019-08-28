<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    public function testGetAllProducts()
    {
        $response = $this->json('GET', '/products/');
        $response->assertStatus(200)
                ->assertJsonStructure([
                        'paginationMeta' => [
                            'currentPage',
                            'currentPageSize',
                            'totalPages',
                            'totalRecords'
                        ],
                        'rows' => [
                            '*' => [
                                'product_id',
                                'name',
                                'description',
                                'price',
                                'discounted_price',
                                'thumbnail'
                            ]
                        ]                    
                    ]);
    }

    // public function testSearchProducts()
    // {



    //     $response = $this->json('GET', "/products?query_string={$query}&description_length={$desc_len}&");
    //     $response->assertStatus(200)
    //             ->
    // }

    /**
     * Test getting single product by Id
     *
     * @return void
     */
    public function testGetProductById()
    {
        $response = $this->json('GET', '/products/1');
        $response->assertStatus(200)
                ->assertJson([
                        'product_id' => 1,
                        'name' => "Arc d'Triomphe",
                        'description' => 'This beautiful and iconic T-shirt will no doubt lead you to your own triumph.',
                        'price' => 14.99,
                        'discounted_price' => 0.00,
                        'image' => 'arc-d-triomphe.gif',
                        'image_2' => 'arc-d-triomphe-2.gif',
                        'thumbnail' => 'arc-d-triomphe-thumbnail.gif',
                        'display' => 0
                    ]);
    }

    public function testGetProductsInCategory()
    {
        $response = $this->json('GET', '/products/inCategory/1');
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'paginationMeta' => [
                        'currentPage',
                        'currentPageSize',
                        'totalPages',
                        'totalRecords'
                    ],
                    'rows' => [
                        '*' => [
                            'product_id',
                            'name',
                            'description',
                            'price',
                            'discounted_price',
                            'thumbnail'
                        ]
                    ]
            ]);
    }
}
// {
//     "product_id": integer, 
//     "name": string,
//     "description": string,
//     "price": string,
//     "discounted_price": string,
//      "image": string,
//      "image_2": string,
//     "thumbnail": string,
//     "displayl": integer,
// }
