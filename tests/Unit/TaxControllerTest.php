<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaxControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetAllTaxes()
    {
        $response = $this->json('GET', '/tax');
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'tax_id',
                    'tax_type',
                    'tax_percentage'
                ]
            ]);
    }

    public function tetGetTaxById()
    {
        $response = $this->json('GET', '/tax/1');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'tax_id',
                'tax_type',
                'tax_percentage'
            ]);
    }
}
