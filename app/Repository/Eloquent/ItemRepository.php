<?php

namespace App\Repository\Eloquent;

use App\Models\Item as ModelName;
use App\Repository\ItemRepositoryInterface;
use Storage;

class ItemRepository extends BaseRepository implements ItemRepositoryInterface
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

