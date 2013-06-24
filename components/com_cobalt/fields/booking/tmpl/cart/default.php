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

<table class="table table-striped">
	<thead>
		<tr>
			<?php if($params->get('show_title')):?>
				<th><?php echo JText::_('CTITLE');?></th>
			<?php endif;?>
			<?php foreach ($params->get('show_fields', array()) AS $key):?>
				<th width="1%" nowrap="nowrap">
					<?php echo $rows[$row['record_id']]->fields_by_key[$key]->label;?>
			<?php endforeach;?>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($cart as $key_r => $row) : ?>
		<tr>
			<?php if($params->get('show_title')):?>
				<td><?php echo $rows[$row['record_id']]->title?></td>
			<?php endif;?>
			<?php foreach ($params->get('show_fields', array()) AS $key):?>
				<?php //if (!isset($rows[$row['record_id']]->fields_by_key[$key])) continue;?>
				<?php $field = $rows[$row['record_id']]->fields_by_key[$key];?>
				<td class="<?php echo $field->params->get('core.field_class')?>"><?php if(isset($field->result)) echo $field->result ;?></td>
			<?php endforeach;?>
			<td>
				<a href="javascript:void(0);" onclick="Cobalt.removeFromCart(<?php echo $key_r;?>);"><span class="label label-important">X</span></a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php if (count($cart)):?>
<button id="order_cart" class="btn btn-small btn-success" onclick=""><?php echo JText::_('ORDER');?></button>
<?php endif;?>