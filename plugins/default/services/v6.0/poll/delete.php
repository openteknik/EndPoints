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
if(!com_is_active('Polls')) {
		$params['OssnServices']->throwError('201', ossn_print('ossnservices:component:notfound'));
}
$uguid = input('uguid');
$user  = false;
if($uguid) {
		$user = ossn_user_by_guid($uguid);
}
if(!$user) {
		$params['OssnServices']->throwError('103', ossn_print('ossnservices:nouser'));
}
OssnSession::assign('OSSN_USER', $user);

$guid = input('guid');
$poll = ossn_poll_get($guid);
if(!$poll || ($poll->owner_guid !== ossn_loggedin_user()->guid && $poll->container_type == 'user' && !ossn_isAdminLoggedin())) {
		$params['OssnServices']->throwError('200', ossn_print('polls:failed:delete'));
}
if($poll->container_type == 'group') {
		if(function_exists('ossn_get_group_by_guid')) {
				$group = ossn_get_group_by_guid($poll->container_guid);
				if($group && ($group->owner_guid !== ossn_loggedin_user()->guid && $poll->owner_guid !== ossn_loggedin_user()->guid)) {
						$params['OssnServices']->throwError('200', ossn_print('polls:failed:delete'));
				}
		}
}
$poll->removeData();
if($poll->deleteObject()) {
		OssnSession::assign('OSSN_USER', $old_user);
		$params['OssnServices']->successResponse(array(
				'success' => ossn_print('polls:success:delete'),
		));
} else {
		$params['OssnServices']->throwError('200', ossn_print('polls:failed:delete'));
}