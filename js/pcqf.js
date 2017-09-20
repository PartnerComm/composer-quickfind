var qf_dev = (function($, w) {
	
	function toggleDebug() {
		var d = false,
			b = false,
			code;
		$(document).keyup(function(e) {
			code = e.keyCode || e.which;
			// if we pressed the d key
			if (code === 68) {
				d  = true;;
			}
		}).keydown(function(e) {
			code = e.keyCode || e.which;
			if (code === 66 && d === true) {
				// toggle the debug
				// only for non-ie, logged in users
				$('.no-ie .logged-in').find('.debug').toggle();
			}
		});
			
	}

	function toggleYearContentCharts() {
		if ( $('.year-toggle-button').length ) {
			var $headerCol = $('.table_rendered').find('tbody tr th');
			var thWidth = $headerCol.outerWidth();
			$headerCol.css('width', thWidth);

			var handler = function(e) {
				e.preventDefault();
				var activeyear = $('.year-toggle').data('activeyear');
				var inactiveyear = $('.year-toggle').data('inactiveyear');

				if ( $(e.currentTarget).hasClass('active') ) {
					return false;
				}

				else {
					$('.year-toggle-button').toggleClass('active');
					$('.fa-toggle-on').toggleClass('fa-rotate-180');
					$('span[class^="d-"]').hide();
					$('span.d-' + inactiveyear).show();
					$('.year-toggle').data('activeyear', inactiveyear);
					$('.year-toggle').data('inactiveyear', activeyear);
				}
			};

			$('.year-toggle-button').on('click', function(e) {
				handler(e);
			});

			$('.fa-toggle-on').on('click', function(e) {
				handler(e);
			});
		}
	}

	return {
		init: function() {
			toggleDebug();
			toggleYearContentCharts();
		}
	};

})(jQuery, window, undefined);

qf_dev.init();