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
$guid  = input('guid');
$video = ossn_get_video($guid);
if(!$video) {
		$params['OssnServices']->throwError('200', 'Invalid video');
}
unset($video->data);

$entity = ossn_get_entities(array(
		'type'       => 'object',
		'owner_guid' => $video->guid,
		'subtype'    => 'file:video',
));
$comments       = new OssnComments();
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

$params['OssnServices']->successResponse(array(
		'video' => $video,
));