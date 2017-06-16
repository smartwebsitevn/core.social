<?php namespace Core\Tree;

interface Visitor
{
	/**
	 * Visit node
	 *
	 * @param NodeInterface $node
	 * @return mixed
	 */
	public function visit(NodeInterface $node);
}