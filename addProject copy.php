<?php
	session_start();
	ob_start();
	$Ssn = $_SESSION["Ssn"];
	// Include config file
	require_once "config.php";
?>


<?php 
	// Define variables and initialize with empty values
	$Dependent_name = $Sex = $Bdate = $Relationship = "";
	$Ssn_err = $Dependent_name_err = $Sex_err = $Bdate_err = $Relationship_err = "" ;
 
	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		// Validate Dependent Name
		$Dependent_name = trim($_POST["Dependent_name"]);
		if(empty($Dependent_name)){
			$Dependent_name_err = "Please enter a Dependent Name.";
		} elseif(!filter_var($Fname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
			$Dependent_name_err = "Please enter a valid Dependent Name.";
		} 
		// Validate Sex
		$Sex = trim($_POST["Sex"]);
		if(empty($Sex)){
			$Sex_err = "Please enter Sex.";     
		}
		// Validate Birthdate
		$Bdate = trim($_POST["Bdate"]);
		if(empty($Bdate)){
			$Bdate_err = "Please enter birthdate.";     
		}	
		// Validate Relationship
		$Relationship = trim($_POST["Relationship"]);
		if(empty($Relationship)){
			$Relationship_err = "Please enter a Relationship.";
		} elseif(!filter_var($Fname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
			$Relationship_err = "Please enter a valid Relationship";
		} 
		// Validate the SSN
		if(empty($Ssn)){
			$Ssn_err = "No SSN.";     
		}
    // Check input errors before inserting in database
		if(empty($Ssn_err) && empty($Dependent_name_err) && empty($Sex_err) && empty($Bdate_err) && empty($Relationship_err) ){
        // Prepare an insert statement
			$sql = "INSERT INTO DEPENDENT (Essn, Dependent_name, Sex, Bdate, Relationship) VALUES (?, ?, ?, ?, ?)";
        	if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, 'sii', $param_Ssn, $$param_Dependent_name, $param_Sex, $param_Bdate, $param_Relationship);
            
				// Set parameters
				$param_Ssn = $Ssn;
				$param_Dependent_name = $Dependent_name;
				$param_Sex = $Sex;
				$param_Bdate = $Bdate;
				$param_Relationship = $Relationship;
        
            // Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
               // Records created successfully. Redirect to landing page
				//    header("location: index.php");
				//	exit();
				} else{
					// Error
					
					$SQL_err = mysqli_error($link);
				}

			}
         
        // Close statement
        mysqli_stmt_close($stmt);
		
	}   
		// Close connection
		mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Dependent</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h3>Add a Dependent for SSN = 
							<?php echo $Ssn;?>			
						</h3>
                    </div>
				
<?php
	echo $SQL_err;		
	$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	if (!$conn) {
		die('Could not connect: ' . mysqli_error());
	}
	$sql = "SELECT Pnumber, Pname FROM PROJECT";
	$result = mysqli_query($conn, $sql);
	if (!$result) {
		die("Query to show fields from table failed");
	}
	$num_row = mysqli_num_rows($result);	
?>	

	<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
		<div class="form-group <?php echo (!empty($Ssn_err)) ? 'has-error' : ''; ?>">
            <label>Project number & name</label>
			<select name="Pno" class="form-control">
			<?php

				for($i=0; $i<$num_row; $i++) {
					$Pnos=mysqli_fetch_row($result);
					echo "<option value='$Pnos[0]' >".$Pnos[0]."  ".$Pnos[1]."</option>";
				}
			?>
			</select>	
            <span class="help-block"><?php echo $Pno_err;?></span>
		</div>
		<div class="form-group <?php echo (!empty($Hours_err)) ? 'has-error' : ''; ?>">
			<label>Hours </label>
			<input type="number" name="Hours" class="form-control" min="1" max="80" value="">
			<span class="help-block"><?php echo $Hours_err;?></span>
		</div>
		<div>
			<input type="submit" class="btn btn-success pull-left" value="Add Project">	
			&nbsp;
			<a href="viewDependents.php" class="btn btn-primary">List Dependents</a>

		</div>
	</form>
<?php		
	mysqli_free_result($result);
	mysqli_close($conn);
?>
</body>

</html>

	

