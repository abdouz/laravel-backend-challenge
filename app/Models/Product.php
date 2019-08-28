<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;

/**
 * Class Product
 * @package App\Models
 * @property int $product_id
 * @property string $name
 * @property string $description
 * @property double $price
 * @property double $discounted_price
 * @property string $image
 * @property string $image_2
 * @property string $thumbnail
 * @property int $display
 * @property Collection $categories
 *
 */
class Product extends Model
{
    public $timestamps = false;

    protected $table = 'product';
    protected $primaryKey = 'product_id';

    // public static function countedAndPaginableResults(array $criteria = [])
    // {
    //     return self::all();
    // }

    // public static function countedAndPaginableResultsWithDepartments(array $criteria = [])
    // {
    //     return self::all();
    // }

    public static function selectLimitDesc($desc_len)
    {
        $columns = ['product_id', 'name', 'price', 'discounted_price',
                    'image', 'image_2', 'thumbnail', 'display',
                    DB::raw("LEFT(description, {$desc_len}) as description")];
        return self::select($columns);
    }

    public static function selectLimitDescThumbs($desc_len)
    {
        $columns = ['product_id', 'name', 'price', 'discounted_price',
                    'thumbnail', DB::raw("LEFT(description, {$desc_len}) as description")];
        return self::select($columns);
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'product_category', 'product_id', 'category_id');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review', 'product_id');
    }
}
