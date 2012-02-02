<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title; ?></title>
		<meta name="author" content="" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
	</head>
	<body>
	    <div id="content">
	    	<a href="<?php echo site_url('admin/logout'); ?>">Logout</a>
	        <h1><?php echo $title; ?></h1>
	        <h2><?php echo $lang['welcome']; ?> <?php echo $user; ?></h2>
	        
	        <?php if($sections): foreach($sections as $section): if($section['rows']): ?>
	        <h3><?php echo $section['menu']; ?></h3>
	        <table>
		        <thead>
		        	<?php foreach(array_keys($section['fields']) as $field): ?>
		        	<td><?php echo $field; ?></td>
		        	<?php endforeach; ?>
		        </thead>
	        	<?php foreach($section['rows'] as $row): ?>
	        	<tr>
	        	<?php foreach($row as $key => $value): ?>
	        		<td><?php echo $row[$key]; ?></td>
	        	<?php endforeach; ?>
	        	</tr>
	       		<?php endforeach; ?>
	        </table>	        
	        <?php endif; endforeach; endif; ?>
	        
	    </div>
	</body>
</html>