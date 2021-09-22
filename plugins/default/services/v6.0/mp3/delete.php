<?php
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

$guid = input('guid');
$file = mp3_get_file($guid);
if(!$file){
	$params['OssnServices']->throwError('103', ossn_print("mp3file:delete:failed"));
}
if(!$file || ($file->owner_guid !== ossn_loggedin_user()->guid && $file->container_type == 'user')){
			$params['OssnServices']->throwError('103',ossn_print("mp3file:delete:failed")); 
}

$file->removeData();
if($file->deleteObject()){
		OssnSession::assign('OSSN_USER', $old_user);
		$params['OssnServices']->successResponse(array(
				'success' => ossn_print("mp3file:deleted"),
		));		
}	
$params['OssnServices']->throwError('103',ossn_print("mp3file:delete:failed"));
