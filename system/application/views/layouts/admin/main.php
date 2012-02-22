<?php include('header.php'); ?>
	    <div id="content">
	    	<?php if($result): ?>
	    	<p><?php echo $result; ?></p>
	    	<?php endif; ?>
	    	<p><?php echo $lang['welcome']; ?> <?php echo $user; ?> <a href="<?php echo site_url('admin/logout'); ?>">Logout</a></p>
	        
	        <h2>Content</h2>
	        <?php //TODO: Add paging functionality. ?>
	        <?php if($sections): foreach($sections as $section): if($section['allowed']): ?>
	        <h3>
	        	<?php echo $section['menu']; ?>
	        	<?php if($section['creatable']): ?>
	        	<a href="<?php echo site_url('admin/create/'.$section['table']); ?>">Create</a>
	        	<?php endif; ?>
	        </h3>
	        <p><?php echo $section['description']; ?></p>
	        <?php if($section['rows']): ?>
	        <table>
				<thead>
		        	<?php foreach($section['fields'] as $field): ?>
		        	<td><?php echo $field['name']; ?></td>
		        	<?php endforeach; ?>
		        </thead>
	        	<?php foreach($section['rows'] as $row): ?>
	        	<tr>
		        	<?php foreach($row as $key => $value): ?>
		        	<td><?php echo $value; ?></td>
		        	<?php endforeach; ?>
	        	</tr>
	       		<?php endforeach; ?>
	        </table>   
	        <?php endif; ?>
	        <?php endif; endforeach; endif; ?>
	        
	        <h2>Tools</h2>
	        <?php if(DEV_MODE): ?>
	        <h3>Generate</h3>
	        <?php //TODO: Add basic generators: controller, helper, language, view, theme. ?>
	        <p>Create a model class and a corresponding table in the database.</p>
	        <form action="<?php echo site_url('admin/generate/model'); ?>" method="post">
	        	<input type="hidden" name="action" value="display">
	        	<label>Name: </label><input name="name" type="text" />
	        	<label>Number of Fields: </label><input name="fields" type="text" />
	        	<input type="submit" value="Submit">
	        </form>
	        <p>Create a controller class and a corresponding view.</p>
	        <form action="<?php echo site_url('admin/generate/controller'); ?>" method="post">
	        	<input type="hidden" name="action" value="display">
	        	<label>Name: </label><input name="name" type="text" />
	        	<input type="submit" value="Submit">
	        </form>
	        <h2>Database</h2>
	        <h3>Manage</h3>
	        <p>Manage databases with phpLiteAdmin.</p>
	        <!-- <form action="<?php echo site_url('admin/database'); ?>" target="phpliteadmin" method="post">
	        	<input type="hidden" name="password" value="admin">
	        	<input type="hidden" name="remember" value="yes" checked="checked">
	        	<input type="hidden" name="proc_login" value="true">
	        	<input type="submit" name="login" value="Log In" class="btn">
	        </form> -->
	        <a href="<?php echo site_url('admin/database'); ?>">phpLiteAdmin</a>
	        <?php endif; ?>
	        <h3>Backup</h3>
	        <?php //TODO: Add database backup. ?>
	    </div>
<?php include('footer.php'); ?>