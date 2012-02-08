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
	        
	        <?php //TODO: Add table/model generator. ?>
	    </div>
	</body>
</html>