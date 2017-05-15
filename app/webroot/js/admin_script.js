$(function(){

	$("select:not([multiple], .notchosen)").chosen({disable_search_threshold: 10});

	function updateQueryString(key, value, url) {
	    if (!url) url = window.location.href;
	    var re = new RegExp("([?|&])" + key + "=.*?(&|#|$)(.*)", "gi");

	    if (re.test(url)) {
	        if (typeof value !== 'undefined' && value !== null)
	            return url.replace(re, '$1' + key + "=" + value + '$2$3');
	        else {
	            return url.replace(re, '$1$3').replace(/(&|\?)$/, '');
	        }
	    }
	    else {
	        if (typeof value !== 'undefined' && value !== null) {
	            var separator = url.indexOf('?') !== -1 ? '&' : '?',
	                hash = url.split('#');
	            url = hash[0] + separator + key + '=' + value;
	            if (hash[1]) url += '#' + hash[1];
	            return url;
	        }
	        else
	            return url;
	    }
	}

	// Use for select
	$.fn.inputQuery = function() {
		$(this).change(function() {
            location.href = updateQueryString($(this).data('name'), $(this).val());
        })
	}

	
		$(document).on({
	    	ajaxStart: function() {
	    		if ($('body').data('disableNProgress') == undefined || $('body').data('disableNProgress') == false) {
	    			NProgress.start();
	    		}
	    	},
		    ajaxStop: function() {
		    	if ($('body').data('disableNProgress') == undefined || $('body').data('disableNProgress') == false) {
		    		NProgress.done();
		    	}
		    }
		});
})