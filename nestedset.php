<?php
/**
* Copyright (c) 2011, Leon Sorokin
* All rights reserved.
*
* nestedset.php
* unobtrusive MPTT / nested set and tree manipulation
* http://en.wikipedia.org/wiki/Nested_set_model
* http://www.wallpaperama.com/forums/mptt-modified-preorder-tree-traversal-php-tree-menu-script-t5713.html
*/

class NestedSet {
	public static $l_key = 'lft';		// left
	public static $r_key = 'rgt';		// right
	public static $c_key = 'kids';		// children
	public static $d_key = 'lvl';		// depth
//	public static $o_key = 'pos';		// order/position (of adjacent children)
//	public static $p_key = 'prt';		// parent

	// enumerates nested set's left and right values
	public static function enumTree($tree, &$ctr = 1, $lvl = 0)
	{
		$tree->{self::$d_key} = $lvl;

		$tree->{self::$l_key} = $ctr++;
		foreach ($tree->{self::$c_key} as &$k)
			$k = self::enumTree($k, $ctr, $lvl + 1);
		$tree->{self::$r_key} = $ctr++;

		return $tree;
	}

	// flattens nodes, killing children
	public static function fromTree($tree, $init = TRUE)
	{
		$init && $tree = self::enumTree($tree);

		$kids = $tree->{self::$c_key};
		unset($tree->{self::$c_key});
		$arr = array($tree);
		foreach ($kids as $k)
			$arr = array_merge($arr, self::fromTree($k, FALSE));

		return $arr;
	}

	public static function strip($node)
	{
		unset($node->{self::$l_key});
		unset($node->{self::$r_key});
		unset($node->{self::$d_key});

		return $node;
	}

	public static function toTree($flat)
	{
		$plft	= 0;			// previous left value
		$stack	= array();

		// append a closing trigger element, since stack rollups triggered by lft diffs
		$flat[] = (object)array(self::$l_key => $flat[0]->{self::$r_key});

		foreach ($flat as $itm) {
			$itm = clone $itm;
			$ld = $itm->{self::$l_key} - $plft;

			// rollup
			if ($ld != 1) {
				while (--$ld) {
					$itm2 = array_pop($stack);
					$par = &end($stack);
					$par->{self::$c_key}[] = self::strip($itm2);
				}
			}

			$itm->{self::$c_key} = array();
			$stack[] = $itm;
			$plft = $itm->{self::$l_key};
		}

		return self::strip($stack[0]);
	}
}
