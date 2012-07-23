<?php
/**
 * 
 */
 
class Recipes {
	
 	var $db;//Database Class
	
	function __construct() {
		$this->db = new PDO("sqlite:recipes.db"); 
	}
	
	function validateRecipe($recipe=NULL,$instructions=NULL,$ingredients=NULL,$favorite=0,$tags=NULL){
		if($recipe == NULL)
			return false;
		if($instructions == NULL)
			return false;
		if($ingredients == NULL)
			return false;
		if($favorite != 1 || $favorite != 0)
			return false;
	}
	
	function addRecipe($recipe=NULL,$instructions=NULL,$ingredients=NULL,$favorite=0,$tags=NULL){
		$query = $this->db->prepare('INSERT INTO recipes (recipe, instructions,ingredients, favorite) VALUES (?,?, ?, ?)');
		$query->execute(array($recipe,$instructions,$ingredients,$favorite));
		$recipe_id = $this->db->lastInsertId();
		
		if($tags != NULL)
		{
			$tag_query = $this->db->prepare('SELECT COUNT(*) as tagCount FROM tags WHERE tag = ?');
			$tag_insert = $this->db->prepare('INSERT INTO tags (tag) VALUES (?)');
			$tag_ider = $this->db->prepare('SELECT id FROM tags WHERE tag = ?');
			$tag_recipe = $this->db->prepare('INSERT INTO tag_recipe (recipe_id,tag_id) VALUES (?,?)');
			
			
			foreach($tags as $tag)
			{
				if($tag == '')
					continue;
				
				$tag_query->execute(array($tag));
				$tag_count = $tag_query->fetchColumn();
				if($tag_count==0)
				{
					$tag_insert->execute(array($tag));
					$tag_id = $this->db->lastInsertId();
					$tag_recipe->execute(array($recipe_id,$tag_id));
				}
				else
				{
					$tag_ider->execute(array($tag));
					$tag_id = $tag_ider->fetchColumn(0);
					$tag_recipe->execute(array($recipe_id,$tag_id));
				}
					
			}
			
			
		}

		return $recipe_id;
	}
	
	
	function editRecipe($id,$recipe=NULL,$instructions=NULL,$ingredients=NULL,$favorite=0,$tags=NULL){
		$query = $this->db->prepare('UPDATE recipes SET recipe=?, instructions=?, ingredients=?, favorite=? WHERE id = ?');
		$query->execute(array($recipe,$instructions,$ingredients,$favorite,$id));
		
			$tag_query = $this->db->prepare('SELECT COUNT(*) as tagCount FROM tags WHERE tag = ?');
			$tag_insert = $this->db->prepare('INSERT INTO tags (tag) VALUES (?)');
			$tag_ider = $this->db->prepare('SELECT id FROM tags WHERE tag = ?');
			$tag_recipe = $this->db->prepare('INSERT INTO tag_recipe (recipe_id,tag_id) VALUES (?,?)');
			
			$this->db->query("DELETE FROM tag_recipe WHERE recipe_id = $id");
			
			foreach($tags as $tag)
			{
				if($tag == '')
					continue;
				
				$tag_query->execute(array($tag));
				$tag_count = $tag_query->fetchColumn();
				if($tag_count==0)
				{
					$tag_insert->execute(array($tag));
					$tag_id = $this->db->lastInsertId();
					$tag_recipe->execute(array($id,$tag_id));
				}
				else
				{
					$tag_ider->execute(array($tag));
					$tag_id = $tag_ider->fetchColumn(0);
					$tag_recipe->execute(array($id,$tag_id));
				}
					
			}
			
			
	}
	
	function favoriteRecipe($id){
		$query = $this->db->prepare('UPDATE recipes SET favorite = 1 WHERE id = ?');
		return $query->execute(array($id));
	}
	
	function unfavoriteRecipe($id){
		$query = $this->db->prepare('UPDATE recipes SET favorite = 0 WHERE id = ?');
		return $query->execute(array($id));
	}
	
	function getAll($start=0,$limit=50){
		$query = $this->db->prepare("SELECT * FROM recipes WHERE id > ? LIMIT ?");	
		$query->execute(array($start,$limit));
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function getFavorites($limit=100){
		$query = $this->db->prepare("SELECT * FROM recipes WHERE favorite=1 LIMIT ?");	
		$query->execute(array($limit));
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function getSingle($id){
		$query = $this->db->prepare("SELECT * FROM recipes WHERE id=?");
		$query->execute(array($id));
		$recipe = $query->fetch(PDO::FETCH_ASSOC);
		return $recipe;
	}
	
	function listTags(){
		$query = $this->db->prepare("SELECT DISTINCT * FROM tags");
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function getTagRecipes($tag_id){
		$query = $this->db->prepare("SELECT * FROM recipes JOIN tag_recipe ON tag_recipe.recipe_id = recipes.id WHERE tag_id = ?");
		$query->execute(array($tag_id));
		return $query->fetchAll(PDO::FETCH_ASSOC);
		
	}
	
	function listTables(){
		
		$result = $this->db->query("SELECT * FROM sqlite_master WHERE type='table';");	
		
		foreach ($result as $entry) {
		    echo 'Name: ' . $entry['name'];
			echo "<br />";
		}
		
	}
}


?>
