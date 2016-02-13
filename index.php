<?php
//ini_set('display_errors', 1); 
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SVN Overview</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/sidebar.css">
    </head>
    <body>
    	<div id="wrapper">
    	
    		<div id="sidebar-wrapper">
    			<ul class="sidebar-nav">
    				<li class="sidebar-brand" style="color: white;"><a href="">SVN Overview</a></li>
                    
                    <?php for ($i = 0 ; $i <= 19 ; $i++) {?>
    	            <li><a href="#<?php echo $i?>" onclick="sidebar(<?php echo $i;?>);">Group <?php echo $i?></a></li>
                    <?php }?>
    
                </ul>
    		</div>
    
    		<div id="page-content-wrapper">
    			<div class="well">HELLO</div>
    		</div>
    		
    		<div class="container">
    			<div id="warning" class="alert alert-danger text-center" style="display:none;"><strong>Warning!</strong><br><span></span></div>
    		</div>

    	</div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
    <script src="js/script.js"></script>
</html>