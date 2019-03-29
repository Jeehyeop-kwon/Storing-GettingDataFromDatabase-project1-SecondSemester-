


<?php  

///////////////////////////////////////////////// Validation / Storage /////////
require('include/header.php');

//initializing variables 
    $user_id = null;
    $name = null;
    $email = null;
    $location = null; 
    $skill = null; 
    $nameError = $emailError = $locationError = $skillError = null;
    $ok = true;


if(isset($_POST['submit'])){
  
      $user_id = $_POST['user_id']; 
      // store the form inputs in variables
      $name = filter_input(INPUT_POST, 'name');
      $email = filter_input(INPUT_POST, 'email');
      $location =  filter_input(INPUT_POST, 'location');
      $skill =  filter_input(INPUT_POST, 'skill');

      if(empty($name)) {
        $nameError = "Please fill out the name";
        echo "<span class = w3-text-red>$nameError</span><br>"; 
        $ok = false;
      }

      if(empty($email)) {
        $emailError = "Please fill out the email";
        echo "<span class = w3-text-red>$emailError</span><br>";
        $ok = false;
      }
      
      if(empty($location)) {
        $locationError = "Please fill out the location";
        echo "<span class = w3-text-red>$locationError</span><br>";
        $ok = false;
      }
      
      if(empty($skill)) {
        $skillError = "Please enter the skill!"; 
         echo "<span class = w3-text-red>$skillError</span><br>";
        $ok = false;
      }

      if($ok === false){
        echo "<span class = w3-text-yellow> Please make sure that you store the valid data</span><br>";
        
      } else {

        try {

          require('include/localDatabase.php'); 
            
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
            }

              catch(PDOException $e) {
              //echo $e; 
              echo "<p> There was an error with your form submission </p>"; 
              mail('jeehyeop@naver.com', 'app submission error', $e); 
          } 
      }
  }


///////////////////////////////////////////////// Update //////////////////////

  
  
  if(!empty($_POST['user_id']) && (is_numeric($_POST['user_id']))) {
    

    //grab the user id
      $user_id = $_POST['user_id'];
      $name = $_POST['name']; 
      $email = $_POST['email'];
      $location = $_POST['location'];
      $skill = $_POST['skill'];
      
    
    //connect to the db
    require('include/localDatabase.php'); 
    
    //set up your query 
    $sql = "UPDATE project1 SET name = :name, email = :email, location = :location, skill = :skill WHERE user_id = :user_id"; 
    
    
    //prepare 
    $cmd = $conn->prepare($sql);


    //bind 
    $cmd->bindParam(':user_id', $user_id);
    $cmd->bindParam(':name', $name);
    $cmd->bindParam(':email', $email);
    $cmd->bindParam(':location', $location);
    $cmd->bindParam(':skill', $skill);

    //execute 
    $cmd->execute(); 
    
    //close the database connection 
    $cmd->closeCursor();  
  }

///////////////////////////////////////////////// table(select) //////////////////////


ob_start();


try {
  
  //connect to database 
  
  require('include/localDatabase.php'); 
  
  // set up our sql query
  
  $sql = "SELECT * FROM project1;"; 
  
  //prepare 
  
  $cmd= $conn->prepare($sql);
  
  // execute 
  
  $cmd->execute(); 
  
  //use fetchAll to store our results 
  
  $userInfos = $cmd->fetchAll(); 
  echo '<a href="edit.php"> Add New data </a>';
  echo '<table>
          <thead>
            <th> Name </th>
            <th> Email </th>
            <th> Location </th>
            <th> Skill </th>
            <th class = "w3-text-black"> Edit </th> 
            <th> Delete</th>
          </thead>
          <tbody>';
  
  //loop through the data and create a new table row for each record 
  
  foreach($userInfos as $userInfo) {
    echo '<tr><td>' . $userInfo['name'] . '</td>';
    echo '<td>' . $userInfo['email'] . '</td>';
    echo '<td>' . $userInfo['location'] . '</td>';
    echo '<td>' . $userInfo['skill'] . '</td>';
    echo '<td><a href="edit.php?user_id='. $userInfo['user_id']. '">Edit </a></td>';
    echo '<td><a href="delete.php?user_id=' . $userInfo['user_id'] .'"onclick="return confirm(\'Are you sure?\');"> Delete </a></td></tr>';     
  }
  
  
  echo '</tbody></table>'; 

  
  //close the db connection 
  
  $cmd->closeCursor(); 

}

catch(PDOException $e) {
  header('location:error.php'); 
  mail('jeehyeop@naver.com', 'User Database Problems', $e); 
  
}

ob_flush(); 

require('include/footer.php'); 

?>
