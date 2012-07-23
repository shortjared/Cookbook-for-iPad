<html>
<head>
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name = "viewport" content = "user-scalable = no">
<meta name="viewport" id="viewport" content="width=device-width,minimum-  scale=1.0,maximum-scale=10.0,initial-scale=1.0" />
<LINK REL=StyleSheet HREF="css/reset.css" TYPE="text/css" MEDIA=screen>
<LINK REL=StyleSheet HREF="css/960.css" TYPE="text/css" MEDIA=screen>	
<LINK REL=StyleSheet HREF="css/style.css" TYPE="text/css" MEDIA=screen>
<link href='http://fonts.googleapis.com/css?family=Montez' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Enriqueta:400,700' rel='stylesheet' type='text/css'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="js/quickpager.jquery.js"></script>

<script>

$(document).ready(function() {
   
   function updateTags(){ 	
	   $.ajax({
	  		url: "api.php?call=listTags",
	  		success: function(data) {
	  			$("#tag_cloud").empty();
	  			var tags = jQuery.parseJSON(data);
				$.each(tags, function() {
					    $('#tag_cloud').append(
					        '<a class="tag" href="#" data-tagid="'
					        + this.id
					        + '">'
					        + this.tag +
					        '</a> '
					    );
					});
					
	    		}
	  	});
  	}
  	
    function getAllRecipes(){ 	
	   $.ajax({
	  		url: "api.php?call=getAllRecipes",
	  		success: function(data) {
	  			$("#right_title").html('All Recipes');
	  			$("#right_page").empty();
	  			$('#right_page').append('<ul id="listRecipes"></ul>');
	  			var tags = jQuery.parseJSON(data);
				$.each(tags, function() {
					if(this.favorite == 1)
						var favorite = '<img src="images/star.png" />';
					else
						var favorite = ''
					    $('#listRecipes').append(
					        '<li>'+favorite +'<a href="#" class="recipe" data-recipeid="'
					        + this.id
					        + '">'
					        + this.recipe +
					        '</a>'+
					        '</li>'
					    );
				});
				$('#listRecipes').quickPager();
	    		}
	    		
	  	});
  	}
  	
  	 function getFavorites(){ 	
	   $.ajax({
	  		url: "api.php?call=getFavorites",
	  		success: function(data) {
	  			$("#right_title").html('Favorite Recipes');
	  			$("#right_page").empty();
	  			$('#right_page').append('<ul id="listRecipes"></ul>');
	  			var tags = jQuery.parseJSON(data);
				$.each(tags, function() {
					if(this.favorite == 1)
						var favorite = '<img src="images/star.png" />';
					else
						var favorite = '';
					    $('#listRecipes').append(
					        '<li>'+favorite +'<a href="#" class="recipe" data-recipeid="'
					        + this.id
					        + '">'
					        + this.recipe +
					        '</a>'+
					        '</li>'
					    );
				});
				$('#listRecipes').quickPager();
	    		}
	    		
	  	});
  	}
  	
  	$('#addNewRecipeButton').click(function(){
  		$('#addRecipeForm').slideToggle();
  		$('#tag_cloud').slideToggle();
  	});
  	
  	$('#addRecipeFormSubmit').click(function(){
  		event.preventDefault();
  		$.post("api.php?call=addRecipe", $("#addRecipe").serialize(),
			   function(data) {
			     $('#addRecipeForm').slideToggle(function(){
			     		updateTags();
						getAllRecipes();
			     	$('#tag_cloud').fadeIn();
			     	$('INPUT:text, INPUT:password, INPUT:file, SELECT, TEXTAREA', '#addRecipe').val(''); 
			     }); 
			     
			   });
  	});
  	
  	$('.recipe').live('click',function(){
  		$.ajax({
	  		url: 'api.php?call=getSingle&id='+$(this).data('recipeid'),
	  		success: function(data) {
	  			recipe = jQuery.parseJSON(data);
	  			$("#right_page").empty();
	  			$("#right_title").html(recipe.recipe);
	  			$("#right_page").html('<h2>Ingredients</h2>' +
	  				'<p><pre>'+recipe.ingredients+'</pre></p>'+
	  				'<h2>Instructions</h2>' +
	  				'<p><pre>'+recipe.instructions+'</pre></p>'
	  				
	  			)
	  		}
  		});
  	});
  	
  	$('.tag').live('click',function(){
  		tag_name = $(this).html();
  		$.ajax({
	  		url: 'api.php?call=getTag&id='+$(this).data('tagid'),
	  		success: function(data) {
	  			recipe = jQuery.parseJSON(data);
	  			$("#right_page").empty();
	  			$("#right_title").html(tag_name + ' Recipes');
	  			$('#right_page').append('<ul id="listRecipes"></ul>');
	  			var tags = jQuery.parseJSON(data);
				$.each(tags, function() {
					if(this.favorite == 1)
						var favorite = '<img src="images/star.png" />';
					else
						var favorite = '';
					    $('#listRecipes').append(
					        '<li>'+favorite +'<a href="#" class="recipe" data-recipeid="'
					        + this.recipe_id
					        + '">'
					        + this.recipe +
					        '</a>'+
					        '</li>'
					    );
				});
				$('#listRecipes').quickPager();
	  		}
  		});
  	});
  	  	
  	$('#favorites_link').click(function(){getFavorites()});
	$('#allRecipes').click(function(){getAllRecipes()});
	
	updateTags();
	getAllRecipes();
	
 });
</script>
</head>
	
<body>
<div class="container_12">
  
  <!-- Page Headers-->
  <div class="grid_6" id="left_header">
    <h2 id="left_title">Mom's Cookbook</h2>
  </div>
  
  <div class="grid_6" id="right_header">
    <h2 id="right_title">Favorite Recipes</h2>
  </div>
  <!-- End Page Headers-->
  <div class="clear"></div>
  
  
  
  <!-- Left Page Content -->
  <div class="grid_6">
  	<div id="menu"><a id="allRecipes" href="#">All</a><a id="favorites_link" href="#">Favorites</a><a id="addNewRecipeButton" href=#>Add New Recipe</a></div>
    
    
    <div id="addRecipeForm">
    	<form id="addRecipe">
    		<input id="recipe" name="recipe" type="text" placeholder="Recipe Name"/>
    		
    		<!--<textarea id="description" name="description" placeholder="Description of recipe."></textarea>-->
    		
    		<textarea id="ingredients" rows=6 name="ingredients" placeholder="Add one ingredient/qty per line."></textarea>
    		
    		<textarea id="instructions" rows=6 name="instructions" placeholder="Instructions for recipe."></textarea>
    		
    		<input id="tags" type="text" name="tags" placeholder="Tags (Dessert, Chocolate, etc)"/>
    		
    		<input type="submit" id="addRecipeFormSubmit" value="Submit" />
    	</form>
    </div>    
    
    <div id="tag_cloud">
    </div>
  
  
  </div>
  <!-- End Left Page Content-->
  
  
  
  <!-- Right Page Content -->
  <div id="right_page" class="grid_6" style="width:400px;">
    
  </div>
  <!-- End Right Page Content -->
  <div class="clear"></div>
</div>
		
</body>	
</html>
