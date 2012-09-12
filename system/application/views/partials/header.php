<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title; ?></title>
		<meta name="description" content="<?php echo Pep::get_setting('site_description'); ?>" />
		<meta name="keywords" content="<?php echo Pep::get_setting('site_keywords'); ?>" />
	</head>
	<body>
		<?php if(DEV_MODE): ?><p>Dev mode is on.</p><?php endif; ?>
		