<?php
/**
 * Open Source Social Network
 *
 * @package   (Informatikon.com).ossn
 * @author    OSSN Core Team <info@opensource-socialnetwork.org>
 * @copyright (C) OPENTEKNIK  LLC, COMMERCIAL LICENSE
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      http://www.opensource-socialnetwork.org/licence
 */
if(!com_is_active('Events')) {
		$params['OssnServices']->throwError('201', ossn_print('ossnservices:component:notfound'));
}
$title            = input('title');
$info             = input('info');
$guid             = input('guid');
$allowed_comments = input('allowed_comments');

if($allowed_comments && $allowed_comments == 'yes') {
		$allowed_comments = 1;
}
if($allowed_comments && $allowed_comments == 'no') {
		$allowed_comments = 0;
}

$vars = array(
		'country'    => '',
		'location'   => input('location'),
		'event_cost' => input('event_cost'),
		'date'       => input('date'),
		'start_time' => input('start_time'),
		'end_time'   => input('end_time'),
		'comments'   => $allowed_comments,
);
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
if(empty($title) || empty($info) || empty($vars['date'])) {
		$params['OssnServices']->throwError('200', ossn_print('event:fields:required:title:info'));
}
$object = ossn_get_event($guid);
if(!$object){
	$params['OssnServices']->throwError('200', ossn_print('event:save:failed'));
}
if(!$object || $object->owner_guid !== ossn_loggedin_user()->guid) {
		$params['OssnServices']->throwError('200', ossn_print('event:save:failed'));
}

$object->title       = $title;
$object->description = $info;

$object->data->event_cost             = $vars['event_cost'];
$object->data->country                = $vars['country'];
$object->data->location               = $vars['location'];
$object->data->date                   = $vars['date'];
$object->data->start_time             = $vars['start_time'];
$object->data->end_time               = $vars['end_time'];
$object->data->last_update            = time();
$object->data->allowed_comments_likes = $vars['comments'];

if($object->save()) {
		$object->saveImage($object->guid);
		OssnSession::assign('OSSN_USER', $old_user);
		$params['OssnServices']->successResponse(array(
				'event' => ossn_get_event($object->guid),
		));
} else {
		$params['OssnServices']->throwError('200', ossn_print('event:save:failed'));
}