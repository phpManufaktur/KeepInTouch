/**
  Module developed for the Open Source Content Management System Website Baker 
  (http://websitebaker.org)
  Copyright (c) 2010, Ralf Hertsch
  Contact me: ralf.hertsch@phpManufaktur.de, http://phpManufaktur.de

  This module is free software. You can redistribute it and/or modify it
  under the terms of the GNU General Public License  - version 2 or later,
  as published by the Free Software Foundation: http://www.gnu.org/licenses/gpl.html.

  This module is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  
  $Id$
  
**/

/*
 * by Petko D. Petkov; pdp (architect)
 * http://www.gnucitizen.org
 * http://www.gnucitizen.org/projects/jquery-include/
 */
jQuery.extend({
	/*
	 * included scripts
	 */
	includedScripts: {},

	/*
	 * include timer
	 */
	includeTimer: null,

	/*
	 * include
	 */
	include: function (url, onload) {
		if (jQuery.includedScripts[url] != undefined) {
			return;
		}

		jQuery.isReady = false;

		if (jQuery.readyList == null) {
			jQuery.readyList = [];
		}

		var script = document.createElement('script');

		script.type = 'text/javascript';
		script.onload = function () {
			jQuery.includedScripts[url] = true;

			if (typeof onload == 'function') {
				onload.apply(jQuery(script), arguments);
			}
		};
		script.onreadystatechange = function () {
			if (script.readyState == 'complete') {
				jQuery.includedScripts[url] = true;

				if (typeof onload == 'function') {
					onload.apply(jQuery(script), arguments);
				}
			}
		};
		script.src = url;

		jQuery.includedScripts[url] = false;
		document.getElementsByTagName('head')[0].appendChild(script);

		if (!jQuery.includeTimer) {
			jQuery.includeTimer = window.setInterval(function () {
				jQuery.ready();
			}, 10);
		}
	}
});

/*
 * replacement of jQuery.ready
 */
jQuery.extend({
	/*
	 * hijack jQuery.ready
	 */
	_ready: jQuery.ready,

	/*
	 * jQuery.ready replacement
	 */
	ready: function () {
		isReady = true;

		for (var script in jQuery.includedScripts) {
			if (jQuery.includedScripts[script] == false) {
				isReady = false;
				break;
			}
		}

		if (isReady) {
			window.clearInterval(jQuery.includeTimer);
			jQuery._ready.apply(jQuery, arguments);
		}
	}
});

//var basisURL = window.location.protocol+'//' + window.location.hostname;


$.include(WB_URL + '/modules/kit/include/jquery/ui/ui.core.js');
$.include(WB_URL + '/modules/kit/include/jquery/ui/ui.accordion.js');
$.include(WB_URL + '/modules/kit/include/jquery/ui/ui.datepicker.js');

if (isNaN(document.getElementById('language'))) { 
  lang = document.getElementById('language').value;
  if (lang != 'en') {
    $.include(WB_URL + '/modules/kit/include/jquery/ui/i18n/ui.datepicker-'+lang+'.js');
  }
}

$(document).ready(function() {
	$('#accordion').accordion({
		header: '.accordion_tab',
    autoHeight: false
	});
  if (isNaN(document.getElementById('language'))) {
    $('#datepicker').datepicker({ });
    $('#datepicker_2').datepicker({ });
    $('#datepicker_3').datepicker({ });
    $('#datepicker_4').datepicker({ });
    $('#datepicker_5').datepicker({ });
  }
});

// OpenStreetMap
//$.include('http://www.openlayers.org/api/OpenLayers.js');
//$.include('http://www.openstreetmap.org/openlayers/OpenStreetMap.js');
