<?php

namespace App\Repository\Eloquent;

use App\Models\Cart as ModelName;
use App\Repository\CartRepositoryInterface;
use Storage;

class CartRepository extends BaseRepository implements CartRepositoryInterface
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

