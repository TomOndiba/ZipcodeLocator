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
			<input class="button" type="submit" name="button" value=" Create DB " />
			<input class="button" type="submit" name="button" value=" Drop DB " />
		</form>
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

	<div class="table"></div>

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
    	   		var tx = document.getElementById("xpos");
    	   		var ty = document.getElementById("ypos");
    	   		tx.value = (49.2522 - 0.0494*mousePos.y).toFixed(3);
		  		ty.value = (-125.6301 + 0.0594*mousePos.x).toFixed(3);
		  		

	  			sessionStorage.lastMouseX = mousePos.x;
	  			sessionStorage.lastMouseY = mousePos.y; 
	  			sessionStorage.latitude = tx.value;
	  			sessionStorage.longitude = ty.value;
		  		drawMap()
		  	})
		
		}

	</script>

</body>
</html>