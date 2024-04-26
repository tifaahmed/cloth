<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Payment\PaymentResource;
use App\Repository\PaymentRepositoryInterface as ModelInterface;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    public function __construct(private ModelInterface $modelInterface)
    {
    }

    public function all()
    {
        try {
            $modal =  $this->modelInterface->all();
            return PaymentResource::collection($modal);
        } catch (\Exception $e) {
            return $this->MakeResponseErrors(
                [$e->getMessage()],
                'Errors',
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
