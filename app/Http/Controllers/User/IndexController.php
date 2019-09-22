<?php
/**
 * Created by PhpStorm.
 * User: spina
 * Date: 28/05/2019
 * Time: 17:17
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAddressRequest;
use App\Http\Requests\ProfilePostRequest;
use Illuminate\Http\Request;
use App\Repositories\AddressRepositoryInterface;


class IndexController extends Controller
{
    protected $addressRepository;

    public function __construct(AddressRepositoryInterface $addressRepository)
    {
        $this->addressRepository = $addressRepository;
        $this->middleware('auth');

    }

    public function profileGet()
    {
        $user = \Auth::user();
        return View('user.profile', compact('user'));
    }


    public function profilePost(ProfilePostRequest $request)
    {
        $user = \Auth::user();

        if ($user->name != $request->name)
            $user->name = $request->name;
        if ($user->forename != $request->forename)
            $user->forename = $request->forename;
        if ($user->country_code != $request->country_code)
            $user->country_code = $request->country_code;
        if ($user->phone != $request->phone)
            $user->phone = $request->phone;
        if ($user->address_id != $request->address)
            $user->address_id = $request->address;
        $user->save();

        return View('user.profile', compact('user'));
    }

    public function addAddress(CreateAddressRequest $request)
    {
        $this->addressRepository->create($request);
        return redirect()->back();
    }
}
