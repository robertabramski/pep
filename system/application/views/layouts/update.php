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
	    	<a href="<?php echo site_url('admin'); ?>">Back</a>
	    	<a href="<?php echo site_url('admin/logout'); ?>">Logout</a>
	    	<form>
		        <h1><?php echo $title; ?></h1>
		        <p><?php echo $message; ?></p>
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
		   </form>
	    </div>
	</body>
</html>