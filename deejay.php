<!--
    Sania Azhar    (z1884677)
    Nikolas Gatov  (z1884744)
    Leo Jaos       (z1911688)
    Olivia Merrell (z1896986)
    Muhammad Naeem (z1906224)
    
    CSCI 466-0001
    Group Project - Karaoke Management System
-->

<html><head><title>DJ by Coffe'n Code</title></head>

<style>

.buttonHolder{ 
  line-height: 30px;
  text-align: center;
}

.buttonHolder2{ 
  text-align: center;
}

.button {
  background-color: LightGray;
  border: none;
  color: black;
  padding: 10px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 8px;
  cursor: pointer;
}

h1 {text-align: center;}
h3 {text-align: center;}

* {
  box-sizing: border-box;
}

.row {
  margin-left:-5px;
  margin-right:-5px;
}
  
.column {
  float: left;
  width: 50%;
  padding: 5px;
}

/* Clearfix (clear floats) */
.row::after {
  content: "";
  clear: both;
  display: table;
}

table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;
}

th, td {
  text-align: left;
  padding: 16px;
}

tr:nth-child(even) {
  background-color: #f2f2f2;
}
</style>

<body><pre>

<h1><b>KARAOKE<br/>VIRTUAL DJ</b></h1>

<?php
error_reporting(E_ALL);
        $username='z1896986';
        $password='2002Jan22';
