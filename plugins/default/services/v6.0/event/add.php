<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE v1.0 https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com
 */
if(!com_is_active('Events')) {
		$params['OssnServices']->throwError('201', ossn_print('ossnservices:component:notfound'));
}

$title = input('title');
$info  = input('info');
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
$vars = array(
		'country'    => '',
		'location'   => input('location'),
		'event_cost' => input('event_cost'),
		'date'       => input('date'),
		'start_time' => input('start_time'),
		'end_time'   => input('end_time'),
		'comments'   => input('allowed_comments'),
);
$container_type = input('container_type');
$container_guid = input('container_guid');

if($container_type == 'group') {
		$group = ossn_get_object($container_guid);
		if(!$group || $group->subtype !== 'ossngroup') {
				$params['OssnServices']->throwError('200', ossn_print('event:creation:failed'));
		}
}
if($container_type != 'user' && $container_type != 'group') {
		$params['OssnServices']->throwError('200', ossn_print('event:creation:failed'));
}
if(empty($title) || empty($info) || (empty($vars['date']) && empty($_FILES['picture']))) {
		$params['OssnServices']->throwError('200', ossn_print('event:fields:required:title:info'));
}

$event                       = new Events();
$event->data->container_type = $container_type;
if($guid = $event->addEvent($title, $info, $container_guid, $vars)) {
		OssnSession::assign('OSSN_USER', $old_user);
		$params['OssnServices']->successResponse(array(
				'event' => ossn_get_event($guid),
		));
} else {
		$params['OssnServices']->throwError('200', ossn_print('event:creation:failed'));
}