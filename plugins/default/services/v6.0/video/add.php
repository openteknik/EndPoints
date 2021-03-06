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
if(!com_is_active('Videos')) {
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
$old_user = false;
if(ossn_isLoggedin()) {
		$old_user = ossn_loggedin_user();
}
OssnSession::assign('OSSN_USER', $user);

set_time_limit(0);
session_write_close();	

$container_guid = input('container_guid');
$container_type = input('container_type'); 
 
$title = input('title');
$description = input('description');

$error_cnt = 0;
 
$errors = array();
/* error simulation  if(($container_type == 'user' && $container_guid + 1 !== ossn_loggedin_user()->guid) || !in_array($container_type, videos_container_types())){ */
if(($container_type == 'user' && $container_guid != ossn_loggedin_user()->guid) || !in_array($container_type, videos_container_types())){
		$error_cnt++;
	 	$error['errors'][] = ossn_print('video:com:upload:failed');
}
if(empty($container_guid)){
		$error_cnt++;
	 	$error['errors'][] = ossn_print('video:com:upload:failed');	
}
if($container_type == 'group' && function_exists('ossn_get_group_by_guid')){
		$group =  ossn_get_group_by_guid($conatiner_guid);
		if($group && !$group->isMember($group->guid, ossn_loggedin_user()->guid)){
				$error_cnt++;
				$error['errors'][] = ossn_print(ossn_print('video:com:upload:failed'));			
		}
} 

// as you'll see here, the XHR redirects won't really help here 
// so better rely on error count! 
//error_log('continue 39');

if(!$error_cnt) {
		$file = new OssnFile;
		$extension = $file->getFileExtension($_FILES['video']['name']);
		$tmp_path = $_FILES['video']['tmp_name'];
 
		$video = new Videos;
		$extensions = array('3gp', 'mov', 'avi', 'wmv', 'flv', 'mp4');
		if(!in_array($extension, $extensions)){
				$error_cnt++;
				$error['errors'][] = ossn_print('video:com:upload:conversion:failed');
		}
} 
if(!$error_cnt) {
		if($guid = $video->addVideo($title, $description,  $container_guid, $container_type)){
				$video = ossn_get_video($guid);
				if(isset($newfile)){
						unlink($newfile);
						unlink($progress_file);		 
				}
				
				$video->{'file:video'} = $video->getFileURL();
				$video->{'file:cover'} = $video->getCoverURL();
				
				$params['OssnServices']->successResponse(array(
					'video' => $video,
				));
		} else {
				if(isset($newfile)){
						unlink($newfile);
						unlink($progress_file);
				}
				$error['errors'][] =	ossn_print('video:com:upload:conversion:failed');
		}
}
$params['OssnServices']->throwError('200', $errors);