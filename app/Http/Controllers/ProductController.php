<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

/**
 * The Product controller contains all methods that handles product request
 * Some methods work fine, some needs to be implemented from scratch while others may contain one or two bugs/
 *
 *  NB: Check the BACKEND CHALLENGE TEMPLATE DOCUMENTATION in the readme of this repository to see our recommended
 *  endpoints, request body/param, and response object for each of these method.
 */
class ProductController extends BaseController
{
    public function __construct(Product $product, Category $category, Review $review)
    {
        $this->product = $product;
        $this->category = $category;
    }

    private function formatProductRows(array $data)
    {
        return [
            'paginationMeta' => [
                'currentPage' => $data['current_page'],
                'currentPageSize' => (int) $data['to'] - (int) $data['from'] + 1,
                'totalPages' => $data['last_page'],
                'totalRecords' => $data['total']
            ],
            'rows' => $data['data']
        ];
    }

    /**
     * Return a paginated list of products paginated and description limited as per params passed
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllProducts(Request $request)
    {
        // get parameters passed from get request page, limit and description_length
        // and set necessary defaults for them if invalid passed
        $page_num = $this->getParam($request, 'page', 'integer|gt:0', 1);
        $limit_per_page = $this->getParam($request, 'limit', 'integer|gt:0', 20);
        $desc_len = $this->getParam($request, 'description_length', 'integer|gt:0', 200);

        // override paginator to start from current page passed 'page'
        Paginator::currentPageResolver(function () use ($page_num) {
            return $page_num;
        });

        $data = $this->product->selectLimitDesc($desc_len)->paginate($limit_per_page)->toArray();

        // format the response array as per API end-point specs.
        $formatted = $this->formatProductRows($data);

        return response()->json($formatted, 200);
    }

    /**
     * Returns a list of product that matches the search query string.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchProducts(Request $request)
    {
        // get parameters passed from get request page, limit and description_length
        // and set necessary defaults for them if invalid passed
        $page_num = $this->getParam($request, 'page', 'integer|gt:0', 1);
        $limit_per_page = $this->getParam($request, 'limit', 'integer|gt:0', 20);
        $desc_len = $this->getParam($request, 'description_length', 'integer|gt:0', 200);
        $query_str = $this->getParam($request, 'query_string', 'string', '');
        $all_words = $this->getParam($request, 'all_words', 'in:on,off', 'on');

        // override paginator to start from current page passed 'page'
        Paginator::currentPageResolver(function () use ($page_num) {
            return $page_num;
        });

        // select products matching query
        $data = $this->product->selectLimitDescThumbs($desc_len)->where('name', 'like', "%{$query_str}%")->paginate($limit_per_page)->toArray();

        return response()->json($data);
    }

    /**
     * Returns a single product by Id.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductById(Request $request, $product_id)
    {
        $desc_len = $this->getParam($request, 'description_length', 'integer|gt:0', 200);

        $product = $this->product->selectLimitDesc($desc_len)->find($product_id);
        return response()->json($product, 200);
    }

    /**
     * Returns all products in a product category.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductsInCategory(Request $request, $category_id)
    {
        // get parameters passed from get request page, limit and description_length
        // and set necessary defaults for them if invalid passed
        $page_num = $this->getParam($request, 'page', 'integer|gt:0', 1);
        $limit_per_page = $this->getParam($request, 'limit', 'integer|gt:0', 20);
        $desc_len = $this->getParam($request, 'description_length', 'integer|gt:0', 200);

        // override paginator to start from current page passed 'page'
        Paginator::currentPageResolver(function () use ($page_num) {
            return $page_num;
        });

        // select products in a category
        $data = $this->product->selectLimitDescThumbs($desc_len)->whereHas('categories', function (Builder $query) use ($category_id) {
            $query->where('category.category_id', '=', $category_id);
        })->paginate($limit_per_page)->toArray();

        // format the response array as per API end-point specs.
        $formatted = $this->formatProductRows($data);

        return response()->json($formatted);
    }

    /**
     * Returns a list of products in a particular department.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductsInDepartment(Request $request, $department_id)
    {
        // get parameters passed from get request page, limit and description_length
        // and set necessary defaults for them if invalid passed
        $page_num = $this->getParam($request, 'page', 'integer|gt:0', 1);
        $limit_per_page = $this->getParam($request, 'limit', 'integer|gt:0', 20);
        $desc_len = $this->getParam($request, 'description_length', 'integer|gt:0', 200);

        // override paginator to start from current page passed 'page'
        Paginator::currentPageResolver(function () use ($page_num) {
            return $page_num;
        });

        // select products in a category
        $data = $this->product->selectLimitDescThumbs($desc_len)->whereHas('categories', function (Builder $query) use ($department_id) {
            $query->where('category.department_id', '=', $department_id);
        })->paginate($limit_per_page)->toArray();

        // format the response array as per API end-point specs.
        $formatted = $this->formatProductRows($data);

        return response()->json($formatted);
    }

    // public function getProductReviews()
    // {
    //     $this->product->reviews()
    // }

    // public function postProductReview(Request $request)
    // {
    //     $review = new Review;
    //     //$review->product_id = $this->getParam();
    //     $review->customer_id = $cust_id;
    //     $review->review = $review;
    //     $review->rating = $rating;

    //     $review->save();
    // }
}
