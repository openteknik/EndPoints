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
$container_guid = input('container_guid');
$container_type = input('container_type');
if(empty($container_type)) {
		$params['OssnServices']->successResponse(array(
				'list' => false,
		));
}
$args = array(
		'entities_pairs' => array(
				array(
						'name'  => 'container_type',
						'value' => $container_type,
				),
		),
);
if(isset($container_guid) && !empty($container_guid)) {
		$args['entities_pairs'][] = array(
				'name'  => 'container_guid',
				'value' => $container_guid,
		);
}
$files = new File();

$all = $files->getAll($args);

$args['count'] = true;
$count         = $files->getAll($args);

$results = array();
if($all) {
		foreach($all as $item) {
				unset($item->data);
				$item->{'file:File'} = $item->getDownloadURL();
				$item->icon          = $item->getIcon();
				$results[]           = $item;
		}
		$params['OssnServices']->successResponse(array(
				'list'   => $results,
				'offset' => input('offset'),
				'count'  => $count,
		));
} else {
		$params['OssnServices']->throwError('200', 'Nothing to show');
}