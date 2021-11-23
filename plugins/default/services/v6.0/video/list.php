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
				$comments = new OssnComments();
				$entity   = ossn_get_entities(array(
						'type'       => 'object',
						'owner_guid' => $video->guid,
						'subtype'    => 'file:video',
				));
				$total_comments = $comments->countComments($entity[0]->guid, 'entity');
				if(!$total_comments) {
						$total_comments = 0;
				}
				$OssnLikes = new OssnLikes();
				$likes     = $OssnLikes->CountLikes($entity[0]->guid, 'entity');
				if(!$likes) {
						$likes = 0;
				}
				$video->{'file:video'} = $video->getFileURL();
				$video->{'file:cover'} = $video->getCoverURL();
				$video->total_comments = $total_comments;
				$video->total_likes    = $likes;
				$video->cl_entity_guid = $entity[0]->guid;
				$results[]             = $video;
		}
}
$params['OssnServices']->successResponse(array(
		'list'   => $results,
		'offset' => input('offset'),
		'count'  => $count,
));