<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_stats
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the syndicate functions only once
include_once JPATH_ROOT . '/components/com_cobalt/api.php';
require_once __DIR__ . '/helper.php';

$doc = JFactory::getDocument();

$doc->addStyleSheet(JUri::root(true).'/components/com_cobalt/fields/booking/datepicker/css/redmond/jquery-ui-1.10.3.custom.css');
$doc->addScript(JUri::root(true).'/components/com_cobalt/fields/booking/datepicker/js/jquery-ui-1.10.3.custom.js');
$doc->addStyleSheet(JUri::root(true).'/components/com_cobalt/fields/booking/datepicker/css/mdp.css');

//$cart = $app->getUserState('booking_cart');
// $rows = modBookingHelper::getList($params);
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
require JModuleHelper::getLayoutPath('mod_booking', $params->get('layout', 'default'));
