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
$video->{'file:video'} = $video->getFileURL();
$video->{'file:cover'} = $video->getCoverURL();
$params['OssnServices']->successResponse(array(
		'video' => $video,
));