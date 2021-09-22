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
if(!com_is_active('Stories')) {
		$params['OssnServices']->throwError('201', ossn_print('ossnservices:component:notfound'));
}
$uguid = input('uguid');
$user  = false;
if($uguid) {
		$user = ossn_user_by_guid($uguid);
}
if(!$user) {
		$params['OssnServices']->throwError('103', ossn_print('ossnservices:nouser'));
}
$old_user = false;
if(ossn_isLoggedin()) {
		$old_user = ossn_loggedin_user();
}
OssnSession::assign('OSSN_USER', $user);

$guid  = input('guid');
$video = ossn_get_video($guid);
if(!$video) {
		$params['OssnServices']->throwError('200', ossn_print('video:com:invalid'));
}
$user = ossn_loggedin_user();

if(!$video || ($video->owner_guid !== ossn_loggedin_user()->guid && $video->container_type == 'user' && !ossn_isAdminLoggedin())) {
		$params['OssnServices']->throwError('200', ossn_print('video:com:delete:fail'));
}
if($video->container_type == 'group') {
		if(function_exists('ossn_get_group_by_guid')) {
				$group = ossn_get_group_by_guid($video->container_guid);
				if($group && ($group->owner_guid !== ossn_loggedin_user()->guid && $video->owner_guid !== ossn_loggedin_user()->guid)) {
						if(!$user->canModerate()) {
								$params['OssnServices']->throwError('200', ossn_print('video:com:delete:fail'));
						}
				}
		}
}
$entity = ossn_get_entities(array(
		'type'       => 'object',
		'owner_guid' => $video->guid,
		'subtype'    => 'file:video',
));

if(class_exists('OssnLikes')) {
		$likes = new OssnLikes();
		$likes->deleteLikes($entity[0]->guid, 'entity');
}
if(class_exists('OssnComments')) {
		$comments = new OssnComments();
		$comments->commentsDeleteAll($entity[0]->guid, 'comments:entity');
}

$video->deleteWallPost($video->guid);
if($video->deleteObject()) {
		OssnSession::assign('OSSN_USER', $old_user);
		$params['OssnServices']->successResponse(array(
				'success' => ossn_print('video:com:deleted'),
		));
}
$params['OssnServices']->throwError('200', ossn_print('video:com:delete:fail'));