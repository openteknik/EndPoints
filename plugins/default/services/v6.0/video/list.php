<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OPENTEKNIK  LLC, COMMERCIAL LICENSE
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      http://www.opensource-socialnetwork.org/licence
 */
if(!com_is_active('Videos')) {
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
$videos        = new Videos();
$all           = $videos->getAll($args);
$args['count'] = true;
$count         = $videos->getAll($args);

$results = array();
if($all) {
		foreach($all as $video) {
				unset($video->data);
				$video->{'file:video'} = $video->getFileURL();
				$video->{'file:cover'} = $video->getCoverURL();
				$results[]             = $video;
		}
}
$params['OssnServices']->successResponse(array(
		'list'   => $results,
		'offset' => input('offset'),
		'count'  => $count,
));