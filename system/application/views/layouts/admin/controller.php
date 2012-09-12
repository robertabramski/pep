<?php include('header.php'); ?>
		<div id="content">
			<a href="<?php echo site_url('admin'); ?>">Back</a>
	    	<a href="<?php echo site_url('admin/logout'); ?>">Logout</a>
			<h1><?php echo $title; ?></h1>
			<form action="<?php echo site_url('admin/generate/controller'); ?>" method="post">
				<input type="hidden" name="action" value="controller">
				<h4>Controller Options</h4>
				<label>Name: </label><input type="text" name="name" value="<?php echo $name; ?>" /><br />
				<label>Create View: </label><input name="genview" type="checkbox" checked="checked" /><br />
				<label>Subdirectory: </label><input name="subdir" type="text" /><br />
				<input type="submit" value="Submit">
			</form>
		</div>
<?php include('footer.php'); ?>