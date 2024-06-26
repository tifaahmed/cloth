<?php

namespace App\Repository\Eloquent;

use App\Models\Order as ModelName;
use App\Repository\OrderRepositoryInterface;
use Storage;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
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

