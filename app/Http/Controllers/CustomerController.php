<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Customer controller handles all requests that has to do with customer
 * Some methods needs to be implemented from scratch while others may contain one or two bugs.
 *
 *  NB: Check the BACKEND CHALLENGE TEMPLATE DOCUMENTATION in the readme of this repository to see our recommended
 *  endpoints, request body/param, and response object for each of these method
 *
 * Class CustomerController
 * @package App\Http\Controllers
 */
class CustomerController extends BaseController
{
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
        $this->common_fields = [
            'customer_id',
            'name',
            'email',
            'address_1',
            'address_2',
            'city',
            'region',
            'postal_code',
            'shipping_region_id',
            'credit_card',
            'day_phone',
            'eve_phone',
            'mob_phone'];
    }
    /**
     * Allow customers to create a new account.
     *
     * @param CreateCustomerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createProfile(CreateCustomerRequest $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email:rfc,dns',
            'password' => 'required|string'
            ]);

        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->api_token = Str::random(80);
        $user->api_token_expire = Carbon::now()->addHours(4);
        $user->save();

        $cust = new Customer;
        $cust->name = $data['name'];
        $cust->email = $data['email'];
        $cust->password = $data['password'];
        $cust->user_id = $user->id;
        $cust->save();
        $created_profile = $this->customer->find($cust->customer_id)->select($this->common_fields)->get()->toArray();

        return response()->json([
            'customer' => $created_profile[0],
            'accessToken' => $user->api_token, 
            'expiresIn' => $user->api_token_expire], 201);
    }

    /**
     * Allow customers to login to their account.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $data = $request->validate(['email' => 'required|email:rfc,dns|exists:customer', 'password' => 'required']);
        $cust = $this->customer->select($this->common_fields)
                ->where('email', '=', $data['email'])
                ->where('password', '=', $data['password'])->first();

        $token_details = User::refreshToken(1);

        $data = [
            'accessToken' => $token_details['accessToken'], 
            'expiresIn' => $token_details['expiresIn']];

        return response()->json($data, 200);
    }

    public function fbLogin(Request $request)
    {
        $data = $request->validate(['access_token' => 'required']);
        $fb_api = "https://graph.facebook.com/v4.0/me?fields=email,name&access_token=".$data['access_token'];
        $client = new Client();
        $response = $client->request('GET', $fb_api);
        $data = json_decode($response->getBody(), true);

        $profile = $this->customer->select($this->common_fields)->where('email', '=', $data['email'])->first();

        if($profile === null)
        {
            $cust = new Customer;
            $cust->name = $data['name'];
            $cust->email = $data['email'];
            $cust->password = password_hash(microtime().$data['email'], PASSWORD_DEFAULT);
            $cust->save();
            
            $profile = $this->customer->find($cust->customer_id)->select($this->common_fields)->first();
        }

        return response()->json($profile, 200);
    }

    private function getProfile(Request $request)
    {
        $token = $request->header('token');
        $cust_id = substr($token, 16); // assumed the token has 16 fake chars from left, then the customer_id
        $profile = $this->customer->find($cust_id)->select($this->common_fields)->get()->toArray();
        return $profile;
    }

    /**
     * Allow customers to view their profile info.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerProfile(Request $request)
    {
        try {
            $token = $request->header('token');
            $cust_id = substr($token, 16); // assumed the token has 16 fake chars from left, then the customer_id
            $profile = $this->customer->find($cust_id)->select($this->common_fields)->get()->toArray();
            return response()->json($profile, 200);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'Error: Not allowed'], 404);
        }
    }

    /**
     * Allow customers to update their profile info like name, email, password, day_phone, eve_phone and mob_phone.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCustomerProfile(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email:rfc,dns',
            'day_phone' => 'required|numeric',
            'eve_phone' => 'required|numeric',
            'mob_phone' => 'required|numeric']);

        //$profile = $this->customer->select($this->common_fields)->where('email', '=', $data['email'])->first();

        $profile = $this->getProfile($request);
        $profile->name = $data['name'];
        $profile->email = $data['email'];
        $profile->day_phone = $data['day_phone'];
        $profile->eve_phone = $data['eve_phone'];
        $profile->mob_phone = $data['mob_phone'];
        $profile->save();

        return response()->json($profile, 200);
    }

    /**
     * Allow customers to update their address info/
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCustomerAddress(Request $request)
    {
        $data = $request->validate([
            'address_1' => 'required|string',
            'address_2' => 'required|string',
            'city' => 'required|string',
            'region' => 'required|string',
            'postal_code' => 'required|string',
            'shipping_region_id' => 'required|integer']);

        //$profile = $this->customer->select($this->common_fields)->where('email', '=', $data['email'])->first();

        $profile = $this->getProfile($request);
        $profile->address_1 = $data['address_1'];
        $profile->address_2 = $data['address_2'];
        $profile->city = $data['city'];
        $profile->region = $data['region'];
        $profile->postal_code = $data['postal_code'];
        $profile->shipping_region_id = $data['shipping_region_id'];
        $profile->save();

        return response()->json($profile, 200);
    }

    /**
     * Allow customers to update their credit card number.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCreditCard()
    {
        $data = $request->validate([
            'credit_card' => 'required|numeric']);

        //$profile = $this->customer->select($this->common_fields)->where('email', '=', $data['email'])->first();

        $profile = $this->getProfile($request);
        $profile->credit_card = $data['address_1'];
        $profile->save();

        return response()->json($profile, 200);
    }

    /**
     * Apply something to customer.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apply()
    {
        return response()->json(['message' => 'this works']);
    }
}
