<?php


namespace App\Repositories;

use App\Http\Requests\CreateAddressRequest;
use App\Models\Address;

class AddressRepository extends ModelRepository implements AddressRepositoryInterface
{

    public function __construct(Address $address)
    {
        $this->model = $address;
    }

    public function create(CreateAddressRequest $request)
    {
        $flat = isset($request->flat) ? $request->flat : '';
        $region = isset($request->region) ? $request->region : '';

        $address = new Address([
            'street' => $request->street,
            'number' => $request->number,
            'flat' => $flat,
            'city' => $request->city,
            'country' => $request->country,
            'region' => $region,
            'postal' => $request->postal,
        ]);

        $user = $request->user();
        $user->addresses()->save($address);

    }
}
