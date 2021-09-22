<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright 2014-2017 OpenTeknik LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com/
 */
if(!com_is_active('UserVerified')) {
		$params['OssnServices']->throwError('201', ossn_print('ossnservices:component:notfound'));
}
$guid = input('user');
$user = ossn_user_by_guid($guid);
if($user) {
		$user->data->is_verified_user = true;
		if($user->save()) {
				$params['OssnServices']->successResponse(array(
						'success' => ossn_print('userverified:unverifiy:success'),
				));
		}
}
$params['OssnServices']->throwError('200', ossn_print('userverified:unverifiy:failed'));