<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShippingControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetAllShippingRegions()
    {
        $response = $this->json('GET', '/shipping/regions');
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'shipping_region_id',
                    'shipping_region',
                ]
            ]);
    }

    public function testGetAllShippingsInRegions()
    {
        $response = $this->json('GET', '/shipping/regions/2');
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'shipping_id',
                    'shipping_type',
                    'shipping_cost',
                    'shipping_region_id',
                ]
            ]);
    }
}