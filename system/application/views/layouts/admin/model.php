<?php include('header.php'); ?>
		<div id="content">
			<a href="<?php echo site_url('admin'); ?>">Back</a>
	    	<a href="<?php echo site_url('admin/logout'); ?>">Logout</a>
			<h1><?php echo $title; ?></h1>
			<form action="<?php echo site_url('admin/generate/model'); ?>" method="post">
				<input type="hidden" name="action" value="model">
				<input type="hidden" name="fields" value="<?php echo $fields; ?>">
				<input type="hidden" name="name" value="<?php echo $name; ?>" />
				<h4>Model Options</h4>
				<label>Description: </label><textarea name="description"></textarea><br />
				<label>Creatable: </label><input name="creatable" type="checkbox" checked="checked" />
				<label>Updateable: </label><input name="updateable" type="checkbox" checked="checked" />
				<label>Deletable: </label><input name="deletable" type="checkbox" checked="checked" /><br />
				<label>Allow: </label>
				<label>Admin </label><input name="allow[]" type="checkbox" checked="checked" value="admin" />
				<label>User </label><input name="allow[]" type="checkbox" value="user" /><br />
				<h4>Table Options</h4>
				<table>
					<thead>
						<tr>
							<td>Field</td>
							<td>Type</td>
							<td>Primary Key</td>
							<td>Not NULL</td>
							<td>Default Value</td>
						</tr>
					</thead>
				<?php for($i = 0; $i < $fields; $i++): $id = $i + 1; ?>
					<tr>
						<td><input name="field<?php echo $id; ?>" type="text" /></td>
						<td>
							<select name="type<?php echo $id; ?>">
								<option>INTEGER</option>
								<option>REAL</option>
								<option>TEXT</option>
								<option>BLOB</option>
							</select>
						</td>
						<td>
							<input name="pk<?php echo $id; ?>" type="hidden" value="off" />
							<input name="pk<?php echo $id; ?>" type="checkbox" />
						</td>
						<td>
							<input name="notnull<?php echo $id; ?>" type="hidden" value="off" />
							<input name="notnull<?php echo $id; ?>" type="checkbox" />
						</td>
						<td>
							<input name="default<?php echo $id; ?>" type="input" />
						</td>
					</tr>
				<?php endfor; ?>
				</table>
				<input type="submit" value="Submit">
			</form>
		</div>
<?php include('footer.php'); ?>