<?php namespace Core\Tree;

use Tree\Node\NodeInterface;

class FlattenedItem
{
	/**
	 * Item id
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Item parent_id
	 *
	 * @var string
	 */
	protected $parent_id;

	/**
	 * Item data
	 *
	 * @var mixed
	 */
	protected $data;

	/**
	 * Doi tuong Node cua item
	 *
	 * @var NodeInterface
	 */
	protected $node;


	/**
	 * Khoi tao doi tuong
	 *
	 * @param int   $id
	 * @param int   $parent_id
	 * @param mixed $data
	 */
	public function __construct($id, $parent_id = 0, $data = null)
	{
		$this->id = $id;

		$this->parent_id = $parent_id;

		$this->data = $data ?: $id;

		$this->node = $this->newNode();
	}

	/**
	 * Tao doi tuong Node
	 *
	 * @return Node
	 */
	protected function newNode()
	{
		return new Node($this->data);
	}

	/**
	 * Lay item id
	 *
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Lay item parent_id
	 *
	 * @return string
	 */
	public function getParentId()
	{
		return $this->parent_id;
	}

	/**
	 * Lay item data
	 *
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Lay doi tuong Node
	 *
	 * @return NodeInterface
	 */
	public function getNode()
	{
		return $this->node;
	}

}