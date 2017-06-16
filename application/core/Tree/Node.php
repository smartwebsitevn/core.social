<?php namespace Core\Tree;

class Node implements NodeInterface
{
	/**
	 * Node id increment
	 *
	 * @var int
	 */
	protected static $id_increment = 1;

	/**
	 * Node id;
	 *
	 * @var int
	 */
	protected $id;

	/**
	 * Node value
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * Doi tuong node cha
	 *
	 * @var NodeInterface
	 */
	protected $parent;

	/**
	 * Danh sach node con
	 *
	 * @var array
	 */
	protected $children = [];


	public static function _t()
	{
		$a = new static('a');
			$aa = new static('aa');
			$ab = new static('ab');
				$aba = new static('aba');

		$a->addChild($aa);
		$a->addChild($ab);
		$ab->addChild($aba);

//		$a->removeChild($ab);
//		$a->removeChildKeepGrandchild($ab);
//		$a->emptyChildren();

		$v = [static::$id_increment, $a];
		$v = $a;
		$v = $a->getRoot();
		$v = $aba->getAncestors();
		$v = $aa->getSiblings();
		$v = $aa->getHeight();
		$v = $a->getSize();
		$v = $aa->isRoot();
		$v = $ab->isLeaf();
		$v = $aba->isDescendantOf($aa);
		$v = $a->isAncestorOf($aba);

		$tree = new Tree([$a, $aa, $ab, $aba]);

		$v = Tree::listNodesValue($tree->lists());

		pr($v);
	}

	/**
	 * Khoi tao doi tuong
	 *
	 * @param mixed         $value
	 * @param array         $children
	 * @param NodeInterface $parent
	 */
	public function __construct($value = null, array $children = [], NodeInterface $parent = null)
	{
		$this->id = static::newId();

		$this->setValue($value);

		if (count($children))
		{
		    $this->setChildren($children);
		}

		$this->setParent($parent);
	}

	/**
	 * Tao id moi
	 *
	 * @return int
	 */
	protected static function newId()
	{
		$id = static::$id_increment;

		static::$id_increment++;

		return $id;
	}

	/**
	 * Lay node id
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Lay node value
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * Gan node value
	 *
	 * @param mixed $value
	 * @return $this
	 */
	public function setValue($value)
	{
		$this->value = $value;

		return $this;
	}

	/**
	 * Lay node cha
	 *
	 * @return NodeInterface|null
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * Set node cha
	 *
	 * @param NodeInterface $parent
	 * @return $this
	 */
	public function setParent(NodeInterface $parent = null)
	{
		$this->parent = $parent;

		return $this;
	}

	/**
	 * Them node con
	 *
	 * @param NodeInterface $child
	 * @return $this
	 */
	public function addChild(NodeInterface $child)
	{
		$child->setParent($this);

		$this->children[] = $child;

		return $this;
	}

	/**
	 * Loai bo node con
	 *
	 * @param NodeInterface $child
	 * @return $this
	 */
	public function removeChild(NodeInterface $child)
	{
		$this->children = array_filter($this->children, function(NodeInterface $my_child) use ($child)
		{
			return ! $my_child->is($child);
		});

		$this->children = array_values($this->children);

		$child->setParent(null);

		return $this;
	}

	/**
	 * Loai bo node con nhung giu lai cac node chau
	 *
	 * @param NodeInterface $child
	 * @return $this
	 */
	public function removeChildKeepGrandchild(NodeInterface $child)
	{
		$children = [];

		foreach ($this->getChildren() as $my_child)
		{
			if ($my_child->is($child))
			{
				$children = array_merge($children, $child->getChildren());

				continue;
			}

			$children[] = $my_child;
		}

		$this->setChildren($children);

		$child->setParent(null);

		return $this;
	}

	/**
	 * Lay danh sach node con
	 *
	 * @return array
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 * Gan danh sach node con
	 *
	 * @param array $children
	 * @return $this
	 */
	public function setChildren(array $children)
	{
		$this->removeParentOfChildren();

		$this->children = [];

		foreach ($children as $child)
		{
			$this->addChild($child);
		}

		return $this;
	}

