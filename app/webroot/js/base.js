function flash(type, message, title) {
	$.pnotify({
		title: title,
		text: message,
		type: type, // success , info , error, notice
		width: "40%",
		addclass: "stack-custom2",
		stack: {"dir1": "down", "dir2": "right", "push": "top"},
		delay: 2000
	});
}

function nl2br(str, is_xhtml) {
	var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
	return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

$.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner =
		'<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
		'<div class="progress progress-striped active">' +
		'<div class="progress-bar" style="width: 100%;"></div>' +
		'</div>' +
		'</div>';