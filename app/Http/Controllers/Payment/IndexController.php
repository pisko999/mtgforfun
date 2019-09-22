<?php
/**
 * Created by PhpStorm.
 * User: spina
 * Date: 04/04/2019
 * Time: 23:32
 */

namespace App\Http\Controllers\Payment;


use App\Http\Controllers\Controller;
use App\Repositories\PaymentRepository;
use App\Repositories\PaymentRepositoryInterface;

class IndexController extends Controller
{
    private $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function show($payment_id)
    {
        if (!is_numeric($payment_id))
            return abort(404);
        $payment = $this->paymentRepository->getByIdWithCommand($payment_id);

        if ($payment == null)
            return abort(404);

        return view('payment.show', compact('payment'));
    }
}
