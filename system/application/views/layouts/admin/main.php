<?php include('header.php'); ?>
	    <div id="content">
	    	<?php if($result): ?>
	    	<p><?php echo $result; ?></p>
	    	<?php endif; ?>
	    	<a href="<?php echo site_url('admin/logout'); ?>">Logout</a>
	        <h1><?php echo $title; ?></h1>
	        <h2><?php echo $lang['welcome']; ?> <?php echo $user; ?></h2>
	        
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
	        
	        <?php if(DEV_MODE && $is_admin): ?>
	        <h3>Dev Tools</h3>
	        <p>Generate a model class and a corresponding table in the database.</p>
	        <form action="<?php echo site_url('admin/generate/model'); ?>" method="post">
	        	<input type="hidden" name="action" value="display">
	        	<label>Name: </label><input name="name" type="text" />
	        	<label>Number of Fields: </label><input name="fields" type="text" />
	        	<input type="submit" value="Submit">
	        </form>
	        <p>Manage databases with phpLiteAdmin. Password is admin.</p>
	        <a href="<?php echo site_url('admin/database'); ?>">phpLiteAdmin</a>
	        <?php endif; ?>
	        
	        <?php //TODO: Add basic generators: controller, helper, language, view, theme. ?>
	        <?php //TODO: Add database backup. ?>
	    </div>
<?php include('footer.php'); ?>