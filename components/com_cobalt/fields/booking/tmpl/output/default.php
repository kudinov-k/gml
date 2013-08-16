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
?>


<div id="booking<?php echo $record->id?>" >
	<!--  <div id="datepicker<?php echo $record->id?>"></div>
	<input type="hidden" name="date" id="date<?php echo $record->id?>" size="100"/> -->

	<table>
		<tr>
			<td>
				<button type="button" class="btn btn-info btn-small" onclick="bookingAddToCart('rent',<?php echo $this->id;?>,<?php echo $record->id;?>,<?php echo $record->section_id;?>);">
				    <?php echo JText::_('CRENT');?>
				</button>
			</td>
			<td><?php echo @$this->value['rent']?> руб.</td>
			<td>Сутки</td>
		</tr>
		<tr>
			<td>
				<button type="button" class="btn btn-info btn-small" onclick="bookingAddToCart('sale',<?php echo $this->id;?>,<?php echo $record->id;?>,<?php echo $record->section_id;?>);">
				    <?php echo JText::_('CSALE');?>
				</button>
			</td>
			<td><?php echo @$this->value['sale']?> руб.</td>
			<td>шт.</td>
		</tr>
	</table>


</div>
