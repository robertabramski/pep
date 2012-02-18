<?php include('header.php'); ?>
		<div id="content">
			<h1><?php echo $title; ?></h1>
			<form action="<?php echo site_url('admin/generate/model'); ?>" method="post">
				<label>Model Name: </label><input name="name" type="text" /><br />
				<label>Creatable: </label><input name="creatable" type="checkbox" checked="checked" />
				<label>Updateable: </label><input name="updateable" type="checkbox" checked="checked" />
				<label>Deletable: </label><input name="deletable" type="checkbox" checked="checked" /><br />
				<label>Description: </label><textarea name="description"></textarea><br />
				<label>Allow: </label>
				<label>Admin </label><input name="allow[]" type="checkbox" checked="checked" value="admin" />
				<label>User </label><input name="allow[]" type="checkbox" value="user" /><br />
				<input type="submit" value="Submit">
			</form>
		</div>
<?php include('footer.php'); ?>