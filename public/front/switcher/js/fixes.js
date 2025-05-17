
$(document).ready(function() {	
		
	

	 
	//Force active link on home pages




    //reload page on option changer
	
		(function($) {
			$(document).ready( function() {
				$('#nav-options').change(function() {
					location.reload();
					if (window.location.href.indexOf('?') > -1) {
					window.location.href = window.location.pathname;
				}
								
				});
			});
		})(jQuery);
		
		
				
			


}); // end document ready
		
				
	
	// Cookie to remember what options are selected
	$(function() {
		var navValue = localStorage.getItem("navValue");
		if (navValue != null) {
			$("#nav-options").val(navValue);
		}

		$("#nav-options").on("change", function() {
			localStorage.setItem("navValue", $(this).val());
		});
		
	//Force boxed if page has boxed string
		 if (window.location.href.indexOf("?boxed") > -1)  {
				$("#nav-options").val("boxed").change();	
			};
			
			//Force full if page has full string
		 if (window.location.href.indexOf("?full") > -1)  {
				$("#nav-options").val("full").change();	
			};
		})
		
		
	// selected option will show on page load
	$(document).ready(function() {
		if ($('#nav-options').val() == "boxed") {
			$('head').append('<link rel="stylesheet" type="text/css" href="switcher/css/boxed.css">');
		}
		  else {
		  $('head').append('');
		 
		}
		});




			


