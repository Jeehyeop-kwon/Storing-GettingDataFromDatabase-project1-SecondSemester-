<?php  

	//initializing variables 
    $user_id = null; 
    $name = null; 
    $email = null;
    $location = null; 
    $skill = null; 
  
  if(!empty($_GET['user_id']) && (is_numeric($_GET['user_id']))) {
    
    //grab the user id from the URL string 
    $user_id = $_GET['user_id'];
    
    //connect to the db
    require('include/azureDatabase.php');
    
    //set up your query 
    $sql = "SELECT * FROM project1 WHERE user_id = :user_id";
    
    //prepare 
    $cmd = $conn->prepare($sql);
    
    //bind 
    $cmd->bindParam(':user_id', $user_id);
    
    //execute 
    $cmd->execute(); 
    
    //use fetchAll method to store info in an array 
    $userInfos = $cmd->fetchAll(); 
    
    //store userInfo from database
     foreach ($userInfos as $userInfo) {
     	$name = $userInfo['name']; 
	    $email = $userInfo['email'];
	    $location = $userInfo['location'];
	    $skill = $userInfo['skill'];
    }
    
    
    //close the database connection 
    
    $cmd->closeCursor();  
  }

  require('include/header.php');

?>

	<main class="w3-container">
		<div class="w3-card-4 w3-blue w3-padding-top">
			<form class="w3-container" method="post" action="userCheck.php">
			  	 
		      <input class="w3-input w3-margin-top w3-large" type="text" name="name" placeholder="What's Your Name?" value="<?php echo $name ?>">
		 
		      <input class="w3-input w3-large" type="text" name="email" placeholder="What's Your Email?" value="<?php echo $email ?>">
		    
		      <input class="w3-input w3-large" type="text" name="location" placeholder="Where are your living now?" value="<?php echo $location ?>">
		    
		      <input class="w3-input w3-large" type="text" name="skill" placeholder="What's your skill?" value="<?php echo $skill ?>">
		      <input type="hidden" name="user_id" value="<?php echo $user_id?>">
		      <input class="w3-btn w3-block w3-large" type="submit" name="submit" value="submit">

		    </form>
		</div>
	</main>

	<?php require('include/footer.php') ?>

