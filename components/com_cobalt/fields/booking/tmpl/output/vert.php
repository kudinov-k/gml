<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die();
$doc = JFactory::getDocument();
$doc->addScript(JUri::root(true).'/components/com_cobalt/fields/booking/assets/booking.js');
$doc->addStyleSheet(JUri::root(true).'/components/com_cobalt/fields/booking/assets/booking.css');
$main_units = explode("\n", $this->params->get('params.unit', ''));
?>

<div id="booking<?php echo $record->id?>" >
	<!--  <div id="datepicker<?php echo $record->id?>"></div>
	<input type="hidden" name="date" id="date<?php echo $record->id?>" size="100"/> -->

	<table>
		<?php if(isset($this->value['rent']['price']) && $this->value['rent']['price']):?>
		<tr>
			<td class="price-td">
				<span class="price">
					<?php echo $this->nformat($this->getReadyPrice($this->value['rent']));?>
					<?php echo JText::_($this->params->get('params.cur_output', ''))?>
				</span>
			</td>
		</tr>
		<tr>
			<td class="unit-td">
				<span class="unit"><?php echo @$main_units[$this->value['rent']['unit']] ?></span>
			</td>
		</tr>
		<?php $taxes = isset($this->value['tax']) ? $this->value['tax'] : false;?>
		<?php if(!empty($taxes)):?>
		<tr>
			<td  class="tax-td">
				<span class="tax">
					<?php if(!isset($this->value['rent']['tax'])):?>
						Включая
					<?php else:?>
						Не включая
					<?php endif;?>

					<?php echo implode(', ', $this->getSelectedTaxesNames());?>
				</span>
			</td>
		</tr>
		<?php endif;?>
		<tr>
			<td class="button-td">
				<button type="button" class="btn btn-info btn-small" onclick="bookingAddToCart('rent',<?php echo $this->id;?>,<?php echo $record->id;?>,<?php echo $record->section_id;?>);">
				    <?php echo JText::_('CRENT');?>
				</button>
			</td>
		</tr>
		<?php endif;?>

		<?php if(isset($this->value['sale']['price']) && $this->value['sale']['price']):?>

		<tr>
			<td class="price-td">
				<span class="price">
					<?php echo $this->nformat($this->getReadyPrice($this->value['sale']));?>
					<?php echo JText::_($this->params->get('params.cur_output', ''))?>
				</span>
			</td>
		</tr>
		<tr>
			<td class="unit-td">
				<span class="unit"><?php echo @$main_units[$this->value['sale']['unit']] ?></span>
			</td>
		</tr>
		<?php if(!empty($taxes)):?>
		<tr>
			<td class="tax-td">
				<span class="tax">
					<?php if(!isset($this->value['sale']['tax'])):?>
						Включая
					<?php else:?>
						Не включая
					<?php endif;?>

					<?php echo implode(', ', $this->getSelectedTaxesNames());?>
				</span>
			</td>
		</tr>
		<?php endif;?>
		<tr>
			<td class="button-td">
				<button type="button" class="btn btn-info btn-small" onclick="bookingAddToCart('sale',<?php echo $this->id;?>,<?php echo $record->id;?>,<?php echo $record->section_id;?>);">
				    <?php echo JText::_('CSALE');?>
				</button>
			</td>
		</tr>
		<?php endif;?>

		<?php if(isset($this->value['order']['price']) && $this->value['order']['price']):?>
		<tr>
			<td class="price-td">
				<span class="price">
					<?php echo $this->nformat($this->getReadyPrice($this->value['order']));?>
					<?php echo JText::_($this->params->get('params.cur_output', ''));?>
				</span>
			</td>
		</tr>
		<tr>
			<td class="unit-td">
				<span class="unit"><?php echo @$main_units[$this->value['order']['unit']] ?></span>
			</td>
		</tr>
		<?php if(!empty($taxes)):?>
		<tr>
			<td class="tax-td">
				<span class="tax">
					<?php if(!isset($this->value['order']['tax'])):?>
						Включая
					<?php else:?>
						Не включая
					<?php endif;?>

					<?php echo implode(', ', $this->getSelectedTaxesNames());?>
				</span>
			</td>
		</tr>
		<?php endif;?>

		<tr>
			<td class="button-td">
				<button type="button" class="btn btn-info btn-small" onclick="bookingAddToCart('order',<?php echo $this->id;?>,<?php echo $record->id;?>,<?php echo $record->section_id;?>);">
				    <?php echo JText::_('CORDER');?>
				</button>
			</td>
		</tr>
		<?php endif;?>
	</table>

</div>