	/**
	 * Loai bo tat ca node con
	 *
	 * @return $this
	 */
	public function emptyChildren()
	{
		return $this->setChildren([]);
	}

	/**
	 * Loai bo parent cua cac node con
	 */
	protected function removeParentOfChildren()
	{
		foreach ($this->getChildren() as $children)
		{
			$children->setParent(null);
		}
	}

	/**
	 * Lay node root
	 *
	 * @return NodeInterface
	 */
	public function getRoot()
	{
		$node = $this;

		while ($parent = $node->getParent())
		{
			$node = $parent;
		}

		return $node;
	}

	/**
	 * Lay to tien cua node hien tai
	 *
	 * @return array
	 */
	public function getAncestors()
	{
		$ancestors = [];

		$node = $this;

		while ($parent = $node->getParent())
		{
			array_unshift($ancestors, $parent);

			$node = $parent;
		}

		return $ancestors;
	}

	/**
	 * Lay anh chi em cua node hien tai (de quy)
	 *
	 * @return array
	 */
	public function getSiblings()
	{
		if ($this->isRoot()) return [];

		$siblings = $this->getParent()->getChildren();

		$current = $this;

		return array_values(array_filter($siblings, function($item) use ($current)
		{
			return $item != $current;
		}));
	}

	/**
	 * Lay tat ca con chau cua node hien tai (de quy)
	 *
	 * @return array
	 */
	public function getDescendants()
	{
		$descendants = [];

		foreach ($this->getChildren() as $child)
		{
			$descendants[] = $child;

			$descendants = array_merge($descendants, $child->getDescendants());
		}

		return $descendants;
	}

	/**
	 * Lay khoang cach tu node hien tai den root
	 *
	 * @return int
	 */
	public function getDepth()
	{
		if ($this->isRoot()) return 0;

		return $this->getParent()->getDepth() + 1;
	}

	/**
	 * Lay chieu cao cua cay co root la node nay
	 *
	 * @return int
	 */
	public function getHeight()
	{
		if ($this->isLeaf()) return 0;

		$heights = [];

		foreach ($this->getChildren() as $child)
		{
			$heights[] = $child->getHeight();
		}

		return max($heights) + 1;
	}

	/**
	 * Lay tong so node cua cay co root la node nay
	 *
	 * @return int
	 */
	public function getSize()
	{
		$size = 1;
		foreach ($this->getChildren() as $child)
		{
			$size += $child->getSize();
		}

		return $size;
	}

	/**
	 * Kiem tra 1 node khac co phai node hien tai hay khong
	 *
	 * @param NodeInterface $node
	 * @return bool
	 */
	public function is(NodeInterface $node)
	{
		return $this->getId() == $node->getId();
	}

	/**
	 * Kiem tra node hien tai co phai la root hay khong
	 *
	 * @return bool
	 */
	public function isRoot()
	{
		return is_null($this->getParent());
	}

	/**
	 * Kiem tra node hien tai co node con hay khong
	 *
	 * @return bool
	 */
	public function isLeaf()
	{
		return ! count($this->getChildren());
	}

	/**
	 * Kiem tra node hien tai co phai node con hay khong
	 *
	 * @return bool
	 */
	public function isChild()
	{
		return ! is_null($this->getParent());
	}

	/**
	 * Kiem tra node hien tai co phai la con chau cua node khac hay khong
	 *
	 * @param NodeInterface $node
	 * @return bool
	 */
	public function isDescendantOf(NodeInterface $node)
	{
		foreach ($this->getAncestors() as $ancestor)
		{
			if ($node->is($ancestor)) return true;
		}

		return false;
	}

	/**
	 * Kiem tra node hien tai co phai la to tien cua node khac hay khong
	 *
	 * @param Node $node
	 * @return bool
	 */
	public function isAncestorOf(NodeInterface $node)
	{
		return $node->isDescendantOf($this);
	}

	/**
	 * Goi ham visit cua visitor voi node hien tai
	 *
	 * @param Visitor $visitor
	 * @return mixed
	 */
	public function accept(Visitor $visitor)
	{
		return $visitor->visit($this);
	}

}