try { // if something goes wrong, an exception is thrown
	$dsn = "mysql:host=courses;dbname=z1896986";
	$pdo = new PDO($dsn, $username, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$del = "default";
	if(isset($_POST['subject'])) 
		$del = $_POST['subject'];
	
// DELETE ROW 	
	$title = "-";
	$artist;
	$rows1;
	
	if(isset($_POST['title'])) 
		$title = $_POST['title'];
	if(isset($_POST['artist'])) 
		$artist = $_POST['artist'];
	
		
	if($del == "next" || $del == "next1"){
		//priority PQueue 
		if($del == "next"){
		$rs = $pdo->query("SELECT * FROM PQueues LIMIT 1");
		$rows = $rs->fetchALL(PDO::FETCH_ASSOC);
		}
		if($del == "next1"){
		$rs = $pdo->query("SELECT * FROM PQueues ORDER BY AMOUNT DESC LIMIT 1");
		$rows = $rs->fetchALL(PDO::FETCH_ASSOC);
		}
		
		//get title & artist
		if(!empty($rows)){
			$songid = $rows[0]['SID'];
			$rs1 = $pdo->prepare("SELECT TITLE, ARTIST FROM Songs WHERE SID = :songid");
			$rs1->execute(array(":songid" => $songid));
			$rows1 = $rs1->fetchALL(PDO::FETCH_ASSOC);
			$title = $rows1[0]['TITLE'];
			$artist = $rows1[0]['ARTIST'];
		}
		//delete
		if(!empty($rows)){
			$userid = $rows[0]['USID'];
			$kfile = $rows[0]['KID'];
			$rs = $pdo->prepare("DELETE FROM PQueues WHERE USID = :userid AND KID = :kfile");
			$rs->execute(array(":userid" => $userid, ":kfile" => $kfile));
		}
		else {
			//regular Queue
			$rs = $pdo->query("SELECT * FROM Queues LIMIT 1");
			$rows = $rs->fetchALL(PDO::FETCH_ASSOC);
			
			//get title & artist
			if(!empty($rows)){
			$songid = $rows[0]['SID'];
			$rs1 = $pdo->prepare("SELECT TITLE, ARTIST FROM Songs WHERE SID = :songid");
			$rs1->execute(array(":songid" => $songid));
			$rows1 = $rs1->fetchALL(PDO::FETCH_ASSOC);
			$title = $rows1[0]['TITLE'];
			$artist = $rows1[0]['ARTIST'];
		}
			if(!empty($rows)){
			$userid = $rows[0]['USID'];
			$kfile = $rows[0]['KID'];
			$rs = $pdo->prepare("DELETE FROM Queues WHERE USID = :userid AND KID = :kfile");
			$rs->execute(array(":userid" => $userid, ":kfile" => $kfile));
			}
		}
		$sort = "noamount";
	}
	
	
// DRAW TABLE 1
	
	$rs = $pdo->query("SELECT NAME, TITLE, ARTIST, Kfiles.KID FROM Queues, Users, Songs, Kfiles WHERE Users.USID = Queues.USID AND Kfiles.KID = Queues.KID AND Songs.SID = Queues.SID;");
	$rows = $rs->fetchALL(PDO::FETCH_ASSOC);
	
	echo "<div class=\"row\">";
		echo "<div class=\"column\">";
		if(empty($rows)) {echo "<p>No free songs ordered.</p>";}
		else{
		echo "<table>";
		echo "<tr>";
		
		echo "<th>  </th>";
		foreach($rows[0] as $key => $item) {
			echo "<th>$key</th>";
		}
		echo "</tr>";
		
		$count = 1;
		foreach($rows as $row) {
			echo "<tr>";
			echo "<td>$count</td>";
			$count++;
			foreach($row as $key => $item) {
				echo "<td>$item</td>";
			}
			echo "</tr>";
		}
		}
		echo "</table>";
		echo "</div>";
		
// DRAW TABLE 2	
		
		$sort = "default";
		if(isset($_POST['sort'])) 
			$sort = $_POST['sort'];
		
		if($sort == "noamount"){
			$rs = $pdo->query("SELECT NAME, TITLE, ARTIST, AMOUNT, Kfiles.KID FROM PQueues, Users, Songs, Kfiles WHERE Users.USID = PQueues.USID AND Kfiles.KID = PQueues.KID AND Songs.SID = PQueues.SID;");
			$rows = $rs->fetchALL(PDO::FETCH_ASSOC);
			}
		else if($sort == "amount"){
			$rs = $pdo->query("SELECT NAME, TITLE, ARTIST, AMOUNT, Kfiles.KID FROM PQueues, Users, Songs, Kfiles WHERE Users.USID = PQueues.USID AND Kfiles.KID = PQueues.KID AND Songs.SID = PQueues.SID ORDER BY AMOUNT DESC;");
			$rows = $rs->fetchALL(PDO::FETCH_ASSOC);	
			}
		else {
			$rs = $pdo->query("SELECT NAME, TITLE, ARTIST, AMOUNT, Kfiles.KID FROM PQueues, Users, Songs, Kfiles WHERE Users.USID = PQueues.USID AND Kfiles.KID = PQueues.KID AND Songs.SID = PQueues.SID;");
			$rows = $rs->fetchALL(PDO::FETCH_ASSOC);
		}
		
		echo "<div class=\"column\">";
		if(empty($rows)) echo "<p>No paid songs ordered.</p>";
		else{
			echo "<table>";
			echo "<tr>";
			echo "<th>  </th>";
			foreach($rows[0] as $key => $item) {
				echo "<th>$key</th>";
			}
			echo "</tr>";
			
			$count = 1;
			foreach($rows as $row) {
				echo "<tr>";
				echo "<td>$count</td>";
				$count++;
				foreach($row as $key => $item) {
					echo "<td>$item</td>";
				}
				echo "</tr>";
			}
			echo "</table>";
			
	
// ORDER BY AMOUNT BUTTON
			echo "<p>";
			echo " Order by amount paid ";
			echo "<form action=\"\" method=\"post\">";
			if($sort == "noamount"){
				echo "<input type=\"hidden\" name=\"sort\" value=\"amount\"/>";
				if(($sort == "amount" || $sort == "noamount") && $title !="-"){
					echo "<input type=\"hidden\" name=\"title\" value=\"";
					echo $title;
					echo "\">";
					echo "<input type=\"hidden\" name=\"artist\" value=\"";
					echo $artist;
					echo "\">";
				}
				echo "<input type=\"image\" src=\"toggle-off-line.png\" alt=\"tog1\" width=\"34\" height=\"20\" align=\"middle\">";
			}
			else if($sort == "amount"){
				echo "<input type=\"hidden\" name=\"sort\" value=\"noamount\"/>";
				if(($sort == "amount" || $sort == "noamount") && $title !="-"){
					echo "<input type=\"hidden\" name=\"title\" value=\"";
					echo $title;
					echo "\">";
					echo "<input type=\"hidden\" name=\"artist\" value=\"";
					echo $artist;
					echo "\">";
				}
				echo "<input type=\"image\" src=\"toggle-on-line.png\" alt=\"tog2\" width=\"34\" height=\"20\" align=\"middle\">";
			}
			else{
				echo "<input type=\"hidden\" name=\"sort\" value=\"amount\"/>";
				if(($sort == "amount" || $sort == "noamount") && $title !="-"){
					echo "<input type=\"hidden\" name=\"title\" value=\"";
					echo $title;
					echo "\">";
					echo "<input type=\"hidden\" name=\"artist\" value=\"";
					echo $artist;
					echo "\">";
				}
				echo "<input type=\"image\" src=\"toggle-off-line.png\" alt=\"tog1\" width=\"34\" height=\"20\" align=\"middle\">";
			}
			echo "</form>";
			echo "</p>";
		}
		echo "</div>";
	echo "</div>";
		
	
	if($title != "-" && (isset($_POST['title']) || $del == "next" || $del == "next1")){
		echo "<div class=\"buttonHolder\">";
		echo "<h3>";
		echo "  Now playing:  \"";
		echo $title;
		echo "\" | ";
		echo $artist ;
		echo "<img src=\"audio.gif\" alt=\"sound\" width=\"50\" height=\"38\ align=\"middle\">";
		echo "</h3>";
		echo "</div>";
	}
	echo "<br/>";
	
// PLAY/DELETE BUTTON
	echo "<div class=\"buttonHolder2\">";
	if($sort == "noamount"){
		echo "<form action=\"\" method=\"post\">";
		echo "<input type=\"hidden\" name=\"sort\" value=\"noamount\"/>";
		echo "<button class=\"button\" name=\"subject\" type=\"submit\" value=\"next\">Play next song</button>";
		echo "</form>";
	}
	else if($sort == "amount"){
		echo "<form action=\"\" method=\"post\">";
		echo "<input type=\"hidden\" name=\"sort\" value=\"amount\"/>";
		echo "<button class=\"button\" type=\"submit\" name=\"subject\" value=\"next1\">Play next song</button>";
		echo "</form>";
	}
	else{
		echo "<form action=\"\" method=\"post\">";
		echo "<input type=\"hidden\" name=\"sort\" value=\"noamount\"/>";
		echo "<button class=\"button\" type=\"submit\" name=\"subject\" value=\"next\">Play next song</button>";
		echo "</form>";
	}
	echo "</div>";
}
			
	catch(PDOexception $e) { // handle that exception
	echo "Connection to database failed: " . $e->getMessage();
	}
?>	

</pre></body></html>