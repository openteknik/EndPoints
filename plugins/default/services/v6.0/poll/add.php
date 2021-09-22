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

$container_guid = input('container_guid');
$container_type = input('container_type');
$title          = input('title');
$counter        = input('counter');

foreach(range(1, $counter) as $item) {
		$options[] = input("poll_options_{$item}");
}
if(($container_type == 'user' && $container_guid != ossn_loggedin_user()->guid) || !in_array($container_type, polls_container_types())) {
		$params['OssnServices']->throwError('200', ossn_print('polls:error:created'));
}
if($container_type == 'group' && function_exists('ossn_get_group_by_guid')) {
		$group = ossn_get_group_by_guid($conatiner_guid);
		if($group && !$group->isMember($group->guid, ossn_loggedin_user()->guid)) {
				$params['OssnServices']->throwError('200', ossn_print('polls:error:created'));
		}
}
$poll = new Softlab24\Ossn\Component\Polls();
if($guid = $poll->addPoll($title, $container_guid, $container_type, $options)) {
		OssnSession::assign('OSSN_USER', $old_user);
		$params['OssnServices']->successResponse(array(
				'poll' => ossn_poll_get($guid),
		));
} else {
		$params['OssnServices']->throwError('200', ossn_print('polls:error:created'));
}