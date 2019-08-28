<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;

class CustomerControllerTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();
    }

    /**
     * test creating a new customer profile
     *
     * @return void
     */
    public function testCreateCustomer()
    {
        $response = $this->json('POST', '/customers', ['name' => 'Sally Sam', 'email' => 'sally@gmail.com', 'password' => 'abc672!Mon']);
        $response->assertStatus(201)
            ->assertJsonStructure([
                'customer' => [
                    'customer_id',
                    'name',
                    'email',
                    'address_1',
                    'address_2',
                    'city',
                    'region',
                    'postal_code',
                    'shipping_region_id',
                    'credit_card',
                    'day_phone',
                    'eve_phone',
                    'mob_phone'
                ],
                'accessToken',
                'expiresIn'
            ]);
    }

    public function testLogin()
    {
        $customer = factory(\App\Models\Customer::class)->create();
        $response = $this->json('POST', '/customers/login', ['email' => $customer->email, 'password' => $customer->password]);

        $response->assertStatus(200)
            ->assertJson([
                'customer_id' => $customer->customer_id,
                'name' => $customer->name,
                'email' => $customer->email,
                'address_1' => null,
                'address_2' => null,
                'city' => null,
                'region' => null,
                'postal_code' => null,
                'shipping_region_id' => 1,
                'credit_card' => null,
                'day_phone' => null,
                'eve_phone' => null,
                'mob_phone' => null
            ]);
    }

    // working but requires token
    // public function testFbLogin()
    // {
    //     $fb_token = "EAAMdVHSzcdIBAKuNdOvb9qS4jAtVIMxnthxeDvM5bdOITeMlmPVyUlOJc7rXVYUhdKmwy0wWjCZA0vuYEgD0FLSOZBDJ4QVqCDdeX1jGXrYrPLJczYSmfLN0q6pAkG7AAd4nbfPKZCsMyIhOqlORbfpBepv3VIJJG1DN7NVoDvOR5jJTTTBFSMlk1ZCsFAROoK5yXyUZAdgZDZD";

    //     $customer = factory(\App\Models\Customer::class)->create();
    //     $response = $this->json('POST', '/customers/facebook', ['access_token' => $fb_token]);

    //     $response->assertStatus(200)
    //         ->assertJson([
    //             'customer_id' => $customer->customer_id,
    //             'name' => $customer->name,
    //             'email' => $customer->email,
    //             'address_1' => null,
    //             'address_2' => null,
    //             'city' => null,
    //             'region' => null,
    //             'postal_code' => null,
    //             'shipping_region_id' => 1,
    //             'credit_card' => null,
    //             'day_phone' => null,
    //             'eve_phone' => null,
    //             'mob_phone' => null
    //         ]);
    // }

    // public function testGetCustomerProfile()
    // {
        
    // }

    // public function testUpdateCustomerProfile()
    // {

    // }

    // public function testUpdateCustomerAddress()
    // {

    // }

    // public function testUpdateCustomerCreditCard()
    // {
        
    // }

    public function tearDown() : void
    {
        DB::table('customer')->truncate();
        parent::tearDown();
    }

    /**
     * 
     */
}

// EAAMdVHSzcdIBAInbrktc5ZCsyiDvgnAbf5L5d90gU1XWcAGy2FkU5kp3LRAbOQ3Lf515F3rZCxWH1e9LQ3zoXdYUIp7Hos0ZBRWVlWaOyzZAzasZCZBM4z4on9fFezhwdm3PUiZCWI9ivayCgyUZAjjWO62aZByhuiuu30whNOjMEHFDG3rywAekmRZArLBwL99ZBhZCYz97sFIPBrZBa1ub4DV5R
