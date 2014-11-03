/* ---------- IE8 list style hack (:nth-child(odd)) ---------- */
jQuery(document).ready(function($) {
	/* ---------- File Manager ---------- */
	var elf = $('.file-manager').elfinder({
		url: SITE_URL + 'files_manager/index',  // connector URL (REQUIRED)
		lang : 'en',
		height: 475,
		getFileCallback : function(file) {
			
		},
	}).elfinder('instance');
});