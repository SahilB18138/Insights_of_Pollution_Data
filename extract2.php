<?php
if($_POST['from'] && $_POST["to"]) {

    //$email_to = "sahilgarg2006.sg@gmail.com";
    //$email_subject = "Assignment 2";
 
    function died($error) {
        echo "Sorry, there is error!!<br /> ";
        //echo "These errors appear below.<br /><br />";
        echo $error."<br /><br />";
        //echo "Please go back and fix these errors.<br /><br />";
        die();
    }
 
    $par = $_POST['par']; // required
    $choices = $_POST['choices']; // not required
    //$comments = $_POST['comments']; // required
    $from_time=$_POST["from"];

	$to_time=$_POST["to"];



	//from analysis

	$from_date_timestamp = strtotime($from_time);
    $from_day = date("d", $from_date_timestamp);
    $from_month = date("m", $from_date_timestamp);
    $from_year = date("Y", $from_date_timestamp);
    $from_time=$from_year.'-'.$from_month.'-'.$from_day;

	//echo $from_time;



	//to analysis

	$to_date_timestamp = strtotime($to_time);
    $to_day = strval(date("d", $to_date_timestamp));
    $to_month =strval(date("m", $to_date_timestamp));
    $to_year = strval(date("Y", $to_date_timestamp));
    $to_time=$to_year.'-'.$to_month.'-'.$to_day;

	//echo $to_time;
 
    //$error_message = "";
    //Assigning expected pattern for email
    if(empty($choices))
    {
        echo("You didn't select any of the choices.");
    }
    else
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "pollution";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sql = "SELECT created_at as a,$par as b FROM pollution_data WHERE created_at >='".$from_time."' AND created_at <='".$to_time."' ORDER BY a" ;
        $result = mysqli_query($conn, $sql);
        $attr = [];
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                //echo  $row["a"]." => ". $row["b"]. "<br>";
                array_push($attr,array("y" => $row["b"], "label" => $row["a"])); 
            }
        }
        $N = count($choices);
        for($i=0; $i < $N; $i++)
        {
            $choice = $choices[$i];
            if($choice == "Maximum"){
                $sql = "SELECT Max($par) as max FROM pollution_data WHERE created_at >='".$from_time."' AND created_at <='".$to_time."'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                // output data of each row
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "Max ".$par." is : ". $row["max"]. "<br>";
                    }
                } 
            }
            if($choice == "Minimum"){
                $sql = "SELECT MIN($par) as min FROM pollution_data WHERE created_at >='".$from_time."' AND created_at <='".$to_time."'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                // output data of each row
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "Min ".$par." is : ". $row["min"]. "<br>";
                    }
                } 
            }
            if($choice == "Average"){
                $sql = "SELECT AVG($par) as avg FROM pollution_data WHERE created_at >='".$from_time."' AND created_at <='".$to_time."'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                // output data of each row
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "Avg ".$par." is : ". $row["avg"]. "<br>";
                    }
                } 
            }
        }


        mysqli_close($conn);
    }

    
?>
 
 <!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	title: {
		text: "Values of attribute over given time"
	},
	axisY: {
		title: "Attribute value"
	},
    axisX: {
		title: "Year-Month-Date"
	},
	data: [{
		type: "line",
		dataPoints: <?php echo json_encode($attr, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>   

 
<?php
 
}
?>