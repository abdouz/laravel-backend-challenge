<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use App\Models\Customer;

/**
 * Class ShoppingCart
 * @package App\Models
 * @property int $item_id
 * @property int $cart_id
 * @property int $product_id
 * @property string $attributes
 * @property int $quantity
 * @property boolean $buy_now
 * @property string $added_on
 * @property int $customer_id
 * @property \App\Models\Customer $customer
 */
class ShoppingCart extends Model
{
    public $timestamps = false;

    protected $table = 'shopping_cart';
    protected $primaryKey = 'item_id';
    protected $guarded = ['item_id'];

    public function items()
    {
        
    }
    
    // public static function getOrCreateCartId(Customer $customer): string
    // {
    //     $record = ShoppingCart::where('customer_id', $customer->getKey())->select('cart_id')->first();
    //     if($record) return $record->cart_id;

    //     return uniqid($customer->getKey().'_');
    // }
}
