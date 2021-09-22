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
$guid = input('guid');
$mp3 =  mp3_get_file($guid);
if($mp3){
		$mp3->{'file:mp3file'} = $mp3->getPlayURL();
		$params['OssnServices']->successResponse(array(
				'mp3' => $mp3,
		));
} else {
		$params['OssnServices']->throwError('200', 'No such a file');
}