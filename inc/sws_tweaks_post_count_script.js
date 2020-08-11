jQuery(document).ready(function()

{

	if (jQuery('#sws-tweaks-tposts-0').length) {
		alert(jQuery('#sws-tweaks-tposts-0').width());
		jQuery('.sws-tweaks-tposts-img-div').height(jQuery('#sws-tweaks-tposts-0').width());
	}
}