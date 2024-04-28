<?php
/**
 * Open Source Social Network
 *
 * @package   Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   Open Source Social Network License (OSSN LICENSE)  http://www.opensource-socialnetwork.org/licence
 * @link      https://www.opensource-socialnetwork.org/
 */
if(!com_is_active('Polls')) {
		$params['OssnServices']->throwError('201', ossn_print('ossnservices:component:notfound'));
}
$container_guid = input('container_guid');
$container_type = input('container_type');
if(empty($container_type)) {
		$params['OssnServices']->successResponse(array(
				'list' => false,
		));
}
$args = array(
		'entities_pairs' => array(
				array(
						'name'  => 'container_type',
						'value' => $container_type,
				),
		),
);
if(isset($container_guid) && !empty($container_guid)) {
		$args['entities_pairs'][] = array(
				'name'  => 'container_guid',
				'value' => $container_guid,
		);
}
$poll = new \Softlab24\Ossn\Component\Polls();
$all  = $poll->getAll($args);

$args['count'] = true;
$count         = $poll->getAll($args);

$results = false;
if($all) {
		$results = array();
		foreach ($all as $pitem) {
				$pitem->votes = $pitem->getVotes();
				$results[]    = $pitem;
		}
}
$params['OssnServices']->successResponse(array(
		'list'   => $results,
		'offset' => input('offset'),
		'count'  => $count,
));