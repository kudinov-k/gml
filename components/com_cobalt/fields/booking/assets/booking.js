var booking_rent = 0;
var booking_sale = 0;

function bookingAddToCart(type, f_id, r_id, s_id){
	jQuery.ajax({
		url: Cobalt.field_call_url,
		type: 'POST',
		//async: false,
		data:{
			field_id: f_id,
			func: "addToCart",
			record_id: r_id,
			section_id: s_id,
			type:type
		}
	}).done(function(json) {
		Cobalt.updateCart();
	});
}