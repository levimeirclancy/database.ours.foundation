<html>
<head>
<style>

body {
	font-family: Arial, Helvetica;
	font-weight: 700;
	text-align: center; }
	
#messenger-code-image {
	display: block;
	margin: 40px;
	text-align: center;
	}
	
#messenger-step-one, #messenger-step-two, #messenger-step-three {
	display: inline-block;
	width: 28%;
	margin: 20px;
	text-align: center; }
	
#messenger-step-one {

	}
	
#messenger-step-two {

	}

#messenger-step-three {
	
	}

.print-hide {
	display: block;
	margin: 30px auto;
	padding: 0 30px;
	text-align: center; }
	
@media print {
	.print-hide { display: none; }
	}
	
</style>	
</head>
<body>
	
<div class='print-hide'>Print out this flyer and put it on locations where you want users to engage with your Messenger Bot.<br><a href='https://<? echo $domain ?>/<? echo $page_temp ?>/'>Go Back</a></div>

<h1><? echo implode("  •  ", $information_array[$page_temp]['name']) ?></h1>

<? if (!(empty($page_access_token)) && file_exists("messenger/".$page_temp.".png")): ?>
	<div id='messenger-code-image'><img src='https://<? echo $domain ?>/messenger/<? echo $page_temp ?>.png' width='400px' height='400px'></div>
	
	<div id='messenger-step-one'>
	<b>Step 1</b><br>
	Open Messenger.
	</div>
	
	<div id='messenger-step-two'>
	<b>Step 2</b><br>
	Open Camera.
	</div>
	
	<div id='messenger-step-three'>
	<b>Step 3</b><br>
	Scan + Hold.
	</div>

<? elseif (!(empty($page_access_token)) && !(file_exists("messenger/".$entry_info['entry_id'].".png"))): ?>
	<div class='print-hide'>An error occurred. No messenger code.</div>

<? endif ?>
	
	<hr>

<? if (!(empty($telegram_bot))): ?>
	
show telegram icon

		<div>step 1</div>
	
	<div>step 2</div>
	
	<div>step 3</div>
	
	<? endif ?>
	
</body></html>
