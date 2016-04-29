<!DOCTYPE html>
<html>
    
<head>
	<title>Zip Code Locator</title>
	<link rel="stylesheet" href="HW6.css">
	<script src="https://maps.googleapis.com/maps/api/js?v=3"></script>
</head>
    
<body onload="onStart()">
	 <div class="topBanner">
	        <div class="infoBanner">
	                <p> THE COM 214 ZIP CODE LOCATOR </p>     
					<form class ="form1" action="HW6.php" method="get">
						<input class="button" type="submit" name="button" value=" Create DB " />
						<input class="button" type="submit" name="button" value=" Drop DB " />
					</form>
			</div>
	</div>

	<div class = "canvasDiv">
		<div> 
			<canvas id="mapCanvas" width="930" height="440" >
				Your browser does not support the canvas element.
			</canvas>
		</div>
	</div>

	<div class="bottomBanner">
	    <div class="optionBanner">
	        <form action="HW6.php" method="get" >
	        	
	        	<p> LATITUDE: </p>
	            <input type="text" input size="5" id="xpos" name="xpos" readonly>
	            <p> LONGITUDE: </p>
	            <input type="text"  input size="5" id="ypos" name="ypos" readonly>              
	           
	            <input class="button" type="submit" name="button" value=" List Nearby Zipcodes "  /> 
	        	<p> Items per Page </p>   
	            <select type="submit" name="drop" id="selectImg">
	            	<option >5</option>
	            	<option >10</option>
	            	<option >15</option>
	            	<option >20</option>
	            </select> 
	        </form>                      
	    </div>
	</div>

	<div class ="table"> </div>

	<script>

		var canv = document.getElementById("mapCanvas");
        var c = canv.getContext("2d");
		
		function draw(){  

			var lat = 38.7664409;
			var lon = -97.8875587;
			var zoom = 4;
			    
			var img = new Image();  
			var w, h;
			
			img.onload = function(){  			  	  
				w=canv.width;		
				h=canv.height;					
				c.drawImage(img, 0, 0, w, h ); 	
			}

			img.src = "http://maps.googleapis.com/maps/api/staticmap?center="+lat+','+lon+"&zoom="+zoom+"&size=930x440&sensor=false";
		}

		function onStart(){
			draw()
		}

	</script>

</body>
</html>