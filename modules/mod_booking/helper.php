<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_stats
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_stats
 *
 * @package     Joomla.Site
 * @subpackage  mod_stats
 * @since       1.5
 */
class modBookingHelper
{
	public static function &getList(&$params)
	{
		$app	= JFactory::getApplication();
		$rows	= array();

		$cart = $app->getUserState('booking_cart');

		$model = JModelLegacy::getInstance('Record', 'CobaltModel');
		$filter_cart = array_unique($cart);
		foreach ($filter_cart as $record_id)
		{
			$record = ItemsStore::getRecord($record_id);
			$rows[$record_id] = $model->_prepareItem($record);
		}
		return $rows;
	}
}
