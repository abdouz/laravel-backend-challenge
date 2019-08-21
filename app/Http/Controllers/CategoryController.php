<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\ProductCategory;

/**
 * The controller defined below is the category controller.
 *
 * Class DepartmentController
 * @package App\Http\Controllers
 */
class CategoryController extends Controller
{
    /**
     * Default controller constructor, catches the Category and ProductCategory Models
     * @param Category        $categ         [Category Model Class Instance]
     * @param ProductCategory $product_categ [ProductCategory Model Class Instance]
     */
    public function __construct(Category $categ, ProductCategory $product_categ)
    {
        $this->categ = $categ;
        $this->product_categ = $product_categ;
    }
    
    /**
     * This method should return an array of all categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCategories()
    {
        $categs = $this->categ->all();
        // put the returned array in 'rows' to match JSON API specs.
        $rows = ['rows' => $categs];
        return response()->json($rows, 200);
    }

    /**
     * This method should return a single Category by Id.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategoryById($categ_id)
    {
        $categ = $this->categ->find($categ_id);
        return response()->json($categ, 200);
    }

    /**
     * This method should return a specific Product category.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategoryOfProduct($product_id)
    {
        // get category of product from intermediate model
        $product_categ = $this->product_categ->where('product_id', $product_id)->get()->first();
        // only select ids and name as per the API reqs.
        $categ = $this->categ->select(['category_id', 'department_id', 'name'])->find($product_categ->category_id);
        return response()->json($categ);
    }

    /**
     * This method should return a specific Department categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategoriesInDepartment($depart_id)
    {
        $categs = $this->categ->where('department_id', $depart_id)->get()->all();

        // put the returned array in 'rows' to match JSON API specs.
        $rows = ['rows' => $categs];
        return response()->json($rows);
    }
}