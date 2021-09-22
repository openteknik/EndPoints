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
define('EndPoints', ossn_route()->com . 'EndPoints/');

function premium_endpoints_api_init() {
		ossn_add_hook('services', 'methods', 'premium_endpoints_api');
}
function premium_endpoints_api($hook, $type, $methods, $params) {
		$methods['v6.0'][] = 'banuser/ban';
		$methods['v6.0'][] = 'banuser/unban';
		$methods['v6.0'][] = 'birthday/upcoming';
		
		$methods['v6.0'][] = 'event/add';
		$methods['v6.0'][] = 'event/edit';
		$methods['v6.0'][] = 'event/finished';
		$methods['v6.0'][] = 'event/delete';
		$methods['v6.0'][] = 'event/relation';
		$methods['v6.0'][] = 'event/list';
		$methods['v6.0'][] = 'event/view';
		
		$methods['v6.0'][] = 'poll/add';		
		$methods['v6.0'][] = 'poll/vote';		
		$methods['v6.0'][] = 'poll/end';		
		$methods['v6.0'][] = 'poll/delete';		
		$methods['v6.0'][] = 'poll/list';		
		$methods['v6.0'][] = 'poll/view';		

		$methods['v6.0'][] = 'story/add';		
		$methods['v6.0'][] = 'story/delete';		
		$methods['v6.0'][] = 'story/list';		
		
		$methods['v6.0'][] = 'video/add';				
		$methods['v6.0'][] = 'video/delete';				
		$methods['v6.0'][] = 'video/list';				
		$methods['v6.0'][] = 'video/view';				
		
		$methods['v6.0'][] = 'mp3/add';						
		$methods['v6.0'][] = 'mp3/view';						
		$methods['v6.0'][] = 'mp3/delete';						
		$methods['v6.0'][] = 'mp3/list';						
		$methods['v6.0'][] = 'mp3/user';
		
		$methods['v6.0'][] = 'file/add';						
		$methods['v6.0'][] = 'file/view';						
		$methods['v6.0'][] = 'file/delete';						
		$methods['v6.0'][] = 'file/list';						
		
		$methods['v6.0'][] = 'sharepost/share';		
		
		$methods['v6.0'][] = 'userverified/verify';		
		$methods['v6.0'][] = 'userverified/unverify';		
		return $methods;
}
ossn_register_callback('ossn', 'init', 'premium_endpoints_api_init');