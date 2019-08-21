<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetAllCategories()
    {
        $response = $this->json('GET', '/categories');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'rows' => [
                        '*' => [
                        'category_id',
                        'name',
                        'description',
                        'department_id'
                        ]
                    ],
        ]);
    }

    public function testGetCategoryById()
    {
        $response = $this->json('GET', '/categories/1');
        $response->assertStatus(200)
                ->assertJson([
                        'category_id' => 1,
                        'department_id' => 1,
                        'name' => 'French',
                        'description' => "The French have always had an eye for beauty. One look at the T-shirts below and you'll see that same appreciation has been applied abundantly to their postage stamps. Below are some of our most beautiful and colorful T-shirts, so browse away! And don't forget to go all the way to the bottom - you don't want to miss any of them!"
                    ]);
    }

    public function testGetCategoryOfProduct()
    {
        $response = $this->json('GET', '/categories/inProduct/1');
        $response->assertStatus(200)
                ->assertJson([
                        'category_id' => 1,
                        'department_id' => 1,
                        'name' => 'French'
                    ]);

        $response = $this->json('GET', '/categories/inProduct/37');
        $response->assertStatus(200)
                ->assertJson([
                        'category_id' => 4,
                        'department_id' => 2,
                        'name' => 'Animal'
                    ]);
    }

    public function testGetCategoriesInADepartment()
    {
        $response = $this->json('GET', '/categories/inDepartment/1');
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'rows' => [
                        '*' => [
                        'category_id',
                        'name',
                        'description',
                        'department_id'
                        ]
                    ],
        ]);
    }
}
