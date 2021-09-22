<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OPENTEKNIK LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE v1.0 https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com
 */
if(!com_is_active('MP3')) {
		$params['OssnServices']->throwError('201', ossn_print('ossnservices:component:notfound'));
}
$uguid = input('uguid');
$user  = false;
$user = ossn_user_by_guid($uguid);
if(!$user) {
		$params['OssnServices']->throwError('103', ossn_print('ossnservices:nouser'));
}

$files = new MP3();
$all   = $files->getAll(array(
		'owner_guid' => $user->guid,
		'entities_pairs' => array(
				array(
						'name'  => 'container_type',
						'value' => 'user',
				),
		),
));
$count = $files->getAll(array(
		'owner_guid' => $user->guid,
		'count'          => true,
		'entities_pairs' => array(
				array(
						'name'  => 'container_type',
						'value' => 'user',
				),
		),
));
$results = array();
if($all) {
		foreach($all as $item) {
				unset($item->data);
				$item->{'file:mp3file'} = $item->getPlayURL();
				$results[]              = $item;
		}
		$params['OssnServices']->successResponse(array(
				'list' => $results,
				'offset' => input('offset'),
				'count' => $count,
		));
} else {
		$params['OssnServices']->throwError('200', 'Nothing to show');
}
