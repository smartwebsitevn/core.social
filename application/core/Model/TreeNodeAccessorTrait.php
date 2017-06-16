<?php namespace Core\Model;

use Core\Base\Model;
use Core\Tree\NodeInterface as TreeNode;
use TF\Support\Collection;

trait TreeNodeAccessorTrait
{
	/**
	 * Lay doi tuong TreeNode
	 *
	 * @return TreeNode
	 */
	abstract protected function getTreeNode();

	/**
	 * Tao Model tu TreeNode
	 *
	 * @param TreeNode $node
	 * @return Model
	 */
	abstract protected function makeModelFromTreeNode(TreeNode $node);

	/**
	 * Tao Collection tu danh sach TreeNode
	 *
	 * @param array $nodes
	 * @return Collection
	 */
	abstract protected function makeCollectionFromTreeNodes(array $nodes);

	/**
	 * Lay root
	 *
	 * @return Model
	 */
	protected function getRootAttribute()
	{
		$node = $this->getTreeNode()->getRoot();

		return $this->makeModelFromTreeNode($node);
	}

	/**
	 * Lay parent
	 *
	 * @return Model|null
	 */
	protected function getParentAttribute()
	{
		$node = $this->getTreeNode()->getParent();

		return $node ? $this->makeModelFromTreeNode($node) : null;
	}

	/**
	 * Lay children
	 *
	 * @return Collection
	 */
	protected function getChildrenAttribute()
	{
		$nodes = $this->getTreeNode()->getChildren();

		return $this->makeCollectionFromTreeNodes($nodes);
	}

	/**
	 * Lay ancestors
	 *
	 * @return Collection
	 */
	protected function getAncestorsAttribute()
	{
		$nodes = $this->getTreeNode()->getAncestors();

		return $this->makeCollectionFromTreeNodes($nodes);
	}

	/**
	 * Lay siblings
	 *
	 * @return Collection
	 */
	protected function getSiblingsAttribute()
	{
		$nodes = $this->getTreeNode()->getSiblings();

		return $this->makeCollectionFromTreeNodes($nodes);
	}

	/**
	 * Lay descendants
	 *
	 * @return Collection
	 */
	protected function getDescendantsAttribute()
	{
		$nodes = $this->getTreeNode()->getDescendants();

		return $this->makeCollectionFromTreeNodes($nodes);
	}

	/**
	 * Lay depth
	 *
	 * @return int
	 */
	protected function getDepthAttribute()
	{
		return $this->getTreeNode()->getDepth();
	}

	/**
	 * Lay height
	 *
	 * @return int
	 */
	protected function getHeightAttribute()
	{
		return $this->getTreeNode()->getHeight();
	}

	/**
	 * Lay size
	 *
	 * @return int
	 */
	protected function getSizeAttribute()
	{
		return $this->getTreeNode()->getSize();
	}

	/**
	 * Lay is_root
	 *
	 * @return bool
	 */
	protected function getIsRootAttribute()
	{
		return $this->getTreeNode()->isRoot();
	}

	/**
	 * Lay is_leaf
	 *
	 * @return bool
	 */
	protected function getIsLeafAttribute()
	{
		return $this->getTreeNode()->isLeaf();
	}

	/**
	 * Lay is_child
	 *
	 * @return bool
	 */
	protected function getIsChildAttribute()
	{
		return $this->getTreeNode()->isChild();
	}

}