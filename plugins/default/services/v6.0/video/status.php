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
$guid  = input('guid');
$video = ossn_get_video($guid);
if(!$video) {
		$params['OssnServices']->throwError('200', 'Invalid video');
}
unset($video->data);

$dir  = ossn_get_userdata("object/{$video->guid}/video/file/");
$file = $dir . 'progress.txt';

$status = $video->is_pending;
if(is_file($file)){
	$params['OssnServices']->successResponse(array(
			'status' => $status,
			'progress' => Videos::progress($file),
	));
} else {
	$params['OssnServices']->successResponse(array(
			'status' => $status,
			'progress' => 0,
	));	
}