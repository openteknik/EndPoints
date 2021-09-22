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
$guid   = input('guid');
$object = ossn_get_event($guid);
if(!$object) {
		$params['OssnServices']->throwError('103', ossn_print('event:delete:failed'));
}
if($object->deleteObject()) {
		$list = (array) ossn_get_relationships(array(
				'to'   => $object->guid,
				'type' => ossn_events_relationship_default(),
		));
		if($list) {
				foreach($list as $item) {
						ossn_delete_relationship_by_id($item->relation_id);
				}
		}
		$params['OssnServices']->successResponse(array(
				'success' => ossn_print('event:deleted'),
		));
}
$params['OssnServices']->throwError('103', ossn_print('event:delete:failed'));