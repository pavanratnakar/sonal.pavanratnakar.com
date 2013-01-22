<?php
require_once('../min/utils.php');
date_default_timezone_set('Asia/Calcutta');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Sonal | Omega Marketing | Finance System | Login</title>
		<link type="text/css" rel="stylesheet" media="screen" href="<?php echo Minify_getUri('finance_login_css') ?>"/>
	</head>
	<body>
		<div id="wait"></div>
		<div id="wrapper"></div>
		<div id="footer" style="display:none">
		<?php echo date("F j, Y, g:i a");?>
		</div>
		<script type="text/javascript" src="<?php echo Minify_getUri('finance_login_js') ?>"></script>
		<script type="text/javascript">
		///GOOGLE ANALYTICS CODE///
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-22528464-1']);
		_gaq.push(['_setDomainName', '.pavanratnakar.com']);
		_gaq.push(['_trackPageview']);
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
		///GOOGLE ANALYTICS CODE///
		</script>
	</body>
</html>
