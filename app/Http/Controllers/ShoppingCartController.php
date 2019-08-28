<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ShoppingCart;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

/**
 * Check each method in the shopping cart controller and add code to implement
 * the functionality or fix any bug.
 *
 *  NB: Check the BACKEND CHALLENGE TEMPLATE DOCUMENTATION in the readme of this repository to see our recommended
 *  endpoints, request body/param, and response object for each of these method
 *
 * Class ShoppingCartController
 * @package App\Http\Controllers
 */
class ShoppingCartController extends Controller
{
    public function __construct(ShoppingCart $shopping_cart, Order $order)
    {
        $this->shopping_cart = $shopping_cart;
        $this->order = $order;
    }

    /**
     * To generate a unique cart id.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateUniqueCart()
    {
        return response()->json(['cart_id' => str_replace('-', '', (string) Str::uuid())], 200);
    }

    /**
     * To add new product to the cart.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addItemToCart(Request $request)
    {
        $data = $request->validate([
            'cart_id' => 'required|string', 
            'product_id' => 'required|integer|exists:product',
            'attributes' => 'string',
            'quantity' => 'required|integer|gt:0'
            ]);

        $shopping_cart = new ShoppingCart;
        $shopping_cart->cart_id = $data['cart_id'];
        $shopping_cart->product_id = $data['product_id'];
        $shopping_cart->attributes = $data['attributes'];
        $shopping_cart->quantity = $data['quantity'];
        $shopping_cart->buy_now = 0;
        $shopping_cart->added_on = now();

        $shopping_cart->save();

        $formatted = [
            'item_id' => $shopping_cart->item_id,
            'cart_id' => $shopping_cart->cart_id,
            'product_id' => $shopping_cart->product_id,
            'attributes' => $shopping_cart->attributes,
            'quantity' => $shopping_cart->quantity
        ];

        return response()->json($formatted, 201);
    }

    private function getCartObj(Request $request, $cart_id)
    {
        // SELECT sc.item_id, sc.cart_id, p.`name`, sc.attributes, sc.product_id, p.image, p.price, p.discounted_price, sc.quantity, sc.quantity*p.price as subtotal FROM shopping_cart as sc, product as p where p.product_id = sc.product_id;
        $cart_details = DB::table('shopping_cart as sc')
            ->join('product as p', 'sc.product_id', '=', 'p.product_id')
            ->select('sc.item_id', 
                     'sc.cart_id',
                     'p.name',
                     'sc.attributes',
                     'sc.product_id',
                     'p.image',
                     'p.price',
                     'p.discounted_price',
                     'sc.quantity',
                     DB::raw('p.price * sc.quantity as subtotal'))->where('sc.cart_id', '=', $cart_id)->get();
        return $cart_details;
    }

    /**
     * Method to get list of items in a cart.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCartDetails(Request $request, $cart_id)
    {
        return response()->json($this->getCartObj($request, $cart_id), 200);
    }

    /**
     * Update the quantity of a product in the shopping cart.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCartItem(Request $request, $item_id)
    {
        $data = $request->validate(['quantity' => 'required|numeric']);
        $item = $this->shopping_cart
            ->where('item_id', '=', $item_id)
            ->first();
        $item->quantity = $data['quantity'];
        $item->save();

        return response()->json($item, 200);
    }

    /**
     * Should be able to clear shopping cart.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function emptyCart($cart_id)
    {
        $this->shopping_cart->where('cart_id', '=', $cart_id)->forceDelete();
        return response()->json([]);
    }

    /**
     * Should delete a product from the shopping cart.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeItemFromCart($item_id)
    {
        $this->shopping_cart->find($item_id)->first()->forceDelete();
        return response()->json(['message' => 'Item removed successfully']);
    }

    /**
     * Create an order.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOrder(Request $request)
    {
        $data = $request->validate([
            'cart_id' => 'required|exists:shopping_cart,cart_id',
            'shipping_id' => 'required|exists:shipping,shipping_id',
            'tax_id' => 'required|exists:tax,tax_id'
            ]);

        $cart_items = $this->getCartObj($request, $data['cart_id']);

        $order = new Order;
        $order->created_on = now();
        $order->shipping_id = $data['shipping_id'];
        $order->tax_id = $data['tax_id'];
        $order->status = 1;
        //$order->customer_id = null;
        $order->save();
        $total_amount = 0;

        foreach($cart_items as $item)
        {
            $order_detail = new OrderDetail;
            $order_detail->item_id = $item->item_id;
            $order_detail->order_id = $order->order_id;
            $order_detail->product_id = $item->product_id;
            $order_detail->attributes = $item->attributes;
            $order_detail->product_name = $item->name;
            $order_detail->quantity = $item->quantity;
            $order_detail->unit_cost = $item->price;
            $order_detail->save();

            $total_amount += $item->quantity * $item->price;
        }

        $order->total_amount = $total_amount;
        $order->save();

        return response()->json(['order_id' => $order->order_id]);
    }

    public function getOrderById($order_id)
    {
        try {
            $order = $this->order->find($order_id)->first();
            $order_items = $order->items()
                ->select(['product_id', 'attributes', 'product_name', 'quantity', 'unit_cost', DB::raw('quantity * unit_cost as subtotal')])
                ->get()->all();

            $formatted = [
                'order_id' => $order->order_id,
                'order_items' => $order_items
            ];
            return response()->json($formatted, 200);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'Error: Order not found'], 404);
        }
    }

    /**
     * Get all orders of a customer.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerOrders()
    {
        // try {
        //     $order = $this->order->find($order_id)->first();
        //     $order_items = $order->items()
        //         ->select(['product_id', 'attributes', 'product_name', 'quantity', 'unit_cost', DB::raw('quantity * unit_cost as subtotal')])
        //         ->get()->all();

        //     $formatted = [
        //         'order_id' => $order->order_id,
        //         'order_items' => $order_items
        //     ];
        //     return response()->json($formatted, 200);
        // } catch (\Throwable $e) {
        //     report($e);
        //     return response()->json(['message' => 'Error: Order not found'], 404);
        // }
    }

    /**
     * Get the details of an order.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderSummary()
    {
        return response()->json(['message' => 'this works']);
    }

    /**
     * Process stripe payment.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function processStripePayment(Request $request)
    {
        // sk_test_4eC39HqLyjWDarjtT1zdp7dc:
        
        // $data = $request->validate([
        //     'stripeToken' => 'string',
        //     'email' => 'required|email:rfc,dns',
        //     'order_id' => 'required|integer|exists:orders,order_id'
        //     ]);

        // $stripe_api = 'https://api.stripe.com/v1/charges';
        // $charge_data = [
        //     'amount' => , 
        //     'currency' =>, 
        //     'description' =>, 
        //     'metadata[order_id]' => ];

        // $client = new Client();
        // $response = $client->request('GET', $stripe_api);
        // $data = json_decode($response->getBody(), true);

        // return response()->json($data);
    }
}
