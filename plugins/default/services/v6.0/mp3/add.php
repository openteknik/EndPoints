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

$desc = input('desc');

$container_guid = $user->guid;
$container_type = 'user';

if($container_type == 'user' && $container_guid != ossn_loggedin_user()->guid){
			$params['OssnServices']->throwError('200', ossn_print('mp3file:add:failed'));
}
$mymusic = input('mymusic');
if(!$mymusic){
			$params['OssnServices']->throwError('200', ossn_print('mp3file:rights:error'));
}
$file = new MP3;
if(!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK){
		$params['OssnServices']->throwError('200', ossn_print('mp3file:add:failed:1'));
	
}
if($guid = $file->addFile($desc, $container_guid, $container_type)){
		OssnSession::assign('OSSN_USER', $old_user);
		
		$mp3 =  mp3_get_file($guid);
		$mp3->{'file:mp3file'} = $mp3->getPlayURL();
		$params['OssnServices']->successResponse(array(
				'mp3' => $mp3,
		));
} else {
		$params['OssnServices']->throwError('200', ossn_print('mp3file:add:failed'));
}