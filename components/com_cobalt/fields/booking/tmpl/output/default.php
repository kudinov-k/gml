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
// dont forget css
// $doc->addScript(JUri::root(true).'/components/com_cobalt/fields/booking/datepicker/js/jquery-ui-1.10.3.custom.js');
// $doc->addStyleSheet(JUri::root(true).'/components/com_cobalt/fields/booking/datepicker/css/mdp.css');
//$doc->addScript(JUri::root(true).'/components/com_cobalt/fields/booking/datepicker/js/jquery-ui.multidatespicker.js');

$main_units = explode("\n", $this->params->get('params.unit', ''));
?>

<div id="booking<?php echo $record->id?>" >
	<!--  <div id="datepicker<?php echo $record->id?>"></div>
	<input type="hidden" name="date" id="date<?php echo $record->id?>" size="100"/> -->

	<table>
		<?php if(isset($this->value['rent']['price']) && $this->value['rent']['price']):?>
		<tr>
			<td>
				<button type="button" class="btn btn-info btn-small" onclick="bookingAddToCart('rent',<?php echo $this->id;?>,<?php echo $record->id;?>,<?php echo $record->section_id;?>);">
				    <?php echo JText::_('CRENT');?>
				</button>
			</td>
			<td>
				<?php if(!isset($this->value['rent']['fix'])):?>
					<?php echo $this->getReadyPrice($this->value['rent']['price']);?>
				<?php else:?>
					<?php echo $this->value['rent']['price'];?>
				<?php endif;?>
				<?php echo $this->params->get('params.cur_output', '')?> <?php echo @$main_units[$this->value['rent']['unit']] ?><br />
			</td>
		</tr>
		<?php endif;?>

		<?php if(isset($this->value['sale']['price']) && $this->value['sale']['price']):?>
		<tr>
			<td>
				<button type="button" class="btn btn-info btn-small" onclick="bookingAddToCart('sale',<?php echo $this->id;?>,<?php echo $record->id;?>,<?php echo $record->section_id;?>);">
				    <?php echo JText::_('CSALE');?>
				</button>
			</td>
			<td>
				<?php if(!isset($this->value['sale']['fix'])):?>
					<?php echo $this->getReadyPrice($this->value['sale']['price']);?>
				<?php else:?>
					<?php echo @$this->value['sale']['price'];?>
				<?php endif;?>

			<?php echo $this->params->get('params.cur_output', '')?> <?php echo @$main_units[$this->value['sale']['unit']] ?></td>
		</tr>
		<?php endif;?>

		<?php if(isset($this->value['order']['price']) && $this->value['order']['price']):?>
		<tr>
			<td>
				<button type="button" class="btn btn-info btn-small" onclick="bookingAddToCart('order',<?php echo $this->id;?>,<?php echo $record->id;?>,<?php echo $record->section_id;?>);">
				    <?php echo JText::_('CORDER');?>
				</button>
			</td>
			<td>
				<?php if(!isset($this->value['order']['fix'])):?>
					<?php echo $this->getReadyPrice($this->value['order']['price']);?>
				<?php else:?>
					<?php echo $this->value['order']['price'];?>
				<?php endif;?>

				<?php echo $this->params->get('params.cur_output', '')?>
				<?php echo @$main_units[$this->value['order']['unit']] ?>
			</td>
		</tr>
		<?php endif;?>
	</table>

</div>
