/*
Name: 			UI Elements / Widgets - Examples
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version: 	4.0.0
*/

(function($) {

	'use strict';

	$(function() {

		/*
		Flot
		*/
		if( $('#flotWidgetsSales1').get(0) ){
			var plot = $.plot('#flotWidgetsSales1', flotWidgetsSales1Data, {
				series: {
					lines: {
						show: true,
						lineWidth: 2
					},
					points: {
						show: true
					},
					shadowSize: 0
				},
				grid: {
					hoverable: true,
					clickable: true,
					borderColor: 'transparent',
					borderWidth: 1,
					labelMargin: 15,
					backgroundColor: 'transparent'
				},
				yaxis: {
					min: 0,
					color: 'transparent'
				},
				xaxis: {
					mode: 'categories',
					color: 'transparent'
				},
				legend: {
					show: false
				},
				tooltip: true,
				tooltipOpts: {
					content: '%x: %y',
					shifts: {
						x: -30,
						y: 25
					},
					defaultTheme: false
				}
			});
		}

		/*
		Morris
		*/
		if( $('#morrisLine').get(0) ){
			Morris.Line({
				resize: true,
				element: 'morrisLine',
				data: morrisLineData,
				grid: false,
				xkey: 'y',
				ykeys: ['a'],
				labels: ['Series A'],
				hideHover: 'always',
				lineColors: ['#FFF'],
				gridTextColor: 'rgba(255,255,255,0.4)'
			});
		}

		/*
		Sparkline: Bar
		*/
		if( $('#sparklineBar').get(0) ){
			$("#sparklineBar").sparkline(sparklineBarData, {
				type: 'bar',
				width: '80',
				height: '50',
				barColor: '#f3b426',
				negBarColor: '#B20000'
			});
		}

		$('.circular-bar-chart').appear();

	});

}).apply(this, [jQuery]);