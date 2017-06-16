<?php namespace Core\Base;

use Core\Model\ModelAccess;
use Core\Support\Arr;
use MY_Model as ModelHandler;
use TF\Support\Collection;

class Model extends ModelAccess
{
	/**
	 * Ten table
	 *
	 * @var
	 */
	protected $table;

	/**
	 * Key cua table
	 *
	 * @var string
	 */
	protected $primary_key = 'id';

	/**
	 * Luu thoi gian create va update
	 *
	 * @var bool
	 */
	protected $timestamps = true;

	/**
	 * Luu thoi gian created theo microtime
	 *
	 * @var bool
	 */
	protected $microtime = false;

	/**
	 * Ten column created
	 *
	 * @var string
	 */
	const CREATED_AT = 'created';

	/**
	 * Ten column created_micro
	 *
	 * @var string
	 */
	const CREATED_MICRO_AT = 'created_micro';

	/**
	 * Doi tuong xu ly cua model voi DB
	 *
	 * @var ModelHandler
	 */
	protected $model_handler;

	/**
	 * Danh sach attribute bo sung
	 *
	 * @var array
	 */
	protected $additional = [];

	/**
	 * Cac method cho phep goi duoi dang bien
	 *
	 * @var array
	 */
	protected $methods_accessor = ['can', 'url', 'adminUrl', 'format'];

	/**
	 * Kieu dinh dang cua cac attribute
	 *
	 * @var array
	 */
	protected $formats = [
		'created' => 'date',
	];


	/**
	 * Tao moi
	 *
	 * @param array $attributes
	 * @return static
	 */
	public static function create(array $attributes)
	{
		$instance = new static($attributes);

		$instance->insertAttributesIntoDb();

		return $instance;
	}

	/**
	 * Thuc hien insert attributes vao DB
	 */
	protected function insertAttributesIntoDb()
	{
		if ($this->timestamps && is_null($this->getCreatedAt()))
		{
			$this->setCreatedAt(now());
		}

		if ($this->microtime && is_null($this->getAttribute(static::CREATED_MICRO_AT)))
		{
		    $this->setAttribute(static::CREATED_MICRO_AT, microtime(true));
		}

		$id = $this->insertData($this->getAttributes());

		if (($key = $this->getKeyName()) && is_null($this->getKey()))
		{
			$this->setAttribute($key, $id);
		}
	}

	/**
	 * Lay thong tin
	 *
	 * @param int $id
	 * @return static|null
	 */
	public static function find($id)
	{
		$instance = new static;

		$data = $instance->newQuery()->get_info($id);

		return $data ? static::newWithAttributes(Arr::toArray($data)) : null;
	}

	/**
	 * Lay thong tin tu where
	 *
	 * @param array $where
	 * @return static|null
	 */
	public static function findWhere(array $where)
	{
		$instance = new static;

		$data = $instance->newQuery()->get_info_rule($where);

		return $data ? static::newWithAttributes(Arr::toArray($data)) : null;
	}

	/**
	 * Lay thong tin hoac tao moi
	 *
	 * @param array $attributes
	 * @return static
	 */
	public static function firstOrCreate(array $attributes)
	{
		if ( ! is_null($instance = static::findWhere($attributes)))
		{
			return $instance;
		}

		return static::create($attributes);
	}

	/**
	 * Cap nhat thong tin hoac tao moi
	 *
	 * @param  array $attributes
	 * @param  array $values
	 * @return static
	 */
	public static function updateOrCreate(array $attributes, array $values = [])
	{
		$instance = static::firstOrCreate($attributes);

		if (count($values))
		{
			$instance->update($values);
		}

		return $instance;
	}

	/**
	 * Tao doi tuong moi va gan $attributes
	 *
	 * @param array $attributes
	 * @return static
	 */
	public static function newWithAttributes(array $attributes)
	{
		$instance = new static;

		$instance->setAttributes($attributes);

		return $instance;
	}

	/**
	 * Tach danh sach doi tuong quan he tu attributes
	 *
	 * @param array $attributes
	 * @param array $config		[relation_key => relation_class, ...]
	 * @return array
	 */
	protected static function pullRelationsFromAttributes(array &$attributes, array $config)
	{
		$relations = [];

		foreach ($config as $key => $options)
		{
			$options = is_array($options) ? $options : ['class' => $options];

			$class 	= $options['class'];
			$type 	= array_get($options, 'type', 'one');

			if ( ! is_null($relation = static::pullRelationFromAttributes($attributes, $key, $type, $class)))
			{
				$relations[$key] = $relation;
			}
		}

		return $relations;
	}

	/**
	 * Tach doi tuong quan he tu attributes
	 *
	 * @param array  $attributes
	 * @param string $key
	 * @param string $type
	 * @param string $class
	 * @return mixed|null
	 */
	protected static function pullRelationFromAttributes(array &$attributes, $key, $type, $class)
	{
		if ( ! array_key_exists($key, $attributes)) return null;

		$make_relation = function($value, $class)
		{
			return ($value instanceof $class)
				? $value
				: forward_static_call([$class, 'newWithAttributes'], Arr::toArray($value));
		};

		$value = array_pull($attributes, $key) ?: [];

		// Relation many
		if ($type == 'many')
		{
			foreach ($value as &$item)
			{
				$item = $make_relation($item, $class);
			}

			return collect($value);
		}

		// Relation one
		return $make_relation($value, $class);
	}

