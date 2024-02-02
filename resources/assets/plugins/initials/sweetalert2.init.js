"use strict";
// Set defaults
window.Swal = Swal.mixin({
	buttonsStyling: false,
	reverseButtons: true,
	customClass: {
		confirmButton: 'btn btn-primary',
		cancelButton: 'btn btn-secondary',
	},
	confirmButtonText: 'YES',
	cancelButtonText: 'NO',
});