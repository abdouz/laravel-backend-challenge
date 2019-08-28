<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * The controller defined below is the attribute controller.
 * Some methods needs to be implemented from scratch while others may contain one or two bugs.
 *
 * NB: Check the BACKEND CHALLENGE TEMPLATE DOCUMENTATION in the readme of this repository to see our recommended
 *  endpoints, request body/param, and response object for each of these method
 *
 *
 * Class AttributeController
 * @package App\Http\Controllers
 */
class BaseController extends Controller
{
    protected function getParam(Request $request, $name, $validation, $default)
    {
        $data = $request->validate(["{$name}" => "{$validation}"]);
        if(!array_key_exists($name, $data) || empty($data[$name]))
        {
            return $default;
        }
        else
        {
            return $data[$name];
        }
    }
}