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
                    
                    <?php 
                    $countfiles = count(scandir("xml"))-2;
                    for ($i = 0 ; $i < $countfiles ; $i++) {?>
    	            	<li><a href="#<?php echo $i?>" onclick="sidebar(<?php echo $i;?>);">Group <?php echo $i?></a></li>
                    <?php 
                        if( $i % 4 == 0)
                        {
                            echo "<hr>";
                        }
                    }?>
    
                </ul>
    		</div>
    
    		<div id="page-content-wrapper">
    			<div class="well" id="message">HELLO</div>
    			
        		<div id="userrevcontent" style="display:none;">
            		<div class="container-fluid">
            			<div class="row">
    	        			<h1 id="groupname"></h1>
            			</div>
            			<div class="row">
        	    			<span id="lastupdatedsvn"></span>
            			</div>
            			<div class="row well">
            				<div class="row" id="authortags">
            				</div>
            				<div class="row col-md-offset-2">
            					<div id="canvas-holder">
            						<canvas id="chart-area" width="300" height="300"></canvas>
            					</div>
            				</div>
            			</div>
            			</div>
            		<div class="container-fluid">
        				<div id="revisionlist">
        				</div>
            		</div>
        		</div>
        		
        		<div class="container">
        			<div id="warning" class="alert alert-danger text-center" style="display:none;"><strong>Warning!</strong><br><span></span></div>
        		</div>
    		</div>
    	</div>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
    <script src="js/script.js"></script>
</html>