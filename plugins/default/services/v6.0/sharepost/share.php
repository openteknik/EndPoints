<?php
/**
 * Open Source Social Network
 *
 * @package Open Source Social Network
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OPENTEKNIK  LLC, COMMERCIAL LICENSE
 * @license   OPENTEKNIK  LLC, COMMERCIAL LICENSE, COMMERCIAL LICENSE https://www.openteknik.com/license/commercial-license-v1
 * @link      http://www.opensource-socialnetwork.org/licence
 */
if(!com_is_active('SharePost')) {
		$params['OssnServices']->throwError('201', ossn_print('ossnservices:component:notfound'));
} 
$guid              = input('guid');
$type              = input('type');
$friend            = input('friend');
$group             = input('group');

$friend_group_guid = false;
$wall              = new OssnWall();
$post              = $wall->GetPost($guid);

if(!$post){
	$params['OssnServices']->throwError('200', 'Invalid Post');	
}
if($post->access == OSSN_FRIENDS){
	$params['OssnServices']->throwError('200', 'Only public posts can be shared');
}
if($post && isset($post->item_type) && $post->item_type == 'post:share:post') {
		$guid = intval($post->item_guid);
} else {
	$params['OssnServices']->throwError('200', 'This type of posts can not be shared');	
}
if(!empty($friend)) {
		$friend_group_guid = $friend;
}
if(!empty($group)) {
		$friend_group_guid = $group;
}
if(empty($friend_group_guid) && ($type == 'friend' || $type == 'group')) {
		$params['OssnServices']->throwError('200', ossn_print('post:share:error'));	
}
if(share_post($type, $guid, $friend_group_guid)) {
		$params['OssnServices']->successResponse(array(
				'success' => ossn_print('post:shared'),
		));		
}
$params['OssnServices']->throwError('200', ossn_print('post:share:error'));	