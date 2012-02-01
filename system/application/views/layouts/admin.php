<?php partial('header'); ?>

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
	    
<?php partial('footer'); ?>