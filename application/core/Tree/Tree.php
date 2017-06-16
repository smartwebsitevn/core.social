<?php namespace Core\Tree;

use Core\Tree\Command\MakeNodesFromFlattenedItems;

class Tree
{
	/**
	 * Danh sach Nodes
	 *
	 * @var array
	 */
	protected $nodes = [];

	
	/**
	 * Khoi tao doi tuong
	 *
	 * @param array $nodes
	 */
	public function __construct(array $nodes)
	{
		$this->nodes = $nodes;
	}

	/**
	 * Tim node
	 *
	 * @param int $node_id
	 * @return NodeInterface|null
	 */
	public function find($node_id)
	{
		return array_first($this->getNodes(), function($i, NodeInterface $node) use ($node_id)
		{
			return $node->getId() == $node_id;
		});
	}

	/**
	 * Tim node tu gia tri cua node
	 *
	 * @param mixed $node_value
	 * @return NodeInterface|null
	 */
	public function findByValue($node_value)
	{
		return array_first($this->getNodes(), function($i, NodeInterface $node) use ($node_value)
		{
			return $node->getValue() == $node_value;
		});
	}

	/**
	 * Lay tat ca cac root node
	 *
	 * @return array
	 */
	public function roots()
	{
		return array_filter($this->getNodes(), function(NodeInterface $node)
		{
			return $node->isRoot();
		});
	}

	/**
	 * Lay danh sach nodes dang phang
	 *
	 * @return array
	 */
	public function lists()
	{
		$list = [];

		foreach ($this->roots() as $root)
		{
			$list = array_merge($list, [$root], $root->getDescendants());
		}

		return $list;
	}

	/**
	 * Gop cac ket qua tra ve khi ap dung Visitor cho cac nodes
	 *
	 * @param array   $nodes
	 * @param Visitor $visitor
	 * @return array
	 */
	protected function visitNodes(array $nodes, Visitor $visitor)
	{
		$result = [];

		foreach ($nodes as $node)
		{
			array_merge($result, $node->accept($visitor));
		}

		return $result;
	}

	/**
	 * Lay gia tri cua cac nodes hien tai
	 *
	 * @return array
	 */
	public function listValues()
	{
		return static::listNodesValue($this->getNodes());
	}

	/**
	 * Lay danh sach nodes
	 *
	 * @return array
	 */
	public function getNodes()
	{
		return $this->nodes;
	}

	/**
	 * Gan danh sach nodes
	 *
	 * @param array $nodes
	 * @return Tree
	 */
	public function setNodes(array $nodes)
	{
		$this->nodes = $nodes;

		return $this;
	}

	/**
	 * Lay value cua cac nodes
	 *
	 * @param array $nodes
	 * @return array
	 */
	public static function listNodesValue(array $nodes)
	{
		return array_map(function(NodeInterface $node)
		{
			return $node->getValue();
		}, $nodes);
	}

	/**
	 * Tao Tree tu Flattened Items
	 *
	 * @param array $items
	 * @return Tree
	 */
	public static function makeFromFlattenedItems(array $items)
	{
		$nodes = (new MakeNodesFromFlattenedItems($items))->handle();

		return new static($nodes);
	}

}