jQuery(document).ready(function()

{

	if (jQuery('#sws-tweaks-tpost-0').length) {
		var myHeight = jQuery('#sws-tweaks-tpost-0').width();
		//alert(myHeight);
		jQuery('.sws-tweaks-tpost-img-div').height(myHeight);
	}
});