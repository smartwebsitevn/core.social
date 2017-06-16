<?php namespace App\Product\Model;

use TF\Support\Collection;
use Core\Base\Model;
use Core\Model\TreeNodeAccessorTrait;
use Core\Tree\NodeInterface as TreeNode;
use App\Product\ProductFactory as ProductFactory;

class CatModel extends Model
{
	use TreeNodeAccessorTrait;

	protected $table = 'cat';


	public static function _t()
	{
		$cat = static::find(44);
//		$cat = static::find(45);
//		$cat = static::find(46);

		$v = $cat->root;
		$v = $cat->parent;
		$v = $cat->children;
		$v = $cat->ancestors;
		$v = $cat->siblings;
		$v = $cat->descendants;
		$v = $cat->depth;
		$v = $cat->height;
		$v = $cat->size;

		pr($v, 0);
	}

	/**
	 * Lay doi tuong TreeNode
	 *
	 * @return TreeNode
	 */
	protected function getTreeNode()
	{
		if ( ! array_key_exists('tree_node', $this->additional))
		{
			$this->additional['tree_node'] = ProductFactory::cat()->getNode($this->getKey());
		}

		return $this->additional['tree_node'];
	}

	/**
	 * Tao Model tu TreeNode
	 *
	 * @param TreeNode $node
	 * @return Model
	 */
	protected function makeModelFromTreeNode(TreeNode $node)
	{
		return ProductFactory::cat()->findByNode($node);
	}

	/**
	 * Tao Collection tu danh sach TreeNode
	 *
	 * @param array $nodes
	 * @return Collection
	 */
	protected function makeCollectionFromTreeNodes(array $nodes)
	{
		return ProductFactory::cat()->listByNodes($nodes);
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
		switch ($action)
		{
			case 'children':
			{
				return admin_url($this->getTable(), $opt).'?cat_id='.$this->getKey();
			}
		}

		return parent::adminUrl($action, $opt);
	}

}