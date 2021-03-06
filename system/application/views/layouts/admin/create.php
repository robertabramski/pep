<?php include('header.php'); ?>
	    <div id="content">
	    	<a href="<?php echo site_url('admin'); ?>">Back</a>
	    	<a href="<?php echo site_url('admin/logout'); ?>">Logout</a>
	    	<form method="post" action="<?php echo current_url(); ?>">
		        <h1><?php echo $title; ?></h1>
		        <p><?php echo $message; ?></p>
		        <?php if($errors): ?>
		        <ul>
		        	<?php foreach($errors as $error): ?>
		        	<?php if($error['message']): ?><li><?php echo $error['message']; ?></li><?php endif; ?>
		        	<?php endforeach; ?>
		        </ul>
		        <?php endif;?>
		        <table>
		        	<thead>
			        	<?php foreach($fields as $field): ?>
			        	<td><?php echo $field['name']; ?></td>
			        	<?php endforeach; ?>
			        </thead>
		        	<tr>
					<?php foreach($row as $key => $value): ?>
						<td><?php echo $value; ?></td>
			        <?php endforeach; ?>
		        	</tr>
		        </table>
		        <input type="submit" value="Submit" />
		   </form>
	    </div>
<?php include('footer.php'); ?>