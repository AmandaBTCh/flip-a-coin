<html><head><Title>Coin Flip</title>
<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.js"></script>
        <script type="text/javascript">
                $(document).ready(function() {
                        $('input[type="text"]').addClass("idleField");
                $('input[type="text"]').focus(function() {
                        $(this).removeClass("idleField").addClass("focusField");
                    if (this.value == this.defaultValue){
                        this.value = '';
                                }
                                if(this.value != this.defaultValue){
                                this.select();
                        }
                });
                $('input[type="text"]').blur(function() {
                        $(this).removeClass("focusField").addClass("idleField");
                    if ($.trim(this.value) == ''){
                                this.value = (this.defaultValue ? this.defaultValue : '');
                                }
                });
                });
        </script>
<style type="text/css">
    *{
        margin:0;
        padding:0;
    }
    body {
        padding: 10px;
        background-color: #8EC1DA;
        background-image:url(../wask/headerback.png);
        background-repeat:repeat-x;
        text-align: center;
        font-family: arial;
    }
    div {
        text-align: center;
    }
    h1{
	padding: 0px;
        font-size:25px;
        color: #ffe;
    }
    #status{
        width:50%;
        padding:10px;
        height:42px;
        outline:none;
    }
    .focusField{
        border:solid 2px #73A6FF;
        background:#EFF5FF;
        color:#000;
    }
    .idleField{
        background:#EEE;
        color: #6F6F6F;
        border: solid 2px #DFDFDF;
    }
    #replacement {
  	width: 100px;
  	height: 70px;
  	margin: 0;
  	padding: 0;
  	border: 0;
  	background: transparent url(coin.jpg) no-repeat center top;
  	text-indent: -1000em;
  	cursor: pointer; /* hand-shaped cursor */
  	cursor: hand; /* for IE 5.x */
   }
</style>
<link rel="icon" 
      type="image/ico" 
      href="favicon.ico" />
</head><body style='text-align:right;width:400px;margin:auto;padding:0 25% 0 0'><h1>C<img src="../flipacoin/coin.jpg" / style='margin:5px 0 0 0;'>iN FLiP</h1><br /><br />
<?php
$side=0;
$reason="";
$outcome=0;
if (!empty($_GET)){
  	$side = intval(check_input($_GET['side']));
        $reason = (check_input($_GET['reason']));
        $outcome = rand(1,2);
        if (($side==$outcome) && ($side==1))
                {
                	echo "And it's ...";
                	//sleep(1);
                	echo "<h1>HEADS!</h1>  You Win the Flip!";
                }
        else if (($side==$outcome) && ($side==2))
                {
                	echo "And it's ...";
               		// sleep(1);
                	echo "<h1>TAILS!</h1>  You Win the Flip!";
		}
	else if (($side!=$outcome) && ($side==2))
                {
                	echo "And it's ...<br />";
                	//sleep(1);
                	echo "<b style='font-size:24px' >heads.</b><br />  Sorry, you lose the flip.";
                }
        else if (($side!=$outcome) && ($side==1))
                {
	                echo "And it's ...<br />";
        	        //sleep(1);
                	echo "<b style='font-size:24px' >tails.</b><br />  Sorry, you lose the flip.";
                }
        else {
	                echo "And it's ...";
        	        //sleep(1);
                	echo "what the?  Hrm.. this is strange. <br />It seems like the coin landed on it's edge!  <br />FLIP AGAIN please.";
              }
	echo "<br /><br /><br /><FORM><INPUT TYPE='button' VALUE='Back' style='padding:2px;' onClick='history.go(-1);return true;'></FORM>";

	$con = mysql_connect("localhost","root","");
        if (!$con)
        {
                die('Could not connect: ' . mysql_error());
        }
	else
	{
        	mysql_select_db("coinflip", $con);
		$Tod = strval(date('D M j Y, G:i:s'));
        	$ipaddress=$_SERVER["REMOTE_ADDR"];
		$strr=strlen($reason);
		if (($strr < 1 ) || ($strr > 120)){$reason="";}
		if (($side<1) || ($side>2)){$side=0;}
		mysql_query("INSERT INTO results (prediction, result, reason, date, ipaddress) VALUES ('$side', '$outcome', '$reason', '$Tod', '$ipaddress')");
		$output = mysql_query("SELECT prediction, result, reason FROM results ORDER BY id DESC");
		if (!$output) {
 		   die('Invalid query: ' . mysql_error());
		}
		mysql_close($con);
		$totalflips=0;
		$totalheads=0;
		$totaltails=0;
		$win='';
		$last=0;
		$last1=0;
		$last11=0;
		$last2=0;
		$last22=0;
		$winrate=0;
		$code="";
		$top5=0;
		while ($row = mysql_fetch_array($output, MYSQL_NUM)) {
			if ($row[1]==1)
			{
				$totalheads+=1;
				if ($last==1)
				{
					$last1+=1;
					if ($last1>$last11)
					{
						$last11=$last1;
					}
				}
				else
				{
					$last=1;
					$last1=1;
				}
			}
			else if ($row[1]==2)
			{
				$totaltails+=1;
				if ($last==2)
				{
                                        $last2+=1;
                                        if ($last2>$last22)
					{
						$last22=$last2;
					}
                                }
                                else
				{
					$last=2;
					$last2=1;
				}
				if ($row[1]==$row[0])
					{$winrate+=1;}
			}
                        if ($row[1]==$row[0]) {$winrate+=1;$win="(won)";}
			else {$win="(lost)";}
			if (($row[2]) && ($top5<7))
			{
				$code.="<br />- ".$row[2]." ".$win."<br />";
				$top5+=1;
			}
		}
		echo "<Br /><br /><b>Statistics:</b><br /><br />";
		echo "total flips: ".($totalheads+$totaltails)."<br />";
		echo "total heads: ".$totalheads." (".floor($totalheads*100/($totalheads+$totaltails))."%)<br />";
		echo "total tails: ".$totaltails." (".floor($totaltails*100/($totalheads+$totaltails))."%)<br />";
		echo "longest tail streak: ".$last22."<br />";
                echo "longest heads streak: ".$last11."<br />";
		echo "win rate: ".floor($winrate*100/($totalheads+$totaltails))."%<br /><br />";
		echo "<div style='text-align:left'><b>The last 7 reasons someone flipped a coin:</b><br /><i>".$code."</i></div>";
		mysql_free_result($output);
	}

}
else
{
	echo "<div style='padding:0 200px 0 0;text-align:right;'><b>Pick Heads or Tails:</b>";
	echo "<form>Heads <input type='radio' value='1' name='side' CHECKED /><br />";
	echo "Tails <input type='radio' value='2' name='side' /></div>";
	echo "<br /><div style='padding:0 70px 0 0'>";
	echo "<b>Briefly, what are you flipping for?</b> <i>(Optional)</i></div>";
	echo "<input type='text' value='' size='50' maxlength='100'  name='reason' />";
	echo "<br /><br /><input type='submit' value='FLIP THE COIN' style='padding:10px;font-size:22px;' /><br /><input type='reset' style='padding:2px;' value='reset' />";
	echo "</form>";
}
function check_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    //$data = htmlentities($data);
    $data = mysql_real_escape_string($data);

    return $data;
}
echo "</body></html>";
die();
?>
