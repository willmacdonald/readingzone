/*global jQuery, enquire, document, window, ariaRemove, ariaAdd */
jQuery(function () {
	"use strict";
	var
		skip_id = document.getElementById('skip'),

		a_tag_external = document.querySelectorAll('a[rel*="external"]'),
		form_children = document.querySelectorAll('form > *'),

		Default = {
			async: {
				links: function () {
					if (a_tag_external.length) {
						Array.from(a_tag_external).forEach(function (el) {
							el.setAttribute('rel', 'external noopener');
							el.addEventListener('click', function (e) {
								e.preventDefault();
								window.open(this.attributes.href.value);
							});
						});
					}
					if (skip_id) {
						Array.from(skip_id.querySelectorAll('a')).forEach(function (el) {
							el.setAttribute('aria-hidden', true);
							el.addEventListener('focus', function () {
								this.setAttribute('aria-hidden', false);
							});
							el.addEventListener('blur', function () {
								this.setAttribute('aria-hidden', true);
							});
						});
					}
				},
				forms: function () {
					if (form_children.length) {
						Array.from(form_children).forEach(function (el, i) {
							el.style.zIndex = (form_children.length - i);
						});
					}
				},
				responsive: function () {
					var desktop_hide = Array.from(document.getElementsByClassName('desktop-hide')),
						desktop_only = Array.from(document.getElementsByClassName('desktop-only')),
						tablet_hide = Array.from(document.getElementsByClassName('tablet-hide')),
						tablet_only = Array.from(document.getElementsByClassName('tablet-only')),
						mobile_hide = Array.from(document.getElementsByClassName('mobile-hide')),
						mobile_only = Array.from(document.getElementsByClassName('mobile-only'));
					enquire.register('screen and (min-width: 1001px)', function () {
						if (desktop_only.length || tablet_hide.length || mobile_hide.length) {
							ariaRemove(desktop_only.concat(tablet_hide).concat(mobile_hide));
						}
						if (desktop_hide.length || tablet_only.length || mobile_only.length) {
							ariaAdd(desktop_hide.concat(tablet_only).concat(mobile_only));
						}
					}).register('screen and (min-width: 761px) and (max-width: 1000px)', function () {
						if (desktop_hide.length || tablet_only.length || mobile_hide.length) {
							ariaRemove(desktop_hide.concat(tablet_only).concat(mobile_hide));
						}
						if (desktop_only.length || tablet_hide.length || mobile_only.length) {
							ariaAdd(desktop_only.concat(tablet_hide).concat(mobile_only));
						}
					}).register('screen and (max-width: 760px)', function () {
						if (desktop_hide.length || tablet_hide.length || mobile_only.length) {
							ariaRemove(desktop_hide.concat(tablet_hide).concat(mobile_only));
						}
						if (desktop_only.length || tablet_only.length || mobile_hide.length) {
							ariaAdd(desktop_only.concat(tablet_only).concat(mobile_hide));
						}
						if (skip_id) {
							Array.from(skip_id.querySelectorAll('a[href="#nav"], a[href="#mobile"]')).forEach(function (el) {
								el.setAttribute('href', '#mobile');
							});
						}
					}).register('screen and (min-width: 761px)', function () {
						if (skip_id) {
							Array.from(skip_id.querySelectorAll('a[href="#nav"], a[href="#mobile"]')).forEach(function (el) {
								el.setAttribute('href', '#nav');
							});
						}
					});
				}
			}

		};

	Default.async.links();
	Default.async.forms();
	Default.async.responsive();
});

/*!*/
