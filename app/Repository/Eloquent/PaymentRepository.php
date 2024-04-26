<?php

namespace App\Repository\Eloquent;

use App\Models\Payment as ModelName;
use App\Repository\PaymentRepositoryInterface;
class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository  constructor
     * @param  Model $model
     */
    public function __construct(ModelName $model)
    {
        $this->model =  $model;
    }
}
