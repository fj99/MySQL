<?php
    include '../../DB/connect_to_db.php';

    $db_name = 'daycare';

    $conn = get_db_connection($db_name);

    // Initialize sessions
    session_start(); 
    //session var
    $fname = $_SESSION['fname'];
    $lname = $_SESSION['lname'];
    $email = $_SESSION['email'];
    $id = $_SESSION['id'];
    $username = $_SESSION['username'];

    // Check if the user is already logged in, if yes then redirect him to welcome page
    if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true))
    {
        header("location: ../../index.html");
        exit;       
    }

    if(isset($_POST['submit']))
        { 
            $First = $_POST['FirstName'];
            $Last = $_POST['LastName'];
            $sex = $_POST['sex'];
            $DateB = $_POST['date'];
            $md = $_POST['MD'];
            $cname = $_POST['Cname'];
            
            //md is not updating
            /* $md = NUll;
            echo gettype($md);
            echo "<br>"; */

            if(strlen($md)>1)
            {
                $sql = "UPDATE child SET childFirstName = '".$First."', childLastName = '".$Last."', child_sex = '".$sex."', child_Date_of_Birth = '".$DateB."', medicalRecord = '".$md."' WHERE ParentID = '".$id."' AND childFirstName = '".$cname."'";
                echo $sql;
                $result=mysqli_query($conn, $sql);
            }
            else
            {
                $sql = "UPDATE child SET childFirstName = '".$First."', childLastName = '".$Last."', child_sex = '".$sex."', child_Date_of_Birth = '".$DateB."' WHERE ParentID = '".$id."' AND childFirstName = '".$cname."'";
                echo $sql;
                $result=mysqli_query($conn, $sql);
                //delete md so it becomes null

            }
            

        }

    if(isset($_POST['name']))
    {
        $cname = $_POST['cname'];
        $sql = "SELECT childFirstName, childLastName, child_sex, child_Date_of_Birth, medicalRecord FROM child WHERE ParentID = $id AND childFirstName = '".$cname."' ";
        echo $sql;
        $result = mysqli_query($conn, $sql);
        $row = $result->fetch_assoc(); 

        $First = $row['childFirstName'];
        $Last = $row['childLastName'];
        $sex = $row['child_sex'];
        $DateB = $row['child_Date_of_Birth'];
        $md = $row['medicalRecord'];
    }
    

?>

<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <meta charset="utf-8">
    </head>

    <body> 
        <h2>Welcome To our Daycare <?php echo $fname; ?> <?php echo $lname; ?> </h2>
        <h3>Your Username is <?php echo $username; ?> </h3><br>
        <h3>Your email is <?php echo $email; ?> </h3><br>

        <form name = "name" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="cname">Select which child:</label><br>
            <select name="cname" >
                <?php    
                    $result=mysqli_query($conn,"SELECT childFirstName FROM child WHERE ParentID = $id "); 
                    While($row=mysqli_fetch_assoc($result))
                    {
                        ?>
                        <option value=<?php echo $row['childFirstName']?>><?php echo $row['childFirstName']?></option>;
                        <?php
                    }
                ?> 
            </select>
            <input class="submit" name = "name" type="submit">            
        </form>
        
        <?php
        if(isset($_POST['name']))
        {
            ?>
            <form name = "submit" action="<?php echo $_SERVER['PHP_SELF']; ?>"  method="POST"> 

                Child's First Name: <input type="text" name = "FirstName" value = <?php echo $First;?>  ><br>
                <br>

                Child's Last Name: <input type="text" name = "LastName" value = <?php echo $Last;?> ><br>
                <br>
                
                Child's sex: <input type="text" name = "sex" value = <?php echo $sex;?> ><br>
                <br>

                Child's Date of Birth: <input type="date" name = "date" value = <?php echo $DateB;?> ><br>
                <br>
                
                Child's medical Record: <input type="file" name = "MD" value = <?php echo $md;?> ><br>
                <br>
                <input type = "hidden" name = "Cname" value = <?php echo $_POST['cname']; ?>>

                <input class="submit" name = "submit" type="submit"> 
            </form>
            <?php
        }
        ?>
        <br>
        <br>
        <a  href = ../../index.php><img src="../../assets/img/home-symbol.jpg" width="50px" /></a>
    </body>
</html>