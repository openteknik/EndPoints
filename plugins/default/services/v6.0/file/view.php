<?php
/**
 * Open Source Social Network
 *
 * @package   (softlab24.com).ossn
 * @author    OSSN Core Team <info@softlab24.com>
 * @copyright (C) SOFTLAB24 LIMITED
 * @license   SOFTLAB24 LIMITED, COMMERCIAL LICENSE v1.0 https://www.softlab24.com/license/commercial-license-v1
 * @link      https://www.softlab24.com
 */
if(!com_is_active('Files')) {
		$params['OssnServices']->throwError('201', ossn_print('ossnservices:component:notfound'));
}
$guid = input('guid');
$file =  ossn_file_get($guid);
if($file){
		unset($file->data);
		$file->{'file:File'} = $file->getDownloadURL();
		$file->{'icon'} = $file->getIcon();
		$params['OssnServices']->successResponse(array(
				'file' => $file,
		));
} else {
		$params['OssnServices']->throwError('200', 'No such a file');
}