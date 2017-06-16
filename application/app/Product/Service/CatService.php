<?php namespace App\Product\Service;

use TF\Support\Collection;
use Core\Tree\Tree;
use Core\Tree\NodeInterface;
use Core\Tree\FlattenedItem;
use App\Product\Model\CatModel as CatModel;

class CatService extends \Core\Base\ServiceModelMutator
{
	/**
	 * Danh sach the loai
	 *
	 * @var Collection
	 */
	protected $list;

	/**
	 * Doi tuong tree
	 *
	 * @var Tree
	 */
	protected $tree;


	public static function _t()
	{
		$me = new CatService;

		$v = $me->treeList();

		pr($v, 0);
	}

	/**
	 * Khoi tao doi tuong
	 */
	public function __construct()
	{
		$this->tree = $this->newTree();
	}

	/**
	 * Tao doi tuong Tree
	 *
	 * @return Tree
	 */
	protected function newTree()
	{
		$items = [];

		foreach ($this->lists() as $row)
		{
			$items[] = new FlattenedItem($row->id, $row->parent_id);
		}

		return Tree::makeFromFlattenedItems($items);
	}

	/**
	 * Them moi
	 *
	 * @param array $data
	 * @return CatModel
	 */
	public function add(array $data)
	{
		return CatModel::create($data);
	}

	/**
	 * Xoa
	 *
	 * @param CatModel $cat
	 */
	public function delete(CatModel $cat)
	{
		foreach ($cat->children as $child)
		{
			$child->update(['parent_id' => $cat->parent_id]);
		}

		$cat->delete();
	}

	/**
	 * Lay danh sach
	 *
	 * @return Collection
	 */
	public function lists()
	{
		if (is_null($this->list))
		{
		    $this->list = CatModel::all();
		}

		return $this->list;
	}

	/**
	 * Lay thong tin
	 *
	 * @param int $id
	 * @return CatModel|null
	 */
	public function find($id)
	{
		return $this->lists()->whereLoose('id', $id)->first();
	}

	/**
	 * Lay danh sach theo list ids
	 *
	 * @param array $ids
	 * @return Collection
	 */
	public function findList(array $ids)
	{
		return collect(array_filter(array_map([$this, 'find'], $ids)));
	}

	/**
	 * Lay danh sach roots
	 *
	 * @return Collection
	 */
	public function roots()
	{
		return $this->lists()->whereLoose('parent_id', 0);
	}

	/**
	 * Lay danh sach dang phang
	 *
	 * @return Collection
	 */
	public function treeList()
	{
		return $this->listByNodes($this->tree->lists());
	}

	/**
	 * Lay Node tuong ung voi id
	 *
	 * @param int $id
	 * @return NodeInterface|null
	 */
	public function getNode($id)
	{
		return $this->tree->findByValue($id);
	}

	/**
	 * Lay thong tin tu Node
	 *
	 * @param NodeInterface $node
	 * @return CatModel|null
	 */
	public function findByNode(NodeInterface $node)
	{
		return $this->find($node->getValue());
	}

	/**
	 * Tao danh sach tu danh sach Node
	 *
	 * @param array $nodes
	 * @return Collection
	 */
	public function listByNodes(array $nodes)
	{
		$ids = Tree::listNodesValue($nodes);

		return $this->findList($ids);
	}

}