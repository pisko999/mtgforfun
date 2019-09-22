<?php

namespace App\Http\Controllers\Auth;

use App\Models\Address;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'forename' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'country_code' => ['required', 'regex:/^[0-9]{1,5}$/'],
            'phone' =>['required', 'regex:/^[0-9]{7,12}$/'],

            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:16'],
            'flat' => ['nullable', 'string', 'max:16'],
            'city' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'postal' => ['required', 'string', 'min:4', 'max:16'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $address = Address::create([
            'street' => $data['street'],
            'number' => $data['number'],
            'flat' => $data['flat'],
            'city' => $data['city'],
            'country' => $data['country'],
            'region' => $data['region'],
            'postal' => $data['postal'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'forename' => $data['forename'],
            'email' => $data['email'],
            'country_code' => $data['country_code'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'address_id' => $address->id,
        ]);

        $user->addresses()->save($address);
        return $user;
    }
}
