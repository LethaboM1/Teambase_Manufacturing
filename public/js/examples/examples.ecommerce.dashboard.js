/*
Name: 			eCommerce Dashboard - Examples
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version: 	4.0.0
*/

(function($) {

	'use strict';

	/*
	* Revenue Chart
	*/
	if( $('#revenueChart').get(0) ) {
		Morris.Bar({
			resize: true,
			element: 'revenueChart',
			data: revenueChartData,
			xkey: 'y',
			ykeys: ['a', 'b'],
			labels: ['Series A', 'Series B'],
			barColors: ['#f3b426', '#2baab1'],
			fillOpacity: 0.7,
			smooth: false,
			stacked: true,
			hideHover: true,
			grid: false
		});
	}

}).apply(this, [jQuery]);