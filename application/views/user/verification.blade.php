<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>Rapport fra FMV Admin</title>
	<style type="text/css">
		#outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
		body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 14px;}
		/* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
		.ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
		.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing.  More on that: http://www.emailonacid.com/forum/viewthread/43/ */
		#backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
		img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
		a img {border:none;}
		.image_fix {display:block;}
		p {margin: 1em 0;}
		h1, h2, h3, h4, h5, h6 {color: black !important;}
		h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: blue !important;}
		h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {
			color: red !important; /* Preferably not the same color as the normal header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
		 }

		h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
			color: purple !important; /* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */
		}
		table td {border-collapse: collapse;}

    	table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
		a {color: orange;}
		@media only screen and (max-device-width: 480px) {

			/* A nice and clean way to target phone numbers you want clickable and avoid a mobile phone from linking other numbers that look like, but are not phone numbers.  Use these two blocks of code to "unstyle" any numbers that may be linked.  The second block gives you a class to apply with a span tag to the numbers you would like linked and styled.

			Inspired by Campaign Monitor's article on using phone numbers in email: http://www.campaignmonitor.com/blog/post/3571/using-phone-numbers-in-html-email/.

			Step 1 (Step 2: line 224)
			*/
			a[href^="tel"], a[href^="sms"] {
						text-decoration: none;
						color: black; /* or whatever your want */
						pointer-events: none;
						cursor: default;
					}

			.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
						text-decoration: default;
						color: orange !important; /* or whatever your want */
						pointer-events: auto;
						cursor: default;
					}
		}

		/* More Specific Targeting */

		@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
			/* You guessed it, ipad (tablets, smaller screens, etc) */

			/* Step 1a: Repeating for the iPad */
			a[href^="tel"], a[href^="sms"] {
						text-decoration: none;
						color: blue; /* or whatever your want */
						pointer-events: none;
						cursor: default;
					}

			.mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
						text-decoration: default;
						color: orange !important;
						pointer-events: auto;
						cursor: default;
					}
		}

		@media only screen and (-webkit-min-device-pixel-ratio: 2) {
			/* Put your iPhone 4g styles in here */
		}

		/* Following Android targeting from:
		http://developer.android.com/guide/webapps/targeting.html
		http://pugetworks.com/2011/04/css-media-queries-for-targeting-different-mobile-devices/  */
		@media only screen and (-webkit-device-pixel-ratio:.75){
			/* Put CSS for low density (ldpi) Android layouts in here */
		}
		@media only screen and (-webkit-device-pixel-ratio:1){
			/* Put CSS for medium density (mdpi) Android layouts in here */
		}
		@media only screen and (-webkit-device-pixel-ratio:1.5){
			/* Put CSS for high density (hdpi) Android layouts in here */
		}
		/* end Android targeting */
	</style>

	<!-- Targeting Windows Mobile -->
	<!--[if IEMobile 7]>
	<style type="text/css">

	</style>
	<![endif]-->

	<!-- ***********************************************
	****************************************************
	END MOBILE TARGETING
	****************************************************
	************************************************ -->

	<!--[if gte mso 9]>
	<style>
		/* Target Outlook 2007 and 2010 */
	</style>
	<![endif]-->
</head>
<body>
	<!-- Wrapper/Container Table: Use a wrapper table to control the width and the background color consistently of your email. Use this approach instead of setting attributes on the body tag. -->
	<table cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
	<tr>
		<td>

		<h1>Heisann!</h1>
		<p>
			Ditt brukernavn er: {{ $user->username }}<br />
			… og ditt passord er: {{ $password }}
		</p>
		<p>
			En ny konto er opprettet, eller e-posten er byttet. Derfor må du nå verifisere kontoen.
		</p>
		<p>
			<h3><a href="{{ url('verification/'.$user->salt) }}">Klikk for å verifisere kontoen</a></h3>
			<small>Fungerer ikke lenken? Prøv å skriv inn denne i nettleseren din {{ url('verification/'.$user->salt) }}</small>
		<p>
			Ikke glem at du alltid kan ringe 95 30 80 87 / 46 46 45 27, om du lurer på noe!
		</p>

		</td>
	</tr>
	</table>
	<!-- End of wrapper table -->
</body>
</html>
