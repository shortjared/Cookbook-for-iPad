<?php
include_once('class-recipes.php');

$recipes = new Recipes;
$call_type = $_GET['call'];

if($call_type == 'listTags')
{
	echo json_encode($recipes->listTags());
}

if($call_type == 'addRecipe')
{
	$tags = explode(',',$_POST['tags']);
	
	/*if(!$recipes->validateRecipe($_POST['recipe'],$_POST['instructions'],$_POST['ingredients'],0,$tags))
		echo failure;*/
	
	$recipes->addRecipe($_POST['recipe'],$_POST['instructions'],$_POST['ingredients'],0,$tags);
	
	//$recipes->addRecipe();
}

if($call_type == 'getAllRecipes')
{
		echo json_encode($recipes->getAll());
}

if($call_type == 'getTagRecipes')
{
		echo json_encode($recipes->getTagRecipes());
}

if($call_type == 'getSingle')
{
		echo json_encode($recipes->getSingle($_GET['id']));
}

if($call_type == 'getFavorites')
{
		echo json_encode($recipes->getFavorites());
}

if($call_type == 'getTag')
{
		echo json_encode($recipes->getTagRecipes($_GET['id']));
}

?>