<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\DB;

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
class AttributeController extends Controller
{
    /**
     * Default controller constructor, catches the model to be used by the controller
     * 
     * @param Attribute $attrib [Attribute Model Class Instance]
     */
    public function __construct(Attribute $attrib, AttributeValue $attrib_val)
    {
        $this->attrib = $attrib;
        $this->attrib_val = $attrib_val;
    }
    /**
     * This method should return an array of all attributes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllAttributes()
    {
        $attribs = $this->attrib->all();
        return response()->json($attribs, 200);
    }

    /**
     * This method should return a single attribute using the attribute_id in the request parameter.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttributeById($attrib_id)
    {
        $attrib = $this->attrib->find($attrib_id);
        return response()->json($attrib, 200);
    }

    /**
     * This method should return an array of all attribute values of a single attribute using the attribute id.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttributeValues($attrib_id)
    {
        // get attrib. vals. from intermediate model
        // only select id and name as per the API reqs.
        $vals = $this->attrib->find($attrib_id)->values()->select(['attribute_value_id', 'value'])->get()->all();
        // -- next line makes same thing as prev differently
        //$vals = $this->attrib_val->select(['attribute_value_id', 'value'])->where('attribute_id', $attrib_id)->get()->all();
        return response()->json($vals);
    }

    /**
     * This method should return an array of all the product attributes.
     *  [
     *     {
     *          "attribute_name": string, 
     *          "attribute_value_id": integer, 
     *          "attribute_value": string,
     *      }, {
     *      },
     *  ]
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductAttributes($product_id)
    {
        // ---
        // This API end-point requires two joins to get the values as in the specs.
        // It is better to spin-back to the DB fluent SQL Builder instead of using Eloquent
        // 
        // The Reference SQL Join i created and tested for this query before proceeding
        // ---
        // SELECT av.`attribute_value_id`, a.`name` as attribute_name, av.`value` as attribute_value
        // from attribute_value as av, attribute as a, product_attribute as pa 
        // where av.`attribute_value_id` = pa.`attribute_value_id` and a.`attribute_id` = av.`attribute_id` and pa.`product_id` = 1;
        // ----

        $product_attribs = DB::table('attribute')
            ->join('attribute_value', 'attribute.attribute_id', '=', 'attribute_value.attribute_id')
            ->join('product_attribute', 'attribute_value.attribute_value_id', '=', 'product_attribute.attribute_value_id')
            ->select('attribute_value.attribute_value_id', 
                     'attribute.name as attribute_name', 
                     'attribute_value.value as attribute_value')->where('product_attribute.product_id', '=', $product_id)->get();
        return response()->json($product_attribs);
    }
}


