<!DOCTYPE html>
<html>
    
<head>
	<title>Zip Code Locator</title>
	<link rel="stylesheet" href="HW6.css">
	<script src="https://maps.googleapis.com/maps/api/js?v=3"></script>
</head>
    
<body onload="onStart()">
	 <div class="topBanner">   
        <p> THE COM 214 ZIP CODE LOCATOR </p>     
		<form class ="form1" action="HW6.php" method="get">
			<input class="button" type="submit" name="button" value= " Create DB " />
			<input class="button" type="submit" name="button" value= " Drop DB " />
		</form>
		
		<?php
			$db_conn = mysql_connect("localhost", "root", "");
			
			if( isset($_GET["button"]) ){
                                    
	            if( $_GET["button"] == " Create DB "){
	            	
	            	if(!mysql_select_db('ZipcodeDatabase')) {
	                	
	                	if (!$db_conn)
	                    	die("Unable to connect: " . mysql_error()); 
	                	
	                	mysql_query("CREATE DATABASE ZipcodeDatabase;", $db_conn);
	                    mysql_select_db("ZipcodeDatabase", $db_conn);
	                    $cmd = "CREATE TABLE clist ( 
	                    	Zipcode int(5) NOT NULL PRIMARY KEY,
							City varchar(25),
							State varchar(2),
							Latitude float(7,4),
							Longitude float(7,4),
							gap int(1));";
	                    mysql_query($cmd);
	                    $cmd = "LOAD DATA LOCAL INFILE 'zip_codes_usa.csv' INTO TABLE clist FIELDS TERMINATED BY ',';";                    
                        mysql_query($cmd);                  
                        echo " <p id='DBCreated'> Succesfully created database! </p> ";
	                } 

	                else {
	                	echo " <p id='DBExists'> Database already exists </p> ";
	                }
	            }

	            if( $_GET["button"] == " Drop DB "){
	            	
	            	if (!$db_conn)
						die("Unable to connect: " . mysqli_connect_error());  

					$retval = mysql_query("DROP DATABASE ZipcodeDatabase;", $db_conn);
					if(!$retval)
						echo " <p id='DBDelete1'> No such database to delete </p> ";
					else
						echo " <p id='DBDelete2'> Database deleted successfully </p> ";
	            }
	        }
		?>

	</div>

	<div class = "canvasDiv">
		<canvas id="mapCanvas" width="930" height="440" >
			Your browser does not support the canvas element.
		</canvas>
	</div>

	<div class="bottomBanner">
        <form action="HW6.php" method="get" >
        	<p> LATITUDE: </p>
            <input type="text" input size="7" id="xpos" name="xpos" readonly>
            <p> LONGITUDE: </p>
            <input type="text"  input size="7" id="ypos" name="ypos" readonly>              
           
            <input class="button" type="submit" name="button" value= " List Nearby Zipcodes " /> 
        	<p> Items per Page </p>   
            <select type="submit" name="dropdown" id="selectNumRows">
            	<option>5</option>
            	<option>10</option>
            	<option>15</option>
            	<option>20</option>
            </select> 
        </form>                      
	</div>

	<div class="table">
	<?php

		function latLonToMiles($lat1, $lon1, $lat2, $lon2)
        {  
            $R = 3961;  
            $dlon = ($lon2 - $lon1)*M_PI/180;
            $dlat = ($lat2 - $lat1)*M_PI/180;
            $lat1 *= M_PI/180;
            $lat2 *= M_PI/180;
            $a = pow(sin($dlat/2),2) + cos($lat1) * cos($lat2) * pow(sin($dlon/2),2) ;
            $c = 2 * atan2( sqrt($a), sqrt(1-$a) ) ;
            $d = $R * $c;
            return number_format((float)$d, 2, '.', '');	
        }


		$db_conn = mysqli_connect("localhost", "root", "");
			
			if( isset($_GET["button"]) ){
                                    
	            if( $_GET["button"] == " List Nearby Zipcodes "){
	            	
	            	if(mysqli_select_db($db_conn, 'ZipcodeDatabase')) {
	                	
	                	//connection error to mySQL 
	                	if (!$db_conn)
	                    	die("Unable to connect: " . mysql_error()); 
	                	

						//getting data from each field 
						$userLatitude = $_GET['xpos'];
						$userLongitude = $_GET['ypos'];
						$numItems = $_GET['dropdown'];

						//keeps the chosen number of rows after page reload 
						echo "<script> selectNumRows.value = " . $numItems. "</script>";

						//commmand needed to get nearest zipcodes
						$cmd = "SELECT *, SQRT(POW((Latitude - $userLatitude),2)+POW((Longitude - $userLongitude),2)) AS distance
						FROM clist ORDER BY distance ASC limit $numItems ";
						

						//make initial table 
						echo("<table id='table'>	
							<tr>
							<td> Zip Code </td>
        					<td> City </td>
                         	<td> State </td>
                         	<td> Lat </td>
                         	<td> Lon </td>
                         	<td>Distance (miles) </td>
                         	<td>Time Diff (from ET) </td></tr>" ); 

						// populating database with info from databse
						$records = mysqli_query($db_conn, $cmd);
						if($records){
							while($row = mysqli_fetch_array($records)){
							echo( "<tr>"
								. "<td>" . $row['Zipcode'] . "</td>" 
							 	. "<td>" . $row['City']    . "</td>" 
							 	. "<td>" . $row['State']   . "</td>"
							 	. "<td>" . $row['Latitude'] . "</td>"  
							 	. "<td>" . $row['Longitude']	. "</td>"
							 	. "<td >" . (latLonToMiles($row['Latitude'], $row['Longitude'], $userLatitude, $userLongitude)) . "</td>" 	 		
							 	. "<td>" . ($row['gap']+5) . "</td>"  
							 	. "</td></tr>");					

							
							}
							echo " </table>"."<br>";

						}
						
						        
					} else {
	                	echo " <p id='noDatError'> No database to pull information from. Create a database first! </p>";
	                }
	            }

	        }

	?> </div>

	<script>
        
		function drawMap(){ 

			var canv = document.getElementById("mapCanvas");
        	var c = canv.getContext("2d"); 

			var lat = 38.7664409;
			var lon = -97.8875587;
			var zoom = 4;
			    
			var img = new Image();  
			var w, h;
			
			img.onload = function(){  			  	  
				w = canv.width;		
				h = canv.height;					
				c.drawImage(img, 0, 0, w, h );

				if (sessionStorage.getItem("latitude")){
					makeCircle(sessionStorage.lastMouseX, sessionStorage.lastMouseY);
					document.getElementById("xpos").value = sessionStorage.latitude;
					document.getElementById("ypos").value = sessionStorage.longitude;
				}
			}

			img.src = "http://maps.googleapis.com/maps/api/staticmap?center="+lat+','+lon+"&zoom="+zoom+"&size=930x339&sensor=false";
			
		}

		function getMousePos(canvas, events){

  			var obj = canvas;
  			var top = 0, left = 0;
			var mX = 0, mY = 0;
 			
 			while (obj && obj.tagName != 'BODY') { //accumulate offsets up to 'BODY'
      			top += obj.offsetTop;
      			left += obj.offsetLeft;
      			obj = obj.offsetParent;}
  			
			mX = events.clientX - left + window.pageXOffset;
  			mY = events.clientY - top + window.pageYOffset;

  			makeCircle(mX, mY)
  			return { x: mX, y: mY };

		}

		function makeCircle(x, y){

			var canv = document.getElementById("mapCanvas");
       		var c = canv.getContext("2d");

       		c.globalAlpha = 0.5;
       		c.fillStyle="#CCCCFF";
         	c.strokeStyle="#6666FF";
         	c.lineWidth =2;
         	c.beginPath();
         	c.arc(x,y,20,0,Math.PI*2);
         	c.closePath();
         	c.fill();
         	c.stroke();
         	c.globalAlpha = 1;

         	c.fillStyle="black";
         	c.strokeStyle="black";
         	c.lineWidth = 1;
         	c.beginPath();
         	c.arc(x,y,3,0,Math.PI*2);
         	c.closePath();
         	c.fill();
         	c.stroke();

		}

		function onStart(){
			drawMap()
			
			var canvas = document.getElementById('mapCanvas');
    	   	canvas.addEventListener('mousedown', function(events){
    	   		var mousePos = getMousePos(canvas, events);
    	   		var latitudeField = document.getElementById("xpos");
    	   		var longitudeField = document.getElementById("ypos");
    	   		latitudeField.value = (49.2522 - 0.0494*mousePos.y).toFixed(3);
		  		longitudeField.value = (-125.6301 + 0.0594*mousePos.x).toFixed(3);
		  		

	  			sessionStorage.lastMouseX = mousePos.x;
	  			sessionStorage.lastMouseY = mousePos.y; 
	  			sessionStorage.latitude = latitudeField.value;
	  			sessionStorage.longitude = longitudeField.value;
		  		drawMap()
		  	})
		
		}

	</script>

</body>
</html>