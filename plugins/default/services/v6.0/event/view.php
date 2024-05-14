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
$guid = input('guid');

$event = ossn_get_event($guid);
if(!$event) {
		$params['OssnServices']->throwError('200', 'Invalid Event');
}
$comment_wall = ossn_get_entities(array(
		'type'       => 'object',
		'subtype'    => 'event:wall',
		'owner_guid' => $event->guid,
));
$comments = $comment_wall[0];
unset($event->{'file:event:photo'});
$event->icon_url       = $event->iconURL('master');
$event->cl_entity_guid = $comments->guid;
$params['OssnServices']->successResponse(array(
		'event' => $event,
));