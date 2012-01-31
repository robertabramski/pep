<?php include('header.php'); ?>

	    <div id="content">
	        <h1><?php echo $title; ?></h1>
	        <h2><?php echo $message; ?></h2>
	        <?php if($errors): ?>
	        <ul>
	        	<?php foreach($errors as $error): $message = $error['message']; ?>
	        	<?php if($message): ?><li><?php echo $message; ?></li><?php endif; ?>
	        	<?php endforeach; ?>
	        </ul>
	        <?php endif;?>
	        <h3>Login</h3>
		    <form action="<?php echo site_url('admin/login/submit'); ?>" method="post">
		    	<label for="user">User: </label><input type="text" name="user" value="<?php echo $user; ?>" />
		    	<label for="pass">Pass: </label><input type="password" name="pass" />
		    	<input type="submit" value="Submit" />
		    </form>
	    </div>
	    
<?php include('footer.php'); ?>