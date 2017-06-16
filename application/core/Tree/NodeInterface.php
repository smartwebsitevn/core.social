<?php namespace Core\Tree;

interface NodeInterface
{
	/**
	 * Lay node id
	 *
	 * @return int
	 */
	public function getId();

	/**
	 * Lay node value
	 *
	 * @return mixed
	 */
	public function getValue();

	/**
	 * Gan node value
	 *
	 * @param mixed $value
	 * @return NodeInterface
	 */
	public function setValue($value);

	/**
	 * Lay node cha
	 *
	 * @return NodeInterface|null
	 */
	public function getParent();

	/**
	 * Set node cha
	 *
	 * @param NodeInterface $parent
	 * @return NodeInterface
	 */
	public function setParent(NodeInterface $parent = null);

	/**
	 * Them node con
	 *
	 * @param NodeInterface $child
	 * @return NodeInterface
	 */
	public function addChild(NodeInterface $child);

	/**
	 * Loai bo node con
	 *
	 * @param NodeInterface $child
	 * @return NodeInterface
	 */
	public function removeChild(NodeInterface $child);

	/**
	 * Loai bo node con nhung giu lai cac node chau
	 *
	 * @param NodeInterface $child
	 * @return NodeInterface
	 */
	public function removeChildKeepGrandchild(NodeInterface $child);

	/**
	 * Lay danh sach node con
	 *
	 * @return array
	 */
	public function getChildren();

	/**
	 * Gan danh sach node con
	 *
	 * @param array $children
	 * @return NodeInterface
	 */
	public function setChildren(array $children);

	/**
	 * Loai bo tat ca node con
	 *
	 * @return NodeInterface
	 */
	public function emptyChildren();

	/**
	 * Lay node root
	 *
	 * @return NodeInterface
	 */
	public function getRoot();

	/**
	 * Lay to tien cua node hien tai
	 *
	 * @return array
	 */
	public function getAncestors();

	/**
	 * Lay anh chi em cua node hien tai (de quy)
	 *
	 * @return array
	 */
	public function getSiblings();

	/**
	 * Lay tat ca con chau cua node hien tai (de quy)
	 *
	 * @return array
	 */
	public function getDescendants();

	/**
	 * Lay khoang cach tu node hien tai den root
	 *
	 * @return int
	 */
	public function getDepth();

	/**
	 * Lay chieu cao cua cay co root la node nay
	 *
	 * @return int
	 */
	public function getHeight();

	/**
	 * Lay tong so node cua cay co root la node nay
	 *
	 * @return int
	 */
	public function getSize();

	/**
	 * Kiem tra 1 node khac co phai node hien tai hay khong
	 *
	 * @param NodeInterface $node
	 * @return bool
	 */
	public function is(NodeInterface $node);

	/**
	 * Kiem tra node hien tai co phai la root hay khong
	 *
	 * @return bool
	 */
	public function isRoot();

	/**
	 * Kiem tra node hien tai co node con hay khong
	 *
	 * @return bool
	 */
	public function isLeaf();

	/**
	 * Kiem tra node hien tai co phai node con hay khong
	 *
	 * @return bool
	 */
	public function isChild();

	/**
	 * Kiem tra node hien tai co phai la con chau cua node khac hay khong
	 *
	 * @param NodeInterface $node
	 * @return bool
	 */
	public function isDescendantOf(NodeInterface $node);

	/**
	 * Kiem tra node hien tai co phai la to tien cua node khac hay khong
	 *
	 * @param NodeInterface $node
	 * @return bool
	 */
	public function isAncestorOf(NodeInterface $node);

	/**
	 * Goi ham visit cua visitor voi node hien tai
	 *
	 * @param Visitor $visitor
	 * @return mixed
	 */
	public function accept(Visitor $visitor);
}