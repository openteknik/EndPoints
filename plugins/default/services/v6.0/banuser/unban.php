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

if(!com_is_active('BanUser')) {
		$params['OssnServices']->throwError('201', ossn_print('ossnservices:component:notfound'));
}
$guid = input('guid');
$user = ossn_user_by_guid($guid);
if(!$user) {
		$params['OssnServices']->throwError('103', ossn_print('ossnservices:nouser'));
}
if($user->isAdmin()) {
		$params['OssnServices']->throwError('200', 'Admin user can not be banned!');
}
$user->data->is_banned = false;
$user->data->icon_time = time();
if($user->save()) {
		$params['OssnServices']->successResponse(array(
				'success' => ossn_print('banuser:unbanned'),
				'user'    => $params['OssnServices']->setUser($user),
		));
} else {
		$params['OssnServices']->throwError('200', ossn_print('banuser:unban:failed'));
}