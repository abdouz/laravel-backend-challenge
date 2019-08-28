<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttributeControllerTest extends TestCase
{
    /**
     * test getting all attributes
     *
     * @return void
     */
    public function testGetAllAttributes()
    {
        $response = $this->json('GET', '/attributes');

        $response->assertStatus(200)
                ->assertJsonStructure([
                        '*' => [
                        'attribute_id',
                        'name'
                    ],
        ]);
    }

    public function testGetAttributeById()
    {
        $response = $this->json('GET', '/attributes/1');
        $response->assertStatus(200)
                ->assertJson([
                        'attribute_id' => 1,
                        'name' => 'Size',
                    ]);

        $response = $this->json('GET', '/attributes/2');
        $response->assertStatus(200)
                ->assertJson([
                        'attribute_id' => 2,
                        'name' => 'Color',
                    ]);
    }

    public function testGetAttributeValues()
    {
        $response = $this->json('GET', '/attributes/values/1');
        $response->assertStatus(200)
                ->assertJson([[
                            'attribute_value_id' => 1,
                            'value' => 'S',
                        ],
                        [
                            'attribute_value_id' => 2,
                            'value' => 'M',
                        ],
                        [
                            'attribute_value_id' => 3,
                            'value' => 'L',
                        ],
                        [
                            'attribute_value_id' => 4,
                            'value' => 'XL',
                        ],
                        [
                            'attribute_value_id' => 5,
                            'value' => 'XXL',
                        ],
                    ]);
    }

    public function testGetProductAttributes()
    {
        $response = $this->json('GET', '/attributes/inProduct/1');
        $response->assertStatus(200)
                ->assertJsonStructure([
                        '*' => [
                            'attribute_name',
                            'attribute_value_id',
                            'attribute_value'
                        ]
                    ]);
    }
}
