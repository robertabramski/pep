<html>
	<head>
		<title><?php echo $title; ?></title>
	</head>
	<body>
		<style>
			#topbar { position: absolute; right: 0; }
			iframe { border: 0; }
			body { margin: 0; }
		</style>
		<div id="topbar">
			<a href="<?php echo site_url('admin'); ?>">Back</a>
	    	<a href="<?php echo site_url('admin/logout'); ?>">Logout</a>
		</div>
		<iframe width="100%" height="100%" src="<?php echo site_url('system/database/phpliteadmin.php'); ?>"></iframe>
	</body>
</html>