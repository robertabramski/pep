<?php include('header.php'); ?>

	    <div id="content">
	        <h1><?php echo $title; ?></h1>
	    </div>
	    <h3>Settings</h3>
	    <ul>
	    <?php foreach($settings as $setting): ?>
	    	<li><?php echo $setting['name']; ?> = <?php echo $setting['value']; ?></li>
	    <?php endforeach; ?>
	    </ul>
	    <p><?php echo $lang['welcome']; ?></p>
	    
<?php include('footer.php'); ?>