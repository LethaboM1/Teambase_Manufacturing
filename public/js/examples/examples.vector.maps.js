/*
Name: 			Maps / Vector - Examples
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version: 	4.0.0
*/

(function($) {

	'use strict';

	var initMap = function( $el, options ) {
		var defaults = {
			backgroundColor: null,
			color: '#FFFFFF',
			hoverOpacity: 0.7,
			selectedColor: '#005599',
			enableZoom: true,
			borderWidth:1,
			showTooltip: true,
			values: sample_data,
			scaleColors: ['#1AA2E6', '#f3b426'],
			normalizeFunction: 'polynomial'
		};

		$el.vectorMap( $.extend( defaults, options ) );
	};

	$(function() {
		$( '[data-vector-map]' ).each(function() {
			var $this = $(this);
			initMap( $this, ($this.data( 'plugin-options' ) || {}) )
		});
	});

}).apply(this, [jQuery]);