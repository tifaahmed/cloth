<?php

namespace App\Repository\Eloquent;

use App\Models\Category as ModelName;
use App\Repository\CategoryRepositoryInterface;
use Storage;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
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

