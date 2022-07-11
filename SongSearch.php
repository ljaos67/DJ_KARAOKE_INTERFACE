<!--
    Sania Azhar    (z1884677)
    Nikolas Gatov  (z1884744)
    Leo Jaos       (z1911688)
    Olivia Merrell (z1896986)
    Muhammad Naeem (z1906224)
    
    CSCI 466-0001
    Group Project - Karaoke Management System
-->

<html>
    <?php
        session_start();

        $username='z1896986';
        $password='2002Jan22';

        try
        {
            $dsn = "mysql:host=courses;dbname=z1896986";
            $pdo = new PDO($dsn, $username, $password);
        }
        catch(PDOexception $e)
        {
            echo "Connection to database failed: " . $e->getMessage();
	}
    ?>
    <style>
        body 
        {
            background-image: url('MainMenu.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
	    background-size: 100% 100%;
        }
        form 
        {
            width: 1200px;
	    margin: auto;
            margin-left: 150px;
        }
        input 
	{
            color: blue;
            padding: 10px 10px;
            border: 0;
            font-size: 15px;
        }
        h2 
        {
            color: white;
	   
	}
        div.scroll 
	{
            margin-left: 0px;
	    background-color: white;
            width: 500px;
	    height: 150px;
            overflow-x: hidden;
	    overflow-y: auto;
            border: 3px solid purple;
            text-align: auto;
        }
    </style>
    <script>
	function toggle() 
	{   
	    if (document.getElementById('asc_desc').value == 'asc')
  	    {
    		document.getElementById('asc_desc').value = 'desc';
	    }
	    else
	    {
		document.getElementById('asc_desc').value = 'asc';
	    }
        }
    </script>

    <head>
        <title>CSCI466 Group Project - Karaoke Management</title>
    </head>

    <body>
	<form action="SongSearch.php" method="POST" name="song_search"> <br/><br/><br/></br></br></br></br> 
	 <h2>Search for a song by artist, title, or contributor by clicking the corresponding button</h2>
	  <table>
	    <tr><td style="color:white"><input type="radio" name="Radio" value="artist"<?php if(isset($_POST['Radio']) && $_POST['Radio'] == 'artist'){echo "checked";}?>>&nbsp;Artist</input>
            </td></tr>
	    <tr><td style="color:white"><input type="radio" name="Radio" value="title" <?php if(isset($_POST['Radio']) && $_POST['Radio'] == 'title'){echo "checked";}?>>&nbsp;Title</input>
            </td></tr>
	    <tr><td style="color:white"><input type="radio" name="Radio" value="contributor" <?php if(isset($_POST['Radio']) && $_POST['Radio'] == 'contributor'){echo "checked";}?>>&nbsp;Contributor</input></td></tr>
	    <td><input type="text" name="text" value="<?php echo (isset($_POST['text']) ? $_POST['text'] : '') ?>" /></td>
	    <td><input type="submit" name="search" value="Search"/></td>
	    <input type="hidden" id="asc_desc" name="asc_desc" value="<?php echo (isset($_POST['asc_desc']) ? $_POST['asc_desc'] : 'asc') ?>"/>
	</table>

    <?php  
	if (isset($_POST['search']))
	{
	    $radioSelect = ' '; 

	    if (!empty($_POST['Radio']))
            {
	        $radioSelect = $_POST['Radio'];
	    }

	    $searched = $_POST['text'];
	    $asc_desc = $_POST['asc_desc'];
	    
	    if($radioSelect == 'artist')
	    {
		echo "<p style='color:white'>Select a song from the list:</p>";
                $artist = $searched;

                $artistQuery = "SELECT S.Artist, S.Title, K.Version, S.SID, K.KID FROM Songs S, Kfiles K WHERE S.SID = K.SID AND Artist LIKE '%" . $artist."%' ORDER BY Artist $asc_desc";
                $getQuery = $pdo->query($artistQuery);
                 
	        echo "<div id='search_results' name='search_results' class='scroll'>";
                while ($rows = $getQuery->fetch())
		{
		    echo "<div style='color:black'> <input type='radio' name='selected_song' value=".$rows['4']." />". $rows['0'] ." - ". $rows['1']." (".$rows['2']." Version) </div>";  	
		}
		echo "</div> <input type='submit' name='search' value='Sort' onclick='toggle()'/> <input type='submit' name='song_select' value='Select Song'/>";

	    }
	    else if($radioSelect == 'title')
	    {
		echo "<p style='color:white'>Select a song from the list:</p>";
		
		$title = $searched;
		$titleQuery = "SELECT S.Title, S.Artist, K.Version, S.SID, K.KID FROM Songs S, Kfiles K WHERE S.SID = K.SID AND Title LIKE '%" . $title."%' ORDER BY Title $asc_desc";
		$getQuery = $pdo->query($titleQuery);
        
	        echo "<div id='search_results' name='search_results' class='scroll'>";
                while ($rows = $getQuery->fetch())
	        {   
		    echo "<div style='color:black'> <input type='radio' name='selected_song' value=".$rows['4']." />". $rows['0'] ." - ". $rows['1']." (".$rows['2'] ." Version) </div>";	
		}
		echo "</div> <input type='submit' name='search' value='Sort' onclick='toggle()'/> <input type='submit' name='song_select' value='Select Song''/>";

             }
             else if($radioSelect == 'contributor')
	     {
		echo "<p style='color:white'>Select a song from the list:</p>";
  
                $contributor = $searched;
            
                $contributorQuery = "SELECT C.Name AS Name, CR.Role, S.Title, K.Version, S.SID, K.KID FROM Contributor C, Contribution CR, Songs S, Kfiles K WHERE C.CID = CR.CID AND S.SID = CR.SID AND S.SID = K.SID AND Name LIKE '%" . $contributor."%' ORDER BY Name $asc_desc";
                $getQuery = $pdo->query($contributorQuery);
           
	        echo "<div id='search_results' name='search_results' class='scroll'>";
                while ($rows = $getQuery->fetch())
		{
		    echo "<div style='color:black'><input type='radio' name='selected_song' value=".$rows['5']." />". $rows['0'] ."  (". $rows['1']. ") - ". $rows['2']." (".$rows['3']." Version) </div>";
		}
		echo "</div> <input type='submit' name='search' value='Sort' onclick='toggle()'/> <input type='submit' name='song_select' value='Select Song''/>";
	     }
	     else
	     {
		echo "<p style='color:white'>Please select a search type</p>";
	     }
	}
    ?>  
       <?php 
       if (isset($_POST["song_select"])) 
       {
            $KID = $_POST['selected_song'];    
	    $_SESSION['KID'] = $KID;

	    $songQuery = "SELECT S.Title, S.Artist, K.Version, S.SID, K.KID FROM Songs S, Kfiles K WHERE S.SID = K.SID AND K.KID = $KID";
	    $getQuery = $pdo->query($songQuery);
       
	    while ($rows = $getQuery->fetch())
	    {
		$_SESSION['SID'] = $rows['3'];

		echo "</br><p style='color:white'>Selected Song:&nbsp;".$rows['0']." - ". $rows['1'] . " (". $rows['2'] ." Version)</p>";
	    }
        }
       ?>
	  <form action=" " method="POST" id="Username" name="Username">
              <?php 
              if (isset($_POST["song_select"])) 
              {
                echo "<tr><td style='color:white'><p style='color:white'>&nbsp;Add User</p><input type='text' name='User' value='";
                echo (isset($_POST['User']) ? $_POST['User'] : '');
                echo "'</input></td></tr>";
		echo "<tr><td><input type='submit' name='submit_user' value='Submit'/></td></tr>";
              }
              ?>
         <?php
             if(isset($_POST['User']))
	     {  
                $user = $_POST['User'];
                $rows = $pdo->prepare("SELECT NAME FROM Users WHERE NAME = :user");
                $rows->execute(array(":user" => $user));
                $rows1 = $rows->fetchALL(PDO::FETCH_ASSOC);

		if(empty($rows1))
	        {
                    $rows = $pdo->prepare("INSERT INTO Users(NAME) VALUES(:user)");
		    $rows->execute(array(":user" => $user));
                    
                    $rows2 = $pdo->prepare("SELECT USID FROM Users WHERE NAME = :user");
                    $rows2->execute(array(":user" => $user));
                    $rows3 = $rows2->fetchALL(PDO::FETCH_ASSOC);
	
		    $_SESSION['USID'] = $rows3['0'];		    
		}
		else
		{
		    $rows2 = $pdo->prepare("SELECT USID FROM Users WHERE NAME = :user");
                    $rows2->execute(array(":user" => $user));
		    $rows3 = $rows2->fetchALL(PDO::FETCH_ASSOC);
		    $_SESSION['USID'] = $rows3['0'];
		}
             }   

	if(isset($_POST['submit_user']))
	{	
            echo "<form action=' ' method='POST' id='Queue' name='Queue'>";

	    echo "<tr><td><p style='color:white'><input type='radio' name='queue' value='free'>Free</input></p></td></tr>";
            if(isset($_POST['queue']) && $_POST['queue'] == 'free'){echo 'checked';};
	   
	    echo "<tr><td><p style='color:white'><input type='radio' name='queue' value='paid'>Paid</input><input type='text' name='amount' /></p></td><td></td></tr>"; 
	    if(isset($_POST['queue']) && $_POST['queue'] == 'paid'){echo 'checked';};
	   
	    echo "<td><input type='submit' name='send' value='Send'/></td>";
	}

	if(isset($_POST['send']))
	{
            if(isset($_POST['queue']))
	    {
                if($_POST['queue'] == 'free')
		{
		    $USID = $_SESSION['USID'];
		    $KID = $_SESSION['KID'];
		    $SID = $_SESSION['SID'];
		    $sql = "INSERT INTO Queues(USID, KID, SID) VALUES(:USID, :KID, :SID)";
	    	    $stmt1 = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));		
		    $stmt1->execute(array(':USID' => $USID['USID'], ':KID' => $KID, ':SID' => $SID));
		    
		    echo "<p style='color:white'>Inserted into the free queue</p>";
                }
                else if($_POST['queue'] == 'paid')
		{
                    $USID = $_SESSION['USID'];
		    $KID = $_SESSION['KID'];
		    $SID = $_SESSION['SID'];
		 
                    $sql = "INSERT INTO PQueues(USID, KID, SID, Amount) VALUES(:USID, :KID, :SID, :Amount)";
	    	    $stmt1 = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));		
		    $stmt1->execute(array(':USID' => $USID['USID'], ':KID' => $KID, ':SID' => $SID,':Amount' => $_POST['amount']));
		    
		    echo "<p style='color:white'>Inserted into the paid queue</p>";
		 }	    
	    }
	    
	}
?>
          </form>
        </form>
      </form>
</body>
</html>