	/**
	 * Xoa du lieu
	 *
	 * @param int $id
	 */
	public static function destroy($id)
	{
		$instance = new static;

		$instance->newQuery()->del($id);
	}

	/**
	 * Lay tat ca ban ghi
	 *
	 * @return Collection
	 */
	public static function all()
	{
		$instance = new static;

		$list = $instance->newQuery()->get_list();

		return static::makeCollection($list);
	}

	/**
	 * Tao model collection
	 *
	 * @param array $models
	 * @return Collection
	 */
	public static function makeCollection(array $models)
	{
		$items = [];

		foreach ($models as $model)
		{
			$items[] = $model instanceof Model
				? $model
				: static::newWithAttributes(Arr::toArray($model));
		}

		return collect($items);
	}

	/**
	 * Update attributes
	 *
	 * @param array $attributes
	 * @return bool
	 */
	public function update(array $attributes)
	{
		$this->fill($attributes);

		$this->updateAttributesValueIntoDb(array_keys($attributes));

		return true;
	}

	/**
	 * Thuc hien update gia tri cua cac attributes vao db
	 *
	 * @param array $keys
	 */
	protected function updateAttributesValueIntoDb(array $keys)
	{
		$data = array_only($this->attributes, $keys);

		$this->newQuery()->update($this->getKey(), $data);
	}

	/**
	 * Xoa
	 *
	 * @return bool
	 */
	public function delete()
	{
		static::destroy($this->getKey());

		return true;
	}

	/**
	 * Insert data vao DB
	 *
	 * @param array $data
	 * @return int
	 */
	protected function insertData(array $data)
	{
		$data = $this->handleDataInsert($data);

		$id = 0;

		$this->getModelHandler()->create($data, $id);

		return $id;
	}

	/**
	 * Callback xu ly data insert
	 *
	 * @param array $data
	 * @return array
	 */
	protected function handleDataInsert(array $data)
	{
		return $data;
	}

	/**
	 * Thuc hien query moi
	 *
	 * @return ModelHandler
	 */
	public function newQuery()
	{
		$this->setGlobalQuery();

		return $this->getModelHandler();
	}

	/**
	 * Callback gan query global
	 */
	protected function setGlobalQuery(){}

	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 *
	 * @param string $action
	 * @return bool
	 */
	public function can($action)
	{
		switch ($action)
		{
			case 'edit':
			case 'delete':
			{
				return true;
			}

			case 'view':
			{
				if ( ! is_null($status = $this->getAttribute('status')))
				{
					return $status ? true : false;
				}

				return true;
			}

			case 'on':
			{
				return ! $this->getAttribute('status');
			}

			case 'off':
			{
				return $this->getAttribute('status');
			}
		}

		return false;
	}

	/**
	 * Tao url
	 *
	 * @param string $action
	 * @param array  $opt
	 * @return string
	 */
	public function url($action, array $opt = [])
	{
		$uri = $this->getTable().'/'.$action.'/'.$this->getKey();
		$uri = trim($uri, '/');

		return site_url($uri, $opt);
	}

	/**
	 * Tao admin url
	 *
	 * @param string $action
	 * @param array  $opt
	 * @return string
	 */
	public function adminUrl($action, array $opt = [])
	{
		$uri = $this->getTable().'/'.$action.'/'.$this->getKey();
		$uri = trim($uri, '/');

		return admin_url($uri, $opt);
	}

	/**
	 * Format du lieu
	 *
	 * @param string $key
	 * @param mixed  $option
	 * @return string|null
	 */
	public function format($key, $option = null)
	{
		switch(array_get($this->formats, $key))
		{
			case 'amount':
			{
				return currency_convert_format_amount($this->getAttribute($key));
			}

			case 'date':
			{
				$option = in_array($option, ['time', 'full']) ? $option : '';

				return format_date($this->getAttribute($key), $option);
			}
		}
	}

	/**
	 * Lay table
	 *
	 * @return string
	 */
	public function getTable()
	{
		return $this->table ?: strtolower(class_basename($this));
	}

	/**
	 * Ly gia tri cua primary key
	 *
	 * @return mixed
	 */
	public function getKey()
	{
		return $this->getAttribute($this->getKeyName());
	}

	/**
	 * Lay primary key
	 *
	 * @return string
	 */
	public function getKeyName()
	{
		return $this->primary_key;
	}

	/**
	 * Lay doi tuong xu ly DB cua model
	 *
	 * @return ModelHandler
	 */
	public function getModelHandler()
	{
		return $this->model_handler ?: model($this->getTable());
	}

	/**
	 * Lay gia tri cua created_at
	 *
	 * @return int
	 */
	public function getCreatedAt()
	{
		return $this->getAttribute($this->getCreatedAtColumn());
	}

	/**
	 * Gan gia tri cho created_at
	 *
	 * @param int $value
	 * @return $this
	 */
	public function setCreatedAt($value)
	{
		return $this->setAttribute($this->getCreatedAtColumn(), $value);
	}

	/**
	 * Lay ten cot created_at
	 *
	 * @return string
	 */
	public function getCreatedAtColumn()
	{
		return static::CREATED_AT;
	}

}