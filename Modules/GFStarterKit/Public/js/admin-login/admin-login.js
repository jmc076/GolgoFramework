$(window, document, undefined).ready(function() {
	
	 $.ajaxPrefilter(function(options, originalOptions, jqXHR) {
		    if (originalOptions.type !== 'POST' || options.type !== 'POST') {
		        return;
		    }
		    postData = {};
		    postData[$('#csrf').attr('name')] = $('#csrf').val();
			options.data = typeof options.data === "string" ? $('#csrf').attr('name')+"="+ $('#csrf').val()+"&"+options.data : $.extend(options.data, postData);
		});

	  
	  $('.js-login-button').on('click',function(e) {
		  	e.preventDefault();
		  	var url = '/'+ baseHost + "/api/Users";
			$.ajax({
		        url:   url,
		        type:  'POST',
		        data: $('#js-login-form').serialize(),
		        beforeSend: function() {
		        	$('#loading').show();
		        },
		        complete: function() {
		        	$('#loading').hide();
		        },
		        success:  function (response) {
		        	console.log(response);
		        	if(response && response.result != null) {
		        		if(response.result.result === true) {
		        			window.location.href = '/'+ baseHost + "/dashboard";
		        		} else {
		        			if(response.result.error)
		        				 swal("¡Error!", response.result.error, "error");   
		        			else
		        				 swal("¡Error!", "Wrong credentials", "error");   
		        		}
		        	}
		        },
		        error: function(xhr) {
		        	$('#loading').hide();
		        	 handleAjaxError(xhr); 
		        }
			 });
	  });

	});
function handleAjaxError(xhr) {
	console.log("error!")
	  var message = "Unable to process your request, try again later.";
	  if(xhr.responseText != undefined && xhr.responseText != "") {
		  message = JSON.parse(xhr.responseText);
		  message = message.msg;
	  }
	  swal("¡Error!", message, "error");   
	
}