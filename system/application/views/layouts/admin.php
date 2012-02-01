<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title; ?></title>
		<meta name="author" content="" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
	</head>
	<body>
	    <div id="content">
	    	<a href="<?php echo site_url('admin/logout'); ?>">Logout</a>
	        <h1><?php echo $title; ?></h1>
	        <h2><?php echo $lang['welcome']; ?> <?php echo $user; ?></h2>
		    <h3>Settings</h3>
		    <ul>
		    <?php foreach($settings as $setting): ?>
		    	<li><?php echo $setting['name']; ?> = <?php echo $setting['value']; ?></li>
		    <?php endforeach; ?>
		    </ul>
	    </div>
	</body>
</html>