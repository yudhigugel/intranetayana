let parent, element

(function ($) {
	'use strict';
	$(function () {
		if ($('#circleProgress1').length) {
			var bar = new ProgressBar.Circle(circleProgress1, {
				color: '#001737',
				// This has to be the same size as the maximum width to
				// prevent clipping
				strokeWidth: 4,
				trailWidth: 10,
				easing: 'easeInOut',
				duration: 1400,
				text: {
					autoStyleContainer: false
				},
				from: {
					color: '#464dee',
					width: 10
				},
				to: {
					color: '#464dee',
					width: 10
				},
				// Set default step function for all animate calls
				step: function (state, circle) {
					circle.path.setAttribute('stroke', state.color);
					circle.path.setAttribute('stroke-width', state.width);

					circle.setText(`<div class="text-center">17<div class="text-muted">CREDIT NOTE APPROVAL</div></div>`);
				}
			});

			bar.text.style.fontSize = '0.75em';
			bar.text.style.fontWeight = 'bold';
			bar.text.style.align = 'center';
			bar.animate(.64); // Number from 0.0 to 1.0
		}
		if ($('#circleProgress2').length) {
			var bar = new ProgressBar.Circle(circleProgress2, {
				color: '#001737',
				// This has to be the same size as the maximum width to
				// prevent clipping
				strokeWidth: 4,
				trailWidth: 10,
				easing: 'easeInOut',
				duration: 1400,
				text: {
					autoStyleContainer: false
				},
				from: {
					color: '#0ddbb9',
					width: 10
				},
				to: {
					color: '#0ddbb9',
					width: 10
				},
				// Set default step function for all animate calls
				step: function (state, circle) {
					circle.path.setAttribute('stroke', state.color);
					circle.path.setAttribute('stroke-width', state.width);

					circle.setText(`<div class="text-center">22<div class="text-muted">CASH ADVANCE APPROVAL</div></div>`);

				}
			});

			bar.text.style.fontSize = '0.75em';
			bar.text.style.fontWeight = 'bold';
			bar.text.style.align = 'center';
			bar.animate(.75); // Number from 0.0 to 1.0
		}
		if ($('#circleProgress3').length) {
			var bar = new ProgressBar.Circle(circleProgress3, {
				color: '#001737',
				// This has to be the same size as the maximum width to
				// prevent clipping
				strokeWidth: 4,
				trailWidth: 10,
				easing: 'easeInOut',
				duration: 1400,
				text: {
					autoStyleContainer: false
				},
				from: {
					color: '#ef5958',
					width: 10
				},
				to: {
					color: '#ef5958',
					width: 10
				},
				// Set default step function for all animate calls
				step: function (state, circle) {
					circle.path.setAttribute('stroke', state.color);
					circle.path.setAttribute('stroke-width', state.width);

					circle.setText(`<div class="text-center">13<div class="text-muted">LEAVE NOTICE APPROVAL</div></div>`);

				}
			});

			bar.text.style.fontSize = '0.75em';
			bar.text.style.fontWeight = 'bold';
			bar.text.style.align = 'center';
			bar.animate(.45); // Number from 0.0 to 1.0
		}

		if ($("#inline-datepicker-dashboard").length) {
			$('#inline-datepicker-dashboard').datepicker({
				enableOnReadonly: true,
				todayHighlight: true,
			});
		}

	});
})(jQuery);