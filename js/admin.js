(function ($) {
	"use strict";
	$(function () {

		$('#toplevel_page_abpo-experiment li:nth-child(4), #toplevel_page_abpo-experiment li:nth-child(5), #toplevel_page_abpo-experiment li:nth-child(6), #toplevel_page_abpo-experiment li:nth-child(7), #toplevel_page_abpo-experiment li:nth-child(8), #toplevel_page_abpo-experiment li:nth-child(9)').hide();
		
		//Variation Item Change Event
		$('.ab-is-html, .ab-hide-file').hide();
		

		//Hide URL 
		$('#goalTrigger').on('change', function(){
			var type = $(this).find('option:selected').val();

			if(type == "clickEvent")
			{
				$('#ab-urlGroup').hide();
				$( '#url' ).rules( "remove");
			}
			else
			{
				$('#ab-urlGroup').show();
				$( '#url' ).rules( "add", { required: true });
			}
		});

		$('.ab-no-url-onload').hide();

		
		//Experiment Validation
		jQuery.validator.addClassRules({
			variation:{
		    	required: true,
		    },
		    variationName:{
		    	required: true,
		    },
		    variationFile:{
		    	 required: true, 
		    	 extension:"jpg|jpeg|png|gif", 
		    	 filesize: 204800 
		    }
		});

		$('.ab-press-experimentForm').validate(
		{
			rules: {
			    name: {
			      	required: true,
			    },
			    startDate: {
			      	required: true,
			      	date: true
			    },
			    endDate: {
			      	required: true,
			      	date: true
			    },
			    goal: {
			      	required: true,
			    },
			    url: {
			      	required: true,
			    },
			   
			},
			errorPlacement: $.noop,
			highlight: function(element) { $(element).addClass('inputError');},
			success: function(element) {
			 $(element).closest('.ab-press-group').find('.inputError').removeClass('inputError');}
		 });

		//File size validation
		$.validator.addMethod('filesize', function(value, element, param) {
		    return this.optional(element) || (element.files[0].size <= param) 
		});

		//Event Dates
		$( "#startDate" ).datepicker({
	      numberOfMonths: 2,
	      minDate: '0',
	      onClose: function( selectedDate ) {
	        $( "#endDate" ).datepicker( "option", "minDate", selectedDate );
	      }
	    });
	    $( "#endDate" ).datepicker({
	      numberOfMonths: 2,
	      onClose: function( selectedDate ) {
	        $( "#startDate" ).datepicker( "option", "maxDate", selectedDate );
	      }
	    });


		

	
	});
}(jQuery));

