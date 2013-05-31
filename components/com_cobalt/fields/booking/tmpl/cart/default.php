<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_stats
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<dl>
<?php foreach ($cart as $key => $row) : ?>
	<dt><?php echo $rows[$row['record_id']]->title;?></dt>
	<dd><?php echo $rows[$row['record_id']]->fields_by_id[518]->label;?> <?php echo $rows[$row['record_id']]->fields_by_id[518]->result;?>
		<a href="javascript:void(0);" onclick="Cobalt.removeFromCart(<?php echo $key;?>);"><span class="label label-important">X</span></a>
	</dd>
<?php endforeach; ?>
</dl>
<?php if (count($cart)):?>
<button id="order_cart" class="btn btn-small btn-success" onclick=""><?php echo JText::_('ORDER');?></button>
<?php endif;?>