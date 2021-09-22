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

$object = ossn_get_event($guid);
if(!$object) {
		$params['OssnServices']->throwError('200', 'Invalid Event');
}
unset($object->{'file:event:photo'});
$object->icon_url = $object->iconURL('master');
$params['OssnServices']->successResponse(array(
		'event' => $object,
));