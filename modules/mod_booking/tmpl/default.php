<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_stats
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$doc = JFactory::getDocument();

$doc->addScriptDeclaration("
!(function($)
{
	Cobalt.updateCart = function(){
		if($('#booking_cart').length)
		{
			$.ajax({
				url: Cobalt.field_call_url,
				type: 'POST',
				dataType: 'json',
				data:{
					field_id: ".$params->get('booking_id').",
					func: 'getCart',
					mod_params: '".$params->toString()."'
				}
			}).done(function(json) {
				$('#booking_cart').html(json.result);
				$( '#order_cart' ).button().click(function() {
					//$( '#dialog-form' ).dialog( 'open' );
				});

				$('#order_cart').removeClass();
				$('#order_cart').addClass('btn btn-small btn-success');
			});
		}
	};

	Cobalt.removeFromCart = function(id){
		if($('#booking_cart').length)
		{
			$.ajax({
				url: Cobalt.field_call_url,
				type: 'POST',
				dataType: 'json',
				data:{
					field_id: ".$params->get('booking_id').",
					func: 'removeFromCart',
					index: id
				}
			}).done(function(json) {
				Cobalt.updateCart();
			});
		}
	};

	Cobalt.orderCart = function(allFields){
		if($('#booking_cart').length)
		{
			$.ajax({
				url: Cobalt.field_call_url,
				type: 'POST',
				dataType: 'json',
				data:{
					field_id: ".$params->get('booking_id').",
					func: 'orderCart',
					allFields:allFields
				}
			}).done(function(json) {
				alert('".JText::_('ORDER_ACCEPT')."');
				window.location.reload();
			});
		}
	};

	Cobalt.recalc = function(rid, price){
		price = price.replace(',', '');
		var amount = parseInt($('#amount'+rid).val());
		var new_price = amount * parseFloat(price);
		$('#sum'+rid).html(new_price.toFixed(2));
		Cobalt.recalcAll();
	};

	Cobalt.recalcAll = function(){
		var sums = $('span[id^=\'sum\']');
		var total = 0;
		var total_fix = 0;
		sums.each(function(){
			console.log($(this));
			if($(this).attr('rel') == '1')
			{
				total_fix += parseFloat($(this).html());
				return;
			}
			total += parseFloat($(this).html());
		});

		if(day_diff && day_diff > 0)
		{
			total *= day_diff;
		}

		total += total_fix;

		$('#total_summary').val(total.toFixed(2));
	};

})( jQuery );
");
?>
<form method="post" id="orderForm" action="<?php echo JRoute::_('index.php?option=com_cobalt&task=ajax.field_call&field_id='.$params->get('booking_id').'&func=orderCart')?>">
	<div id="booking_cart" class="stats-module<?php echo $moduleclass_sfx; ?>">

	</div>

	<div id="dialog-form" title="Create new user" class="collapse">
		 <p class="validateTips">All form fields are required.</p>
			<fieldset>
				<label for="name">Name</label>
				<input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" />
				<label for="email">Email</label>
				<input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all" />
				<label for="telephone">Telephone</label>
				<input type="text" name="telephone" id="telephone" value="" class="text ui-widget-content ui-corner-all" />
			</fieldset>
	</div>
	<button id="order_cart" type="button" class="btn btn-small btn-success"><?php echo JText::_('ORDER');?></button>
	<input type="hidden" name="mod_params" value="<?php echo htmlspecialchars($params->toString());?>" />
	<input type="hidden" name="return" value="<?php echo base64_encode(JFactory::getURI()->toString());?>" />
</form>


 <script>
!(function($) {
	var name = $( "#name" ),
	email = $( "#email" ),
	telephone = $( "#telephone" ),
	allFields = $( [] ).add( name ).add( email ).add( telephone ),
	tips = $( ".validateTips" );
	function updateTips( t ) {
		tips.text( t ).addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
			allFields.removeClass( "ui-state-error" );
		}, 500 );
	}
	function checkLength( o, n, min, max ) {
		if ( o.val().length > max || o.val().length < min ) {
			o.addClass( "ui-state-error" );
			updateTips( "Length of " + n + " must be between " +
			min + " and " + max + "." );
			return false;
		} else {
			return true;
		}
	}

	function checkRegexp( o, regexp, n ) {
		if ( !( regexp.test( o.val() ) ) ) {
			o.addClass( "ui-state-error" );
			updateTips( n );
			return false;
		} else {
			return true;
		}
	}


	$('#order_cart').click(function() {
		if(!jQuery('#dialog-form').hasClass('in'))
		{
			jQuery('#dialog-form').collapse();
			return;
		}
		var bValid = true;

		bValid = bValid && checkLength( name, "username", 3, 16 );
		bValid = bValid && checkLength( email, "email", 6, 80 );
		bValid = bValid && checkLength( telephone, "telephone", 5, 16 );
		//bValid = bValid && checkRegexp( name, /^[a-z]([0-9a-zA-ZА-Яа-я_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
		// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
		bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );
		bValid = bValid && checkRegexp( telephone, /^([0-9])+$/, "Telephone field only allow : 0-9" );
		if ( bValid ) {
			$('#orderForm').submit();
		}
	});

})(jQuery);
</script>

<script type="text/javascript">
jQuery(document).ready(function() {
	Cobalt.updateCart();
});
</script>
