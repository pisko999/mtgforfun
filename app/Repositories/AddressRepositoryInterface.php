<?php


namespace App\Repositories;


use App\Http\Requests\CreateAddressRequest;

interface AddressRepositoryInterface
{

    public function create(CreateAddressRequest $request);
}
