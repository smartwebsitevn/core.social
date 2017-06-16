<?php namespace Core\Model;

abstract class Extension extends \Core\Base\Model
{
	use SettingAttributeMakerTrait;

	protected $table = 'extension';

	protected $casts = [
		'status'  => 'boolean',
		'options' => 'array',
	];

	protected $defaults = [
		'options' => [],
	];

	/**
	 * Ten column extension_type
	 *
	 * @var string
	 */
	const COLUMN_EXTENSION_TYPE = 'extension_type';


	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $attributes
	 */
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);

		$this->syncExtensionType();
	}

	/**
	 * Get extension type
	 *
	 * @return string
	 */
	abstract public function getExtensionType();

	/**
	 * Callback xu ly data insert
	 *
	 * @param array $data
	 * @return array
	 */
	protected function handleDataInsert(array $data)
	{
		return array_merge($data, $this->getExtensionTypeCombined());
	}

	/**
	 * Callback gan query global
	 */
	protected function setGlobalQuery()
	{
		t('db')->where(
			$this->getQualifiedExtensionTypeColumn(),
			$this->getExtensionType()
		);
	}

	/**
	 * Get extension type column
	 *
	 * @return string
	 */
	public function getExtensionTypeColumn()
	{
		return static::COLUMN_EXTENSION_TYPE;
	}

	/**
	 * Get extension type column full
	 *
	 * @return string
	 */
	public function getQualifiedExtensionTypeColumn()
	{
		return $this->getTable().'.'.$this->getExtensionTypeColumn();
	}

	/**
	 * Thuc hien gan gia tri extension type vao attributes
	 */
	public function syncExtensionType()
	{
		$this->attributes = array_merge($this->attributes, $this->getExtensionTypeCombined());
	}

	/**
	 * Tao 1 array co key la ten column va value la value cua ExtensionType
	 *
	 * @return array
	 */
	public function getExtensionTypeCombined()
	{
		return [$this->getExtensionTypeColumn() => $this->getExtensionType()];
	}

}