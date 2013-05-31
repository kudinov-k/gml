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
$doc->addStyleSheet(JUri::root(true).'/components/com_cobalt/fields/booking/datepicker/css/redmond/jquery-ui-1.10.3.custom.css');
$doc->addStyleSheet(JUri::root(true).'/components/com_cobalt/fields/booking/datepicker/css/mdp.css');
$doc->addScript(JUri::root(true).'/components/com_cobalt/fields/booking/datepicker/js/jquery-ui-1.10.3.custom.js');
$doc->addScript(JUri::root(true).'/components/com_cobalt/fields/booking/datepicker/js/jquery-ui.multidatespicker.js');
?>

<button type="button" class="btn btn-warning btn-small" data-toggle="collapse" data-target="#booking<?php echo $record->id?>">
    <?php echo JText::_('BOOKING');?>
</button>
<br />
<br />
<div id="booking<?php echo $record->id?>" class="collapse">
	<div id="datepicker<?php echo $record->id?>"></div>
	<input type="hidden" name="date" id="date<?php echo $record->id?>" size="100"/>
	<br />
	<button type="button" class="btn btn-info btn-small" onclick="bookingAddToCart<?php echo $record->id?>();">
	    <?php echo JText::_('ADD_TO_CART');?>
	</button>
</div>

<script type="text/javascript">
	var booking_amount_<?php echo $this->id;?> = <?php echo $this->value;?>;

	(function($) {
		$.datepicker.setDefaults( $.datepicker.regional[ "ru" ] );

		var today = new Date();
		$('#datepicker<?php echo $record->id?>').multiDatesPicker({
			//addDates: [$.datepicker.formatDate('yy-mm-dd', new Date())],
			<?php if (count($this->disable_dates)):?>
			addDisabledDates:[<?php foreach ($this->disable_dates as $date) echo "new Date('$date'),"; ?>],
			<?php endif;?>
			numberOfMonths: 2,
			showCurrentAtPos: 0,
			altField: '#date<?php echo $record->id?>',
			minDate: 0,
			firstDay: 1
		});

	})( jQuery );

	function bookingAddToCart<?php echo $record->id?>(){
		jQuery.ajax({
			url: Cobalt.field_call_url,
			type: 'POST',
			data:{
				field_id: <?php echo $this->id;?>,
				func: "addToCart",
				record_id: <?php echo $record->id;?>,
				section_id: <?php echo $section->id;?>,
				dates: jQuery('#date<?php echo $record->id?>').val()
			}
		}).done(function(json) {
			Cobalt.updateCart();
		});
	}

</script>

