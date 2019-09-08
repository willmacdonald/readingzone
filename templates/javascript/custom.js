/* -------------------------------------------

	Name:		ReadingZone
	Date:		2019/08/22
	Author:		http://psdhtml.me

---------------------------------------------  */
/*global jQuery, document, yall, setTimeout, window, history */
var i = 0,
	img_lazy = document.querySelectorAll('img[data-src]:not(.dont)');
for (i = 0; i < img_lazy.length; i = i + 1) {
	img_lazy[i].classList.add('lazy');
}
document.addEventListener('DOMContentLoaded', function () {
	'use strict';
	yall({
		observeChanges: true,
		threshold: 500
	});
});
jQuery(function () {
	"use strict";
	var
		$ = jQuery.noConflict(),
		html_tag = document.documentElement,

		footer_id = document.getElementById('footer'),
		footer_date = footer_id ? footer_id.querySelectorAll('.date') : [],
		nav_id = $(document.getElementById('nav')),
		top_id = $(document.getElementById('top')),

		accordion_class = $(document.querySelectorAll('[class*="accordion"]')),
		email_tag = document.getElementsByClassName('email'),
		heading_title = $(document.getElementsByClassName('heading-title')),
		list_aside = $(document.getElementsByClassName('list-aside')),
		list_gallery = $(document.getElementsByClassName('list-gallery')),
		list_slider = $(document.getElementsByClassName('list-slider')),
		module_wide = $(document.getElementsByClassName('module-wide')),
		tabs_class = $('[class*="tabs"]:not(.tabs-inner, .tabs-header)'),

		Default = {
			utils: {
				mails: function () {
					if (email_tag.length) {
						Array.from(email_tag).filter(function (el) {
							return el.tagName !== 'INPUT' && el.tagName !== 'DIV';
						}).forEach(function (el) {
							el.innerText = el.innerText.replace('//', '@').replace(/\//g, '.');
							if (el.tagName === 'A') {
								el.setAttribute('href', 'mailto:' + el.innerText);
							}
						});
					}
				},
				forms: function () {
					if (nav_id.length) {
						nav_id.children('form').find('label:not(.hidden) + :input:not(select, :button)').each(function () {
							$(this).attr('placeholder', $(this).siblings('label').text()).siblings('label').addClass('hidden').attr('aria-hidden', true);
						});
					}
				},
				top: function () {
					top_id.append('<a href="./" class="menu" role="button" aria-controls="mobile" aria-expanded="false" data-target="#mobile"></a>').after('<nav id="mobile" aria-expanded="false" focusable="false" aria-hidden="true"></nav>');
					var mobile_id = $(document.getElementById('mobile'));
					nav_id.children('ul:first').clone().appendTo(mobile_id);
					mobile_id.append('<a href="#mobile" class="close" role="button">Close</a>').find('a').attr('tabindex', -1).removeAttr('accesskey');
					top_id.children('.menu').add(mobile_id.find('.close')).attr('href', '#mobile').on('click', function () {
						if ($(html_tag).is('.menu-active')) {
							$(html_tag).removeClass('menu-active');
							mobile_id.attr({
								'aria-hidden': true,
								'focusable': false
							}).find('a').attr('tabindex', -1);
							top_id.children('.menu').attr('aria-expanded', false);
							history.replaceState(null, null, ' ');
							return false;
						} else {
							$(html_tag).addClass('menu-active');
							mobile_id.attr({
								'aria-hidden': false,
								'focusable': true
							}).find('a').removeAttr('tabindex');
							top_id.children('.menu').attr('aria-expanded', true);
							document.location.hash = $(this).attr('href').split('#')[1];
						}
					});
				},
				date: function () {
					if (footer_date.length) {
						footer_date[0].innerText = (new Date()).getFullYear();
					}
				},
				mobile: function () {
					if (jQuery.browser.mobile) {
						html_tag.classList.add('mobile');
					} else {
						html_tag.classList.add('no-mobile');
					}
				},
				done: function () {
					var tag = document.createElement('script');
					tag.src = "javascript/scripts-async.js";
					document.body.appendChild(tag);
				},
				maps: function () {
					$(document.getElementsByClassName('uk-map')).html('<div class="inner"></div>').children('.inner').vectorMap({
						map: 'uk_regions_mill',
						zoomOnScroll: false,
						zoomButtons: false,
						backgroundColor: 'rgba(0,0,0,0)',
						hoverRegion: false,
						regionStyle: {
							initial: {
								'fill-opacity': 1,
								'stroke': '#fff',
								'stroke-width': 5,
								'stroke-opacity': 1
							},
							hover: {
								"fill-opacity": 1
							}
						},
						series: {
							regions: [{
								values: {
									'SCT': '#00a556', // Scotland
									'UKD': '#00a2d0', // North West
									'UKC': '#2153a4', // North East
									'UKE': '#ef9443', // Yorkshire and the Humber
									'UKF': '#00a556', // East Midlands
									'UKH': '#ff5b5b', // East 
									'WLS': '#ff5b5b', // Wales
									'UKG': '#0051a7', // West Midlands
									'UKK': '#02a3d1', // South West
									'UKJ': '#ffad40', // South Eeast
									'UKI': '#1f52a2', // London
									'NIR': '#f2af56' // Northern Ireland
								},
								attribute: 'fill'
							}]
						}
					});
				},
				owl: function () {
					if (list_gallery.filter('.a').length) {
						list_gallery.filter('.a').each(function () {
							if ($(this).children().length > 3) {
								$(this).addClass('mobile-hide').clone().removeClass('mobile-hide').addClass('mobile-only').insertAfter($(this));
								$(this).next('.list-gallery.mobile-only').each(function () {
									$(this).owlLayout().children('.inner').owlCarousel({
										loop: true,
										nav: false,
										dots: true,
										autoHeight: true,
										lazyLoad: true,
										margin: 10,
										items: 3,
										onInitialized: function () {
											$(this.$element).owlSemantic();
										},
										onTranslated: function () {
											$(this.$element).owlSemantic();
										}
									});
								});
							}
						});
					}
					if (list_slider.length) {
						list_slider.each(function () {
							if ($(this).filter(':not([data-count])').children().length > 4) {
								$(this).owlLayout().children('.inner').owlCarousel({
									loop: true,
									nav: true,
									dots: true,
									autoHeight: true,
									lazyLoad: true,
									margin: 60,
									items: 4,
									onInitialized: function () {
										$(this.$element).owlSemantic();
									},
									onTranslated: function () {
										$(this.$element).owlSemantic();
									},
									responsive: {
										0: {
											items: 2,
											margin: 20
										},
										400: {
											items: 3,
											margin: 28
										},
										1000: {
											items: 4,
											margin: 20
										},
										1200: {
											items: 4,
											margin: 40
										},
										1330: {
											items: 4,
											margin: 60
										}
									}
								});
							}
							if ($(this).filter('[data-count="5"]').children().length > 5) {
								$(this).owlLayout().children('.inner').owlCarousel({
									loop: true,
									nav: true,
									dots: true,
									autoHeight: true,
									lazyLoad: true,
									margin: 45,
									items: 5,
									onInitialized: function () {
										$(this.$element).owlSemantic();
									},
									onTranslated: function () {
										$(this.$element).owlSemantic();
									},
									responsive: {
										0: {
											items: 2,
											margin: 20
										},
										400: {
											items: 3,
											margin: 28
										},
										1000: {
											items: 5,
											margin: 20
										},
										1200: {
											items: 5,
											margin: 30
										},
										1330: {
											items: 5,
											margin: 45
										}
									}
								});
							}
							if ($(this).filter('[data-count="3"]').children().length > 3) {
								$(this).owlLayout().children('.inner').owlCarousel({
									loop: true,
									nav: true,
									dots: true,
									autoHeight: true,
									lazyLoad: true,
									margin: 95,
									items: 3,
									onInitialized: function () {
										$(this.$element).owlSemantic();
									},
									onTranslated: function () {
										$(this.$element).owlSemantic();
									},
									responsive: {
										0: {
											items: 3,
											margin: 20
										},
										760: {
											items: 3,
											margin: 28
										},
										1000: {
											items: 3,
											margin: 40
										},
										1200: {
											items: 3,
											margin: 65
										},
										1330: {
											items: 3,
											margin: 95
										}
									}
								});
							}
						});
					}
					if (list_aside.filter('.slider').length) {
						list_aside.filter('.slider').each(function () {
							if ($(this).filter(':not([data-count])').children().length > 3) {
								$(this).owlLayout().children('.inner').owlCarousel({
									loop: true,
									nav: true,
									dots: true,
									autoHeight: true,
									lazyLoad: true,
									margin: 25,
									items: 3,
									onInitialized: function () {
										$(this.$element).owlSemantic();
									},
									onTranslated: function () {
										$(this.$element).owlSemantic();
									},
									responsive: {
										0: {
											items: 1
										},
										1000: {
											items: 2
										},
										1200: {
											items: 3
										}
									}
								});
							}
							if ($(this).filter('[data-count="2"]').children().length > 2) {
								$(this).owlLayout().children('.inner').owlCarousel({
									loop: true,
									nav: true,
									dots: true,
									autoHeight: true,
									lazyLoad: true,
									margin: 25,
									items: 2,
									onInitialized: function () {
										$(this.$element).owlSemantic();
									},
									onTranslated: function () {
										$(this.$element).owlSemantic();
									},
									responsive: {
										0: {
											items: 1
										},
										1200: {
											items: 2
										}
									}
								});
							}
						});
					}
				},
				miscellaneous: function () {
					if (heading_title.filter(':has(.list-social)').length) {
						heading_title.filter(':has(.list-social)').each(function () {
							$(this).css('padding-right', $(this).find('.list-social').outerWidth());
						});
						$(window).on('resize', function () {
							heading_title.filter(':has(.list-social)').each(function () {
								$(this).css('padding-right', $(this).find('.list-social').outerWidth());
							});
						});
					}
					if (accordion_class.length) {
						accordion_class.semanticAccordion();
					}
					if (tabs_class.length) {
						tabs_class.semanticTabs();
					}
					if (module_wide.filter('.theme-carrot:has(.list-gallery.box):has(.offset-left)').length) {
						module_wide.filter('.theme-carrot:has(.list-gallery.box):has(.offset-left)').each(function () {
							$(this).find('.offset-left').clone().appendTo($(this).find('.list-gallery.box'));
							$(this).find('.offset-left').clone().removeClass('offset-left').addClass('offset-right cloned').appendTo($(this).find('.list-gallery.box'));
						});
					}
					if (list_aside.filter('.box[data-background]').length) {
						list_aside.filter('.box[data-background]').each(function () {
							$(this).prepend('<div class="bg"><img alt="" src="' + $(this).attr('data-background') + '"></div>');
						});
					}
				}
			}

		};
	setTimeout(function () {
		Default.utils.mails();
		Default.utils.forms();
		Default.utils.date();
		Default.utils.top();
		Default.utils.owl();
		Default.utils.miscellaneous();
		Default.utils.mobile();
		Default.utils.maps();
		Default.utils.done();
	}, 0);
});

/*!*/
