<?php 

  // add the movie id in case you are editing 
  $user_id = null;
  $nameError = $emailError = $locationError = $skillError = null;
  $ok = true;
  $edit = null;
  $confirm = null;
  $homePage = null;

 if(isset($_POST['submit'])){
  
      $user_id = $_POST['user_id']; 
      // store the form inputs in variables
      $name = filter_input(INPUT_POST, 'name');
      $email = filter_input(INPUT_POST, 'email');
      $location =  filter_input(INPUT_POST, 'location');
      $skill =  filter_input(INPUT_POST, 'skill');

      if(empty($name)) {
        $nameError = "Please fill out your name"; 
        $ok = false;
      }

      if(empty($email)) {
        $emailError = "Please fill out your email"; 
        $ok = false;
      }
      
      if(empty($location)) {
        $locationError = "Please fill out your location";
        $ok = false;
      }
      
      if(empty($skill)) {
        $skillError = "Please enter your skill!"; 
        $ok = false;
      }

      if($ok === false){
        $confirm = '<a href="index.php"> We couldn\'t save your data<br>
                    Please, go back to previous page and try again</a>';
       
      } else {

        try {

          require('include/azureDatabase.php');
            
              if(!empty($user_id)) {
                $sql = "UPDATE project1 SET name = :name, email = :email, location = :location, skill = :skill WHERE user_id = :user_id";  
        
              }
              else {
              
                $sql = "INSERT INTO project1(name, email, location, skill) VALUES (:name, :email, :location, :skill)";

              }
            
              $cmd = $conn->prepare($sql); 
          
              $cmd->bindParam(':name', $name);
              $cmd->bindParam(':email', $email);
              $cmd->bindParam(':location', $location); 
              $cmd->bindParam(':skill', $skill);
              
               if(!empty($user_id)) {
                $cmd->bindParam(':user_id', $user_id);   
                }
            
              $cmd->execute(); 
          
              $cmd->closeCursor(); 
              

              $edit = '<button class="w3-button w3-block w3-center w3-red w3-large w3-margin-bottom" type="submit" name="submit">
                        <a href="index.php"> Edit </a>
                      </button>';
              $confirm = '<a href="thankyou.php">confirm</a>';

            }

              catch(PDOException $e) {
              //echo $e; 
              echo "<p> There was an error with your form submission </p>"; 
              mail('jeehyeop@naver.com', 'app submission error', $e); 
          } 
      }
  }

    require('include/header.php');

?>

    <main class="w3-container w3-margin">
       <h1 style="color: white"> Your information </h1>

      <div class="w3-card-4 w3-blue w3-padding-top">
        <p class="w3-large w3-text-black"><b>Your name</b></p>
        <?php echo "$name"?>
        <?php echo "<p class = w3-text-red>$nameError</p>"?>

        <p class="w3-large w3-text-black"><b>Your email</s></p>
        <?php echo "$email"?>
        <?php echo "<p class = w3-text-red>$emailError</p>"?>

        <p class="w3-large w3-text-black"><b>Your location</b></p>
        <?php echo "$location"?>
        <?php echo "<p class = w3-text-red>$locationError</p>"?>

        <p class="w3-large w3-text-black"><b>Your skill</b></p>
        <?php echo "$skill"?>
        <?php echo "<p class = w3-text-red>$skillError</p>"?>
      </div>

      <div>
        <?php echo $edit ?>
          <button class="w3-button w3-block w3-center w3-red w3-large" type="submit" name="submit">
            <?php echo $confirm ?>
          </button>
      </div>

    </main>

    <?php require('include/footer.php') ?>

