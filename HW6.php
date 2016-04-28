<!DOCTYPE html>
<html>
    
<head>
	<title>Zip Code Locator</title>
	<link rel="stylesheet" href="HW6.css">
</head>
    
<body>

	 <div class="topBanner">
	        <div class="infoBanner">
	                <p> THE COM 214 ZIP CODE LOCATOR </p>     
					<form class ="form1" action="HW4.php" method="get">
						<input class="button" type="submit" name="button" value=" Create DB " />
						<input class="button" type="submit" name="button" value=" Drop DB " />
					</form>
			</div>
	</div>

	<div class = "canvasDiv">
		<div> 
			<canvas id="mapCanvas" width="855" height="364" >
				Your browser does not support the canvas element.
			</canvas>
		</div>
	</div>

	<div class="bottomBanner">
	    <div class="optionBanner">
	        <form action="HW6.php" method="get" >
	        
	            LATITUDE:   <input type="text" input size="5" id="xpos" name="xpos" readonly>
	            LONGITUDE:  <input type="text"  input size="5" id="ypos" name="ypos" readonly>              
	           
	            <input class="button" type="submit" name="button" value="List Nearby Zipcodes"  /> 
	        	Items per Page   
	            <select type="submit" name="drop" id="selectImg">
	                      <option >5</option>
	                      <option >10</option>
	                      <option >15</option>
	                      <option >20</option>
	            </select> 
	        </form>                      
	    </div>

	    <div class ="table">
	    </div>
	</div>

</body>
</html>