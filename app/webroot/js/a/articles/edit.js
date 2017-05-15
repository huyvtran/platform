$(function() {
	var converter1 = Markdown.getSanitizingConverter();
	var help = function () { alert("Phần này chưa được xây dựng"); }
	var editor1 = new Markdown.Editor(converter1, "", help);
	var converter1 = new Markdown.Converter();
	Markdown.Extra.init(converter1);

	converter1.hooks.chain("preConversion", function (text) {
		return text;
	});

	converter1.hooks.chain("postBlockGamut", function (text, rbg) {
		var matches = text.match(/\nhttps?:\/\/(?:www)?\.youtube\.com\/watch\?v=([^\s]+)(\s*$|\s*\n)/i);
		text = text.replace(/\nhttps?:\/\/(?:www)?\.youtube\.com\/watch\?v=([^\s]+)(\s*$|\s*\n)/i, function (whole, inner) {
			$.getJSON("http://gdata.youtube.com/feeds/api/videos/" + inner + "?v=2&alt=json", function(data){
				$("#" + uniqid).attr({src: data.entry.media$group.media$thumbnail[3].url});
			});			
			var uniqid = Date.now();
			return '<img id="' + uniqid + '" src="' + BASE_URL + 'img/ajax-loader-s.gif' + '" />';
		})
		return text;
	});

	var help = function () {
		 alert("Chức năng này chưa được xây dựng.");
	}

	var options = {
		helpButton: { handler: help },
		strings: { quoteexample: "whatever you're quoting, put it right here" }
	};

	var editor1 = new Markdown.Editor(converter1, "-second", options);

	editor1.run();

	$('.wmd-button span').css('opacity', 0.3);
	$('#wmd-editor textarea').click(function(){
		if ($('.wmd-button span:first').css('opacity') < 1){
			$('.wmd-button span').css('opacity', 1);
		}
	});


	editor1.hooks.set("insertImageDialog", function (callback) {
		var xhr;
		hiddenUpload = function() {
			$("#uploadImage")
				.modal('hide');
			$("#uploadImage").off('hidden');
			$("#uploadImage #fileBox").val();
			$("#uploadImage .progress").hide();
			$("#uploadImage .progress .bar").css({'width': 0});
			$("#linkBox").val("");
		}
		$("#uploadImage")
			.modal({backdrop: false})
			.modal('show')
			.one('hidden', function() {
				setTimeout(function() {
					callback(null);
					$("#uploadImage").off('submit');
					$("#uploadImage .progress").hide();
					$("#uploadImage .progress .bar").css({'width': 0})
					if (xhr)
						xhr.abort();
				}, 0);
			}).one('submit', function(e) {
				e.preventDefault();
				if ($("#uploadImage").find('.tab-content .active').is('#imageA')) {
					var file = $('#fileBox')[0].files[0];
					var response;
					formData = new FormData();
					formData.append('file', file);

					// Upload file
					xhr = $.ajax({
						type: 'POST',
						url: BASE_URL + "images/upload.json",
						data: formData,
						processData: false,
						contentType: false,
						xhr: function() {
							myXhr = $.ajaxSettings.xhr();
							if(myXhr.upload){ // check if upload exists
								myXhr.upload.addEventListener('progress',updateProgress, false);
							}
							return myXhr;
						},
						success: function(response) {
							console.log(response);
							$("#fileBox").val('');
							callback(response.message);
							hiddenUpload();
						}
					});					
				} else {
					var link = $("#linkBox").val();
					if (link == '') {
						alert('Chưa điền link vào ô');
					} else {
						callback(link);
						hiddenUpload();
					}

				}


			});

		return true; 
	});

	function updateProgress(evt) {
		if (evt.lengthComputable) {
			var percentComplete = evt.loaded / evt.total * 100;
			$("#uploadImage .progress").show().find('.bar').css('width', percentComplete + '%');
		} else {
			alert('Error happen');
		}
	}

})