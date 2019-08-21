<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

/**
 * The controller defined below is the department controller.
 *
 * Class DepartmentController
 * @package App\Http\Controllers
 */
class DepartmentController extends Controller
{
    public function __construct(Department $depart)
    {
        $this->depart = $depart;
    }
    /**
     * This method should return an array of all deparments.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllDepartments()
    {
        $departs = $this->depart->all();
        return response()->json($departs, 200);
    }

    /**
     * This method should return a single Department by Id.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDepartmentById($depart_id)
    {
        $depart = $this->depart->find($depart_id);
        return response()->json($depart, 200);
    }
}