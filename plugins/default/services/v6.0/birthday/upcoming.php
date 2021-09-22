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

if(!com_is_active('Birthdays')) {
		$params['OssnServices']->throwError('201', ossn_print('ossnservices:component:notfound'));
}
$guid = input('guid');
$user = ossn_user_by_guid($guid);

if(!$user) {
		$params['OssnServices']->throwError('103', ossn_print('ossnservices:nouser'));
}

$birthdays = ossn_get_upcomming_birthdays($user);
$result    = array();
if($birthdays){	
	foreach($birthdays as $u){
			$result[] = array(
					'birthdate' => $u->birthdate,
					'user' => $params['OssnServices']->setUser($u)
			);
	}
	$params['OssnServices']->successResponse(array(
				'birthdays'  => $result,
	));	
} else {
	$params['OssnServices']->successResponse(array(
				'birthdays'  => false,
	));		
}