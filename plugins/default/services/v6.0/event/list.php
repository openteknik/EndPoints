<?php
/**
 * Open Source Social Network
 *
 * @package   (openteknik.com).ossn
 * @author    OSSN Core Team <info@openteknik.com>
 * @copyright (C) OpenTeknik LLC
 * @license   OPENTEKNIK LLC, COMMERCIAL LICENSE v1.0 https://www.openteknik.com/license/commercial-license-v1
 * @link      https://www.openteknik.com
 */
if(!com_is_active('Events')) {
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
$events = new Events();
$list   = $events->getEvents($args);

$args['count'] = true;
$count         = $events->getEvents($args);
$results       = array();
if($list) {
		foreach($list as $item) {
				unset($item->{'file:event:photo'});
				$item->icon_url = $item->iconURL('master');
				$results[]      = $item;
		}
}
$params['OssnServices']->successResponse(array(
		'list'   => $results,
		'offset' => input('offset'),
		'count'  => $count,
));