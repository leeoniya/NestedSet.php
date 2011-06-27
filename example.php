<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Nested Set - Examples</title>
	
	<link rel="stylesheet" type="text/css" href="dump_r/dump_r.css" media="all" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.1.min.js"></script>
	<script type="text/javascript" src="dump_r/dump_r.js"></script>
</head>
<body>
	<?php

	require		'nestedset.php';
	@include	'dump_r/dump_r.php';
	
	function printr($obj) {
		if (class_exists('dump_r'))
			new dump_r($obj);
		else
			print_r($obj);
	}

	$tree = (object)array(
		'id'	=> 'root',
		'kids'	=> array(
			(object)array('id'=>'a', 'kids' => array(
				(object)array('id'=>'d', 'kids' => array(
					(object)array('id'=>'g', 'kids' => array(
						(object)array('id'=>'i', 'kids' => array())
					)),
					(object)array('id'=>'h', 'kids' => array())
				)),
				(object)array('id'=>'e', 'kids' => array(
					(object)array('id'=>'j', 'kids' => array())
				)),
				(object)array('id'=>'f', 'kids' => array(
					(object)array('id'=>'k', 'kids' => array()),
					(object)array('id'=>'l', 'kids' => array())
				)),
			)),
			(object)array('id'=>'b', 'kids' => array(
				(object)array('id'=>'m', 'kids' => array(
					(object)array('id'=>'o', 'kids' => array())
				)),
				(object)array('id'=>'n', 'kids' => array(
					(object)array('id'=>'p', 'kids' => array())
				)),
			)),
			(object)array('id'=>'c', 'kids' => array(
				(object)array('id'=>'q', 'kids' => array()),
			)),
		)
	);
	
	printr($tree);

	$flat = NestedSet::fromTree($tree);

	printr($flat);

	printr(NestedSet::toTree($flat));
?>
</body>
</html>