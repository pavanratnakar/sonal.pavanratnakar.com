/*
**	@desc:	PHP ajax login form using jQuery
**	@author:	programmer@chazzuka.com
**	@url:		http://www.chazzuka.com/blog
**	@date:	15 August 2008
**	@license:	Free!, but i'll be glad if i my name listed in the credits'
*/
var auto_refresh = setInterval(
	function ()
	{
	$('#footer').load('ajax/time.php').fadeIn("slow");
	}, 40000); // refresh every 40000 milliseconds
$(document).ready(function(){ 

	var wrapperId 	=	'#wrapper';		// main container
	var waitId		=	'#wait';		// wait message container
	var formId		=	'#frmLogin';	// submit button identifier
	var userId		=	'#u';			// user input identifier
	var passId		=	'#p';			// password input identifier
	var waitNote	=	'Loading...';											// loading message
	var jsErrMsg	=	'User or password is not valid';						// clientside error message
	var postFile	=	'login.post.php?jsoncallback=?';	// post handler
	var autoRedir	=	true;			// auto redirect on success
	$(waitId).hide(); $(wrapperId).hide();
	$(waitId).html(waitNote).fadeIn('fast',function()
	{
		$.getJSON(postFile, function(data)
		{
			if(data.status==true) 
			{
				// status is authorized
				if(autoRedir){ 
					$(waitId).hide().html('Redirecting...').fadeIn('fast', function(){window.location=data.url;});
				} else {
					$(waitId).fadeOut('slow', function(){ $(wrapperId).html(data.message).slideDown(); }).html();
				}
			} else 
			{
				// show form
				$(wrapperId).html(data.message).slideDown('slow',function(){
					// hide  message
					$("#footer").css('display','block');
					$(waitId).fadeOut('fast',function(){
						//*/ submit handler
						$("#frmlogin").submit( function() { 
							// loading
							$(waitId).html(waitNote).fadeIn();
							var _u = $(userId).val();	// form user
							var _p = $(passId).val();	// form id
							//@ valid user ( modify as needed )
							if(_u.length<4) 
								{
									$(waitId).html(jsErrMsg).fadeIn('fast',function(){ 
										$(userId).focus();
									});
								} 
							else
								{
									//@ valid password ( modify as needed )
									if(_p.length<4)
										{
											$(waitId).html(jsErrMsg).fadeIn('fast',function(){ 
												$(passId).focus();
											});
										}
									else
										{
												$.post(postFile, { u: _u, p: _p }, function(data) {
												if(data.status==true)
												{
													$("#footer").css('display','none');
													if(autoRedir){ 
														$(waitId).html('Redirecting...').fadeIn('fast', function()
														{
															window.location=data.url;
														});
													} else {
														$(waitId).fadeOut('slow', function(){ 
															$(wrapperId).slideUp('slow',function(){
																$(this).html(data.message).slideDown();
															}); 
														}).html();
													}
												} else 
												{
													$(waitId).html(data.message).slideDown('fast', function(){ 
														$(userId).focus(); 
													}); 
												}
											}
											,'json');
										}
								}
							return false;
						});				
						//*/
						$(userId).focus();
					}).html();
				});
			}
		 });
	});
});