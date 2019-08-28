<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use App\Models\ShippingRegion;
use Illuminate\Http\Request;

/**
 * The Shipping Controller contains all the methods that handles all shipping request
 * This piece of code work fine, but you can test and debug any detected issue
 *
 * Class ShippingController
 * @package App\Http\Controllers
 */
class ShippingController extends Controller
{
    public function __construct(Shipping $ship, ShippingRegion $ship_reg)
    {
        $this->shipping = $ship;
        $this->shipping_region = $ship_reg;
    }

    /**
     * Returns a list of all shipping region.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getShippingRegions()
    {
        return response()->json($this->shipping_region->all(), 200);
    }

    /**
     * Returns a list of shipping type in a specific shipping region.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getShippingInRegion($shipping_region_id)
    {
        return response()->json($this->shipping->find($shipping_region_id)->all(), 200);
    }
}
