<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DepartmentControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetAllDepartments()
    {
        $response = $this->json('GET', '/departments');

        // API Specs.         
        // [
        //        {
        //            "department_id": integer, 
        //            "name": string,
        //            "description" : string,
        //        },
        //        {
        //            "department_id": integer, 
        //            "name": string,
        //            "description" : string,
        //        },
        // ]

        // $pager_struct = [
        //         'current_page',
        //         'first_page_url',
        //         'from',
        //         'last_page',
        //         'last_page_url',
        //         'next_page_url',
        //         'path',
        //         'per_page',
        //         'prev_page_url',
        //         'to',
        //         'total',
        //         'data' => []
        // ];

        // $exp_struct = $pager_struct;
        // $exp_struct['data'] = [
        //           '*' => [
        //             'department_id',
        //             'name',
        //             'description',
        //           ]
        // ];

        // $response->assertStatus(202)
        //         ->assertJsonStructure($exp_struct);

        $response->assertStatus(200)
                ->assertJsonStructure([
                  '*' => [
                    'department_id',
                    'name',
                    'description',
                  ]
        ]);
    }

    public function testGetDepartmentById()
    {
        $response = $this->json('GET', '/departments/1');
        $response->assertStatus(200)
                ->assertJson([
                        'department_id' => 1,
                        'name' => 'Regional',
                        'description' => 'Proud of your country? Wear a T-shirt with a national symbol stamp!'
                    ]);

        $response = $this->json('GET', '/departments/2');
        $response->assertStatus(200)
                ->assertJson([
                        'department_id' => 2,
                        'name' => 'Nature',
                        'description' => 'Find beautiful T-shirts with animals and flowers in our Nature department!'
                    ]);
    }
}
