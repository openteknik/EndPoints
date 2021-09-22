<?php
if(!com_is_active('Files')) {
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

$container_guid = input('container_guid');
$container_type = input('container_type');

if(($container_type == 'user' && $container_guid != ossn_loggedin_user()->guid) || !in_array($container_type, files_container_types())){
			$params['OssnServices']->throwError('200', ossn_print('file:add:failed'));
}
if($container_type == 'group' && function_exists('ossn_get_group_by_guid')){
			$group =  ossn_get_group_by_guid($container_type);
			if($group && !$group->isMember($group->guid, ossn_loggedin_user()->guid)){
				$params['OssnServices']->throwError('200', ossn_print('file:add:failed'));			
			}
}

$file = new File;
if(!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK){
		$params['OssnServices']->throwError('200', ossn_print('file:add:failed:1'));
}
if($guid = $file->addFile($desc, $container_guid, $container_type)){
		OssnSession::assign('OSSN_USER', $old_user);
		$file =  ossn_file_get($guid);
		unset($file->data);
		$file->{'file:File'} = $file->getDownloadURL();
		$file->{'icon'} = $file->getIcon();
		$params['OssnServices']->successResponse(array(
				'file' => $file,
		));
} else {
		$params['OssnServices']->throwError('200', ossn_print('file:add:failed'));
}