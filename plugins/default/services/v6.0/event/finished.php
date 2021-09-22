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
if(!com_is_active('Events')) {
		$params['OssnServices']->throwError('201', ossn_print('ossnservices:component:notfound'));
}
$guid        = input('guid');
$is_finished = input('is_finished');

if(!$user) {
		$params['OssnServices']->throwError('103', ossn_print('ossnservices:nouser'));
}

$object = ossn_get_event($guid);
if(!$object){
	$params['OssnServices']->throwError('200', ossn_print('event:save:failed'));
}

$object->data->is_finished = $is_finished;
if($object->save()) {
		$object->saveImage($object->guid);
		$params['OssnServices']->successResponse(array(
				'event' => ossn_get_event($object->guid),
		));
} else {
		$params['OssnServices']->throwError('200', ossn_print('event:save:failed'));
}
