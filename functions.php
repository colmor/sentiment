<?php
	function mysql_esc($string){
		global $connection;
		$escaped = mysqli_real_escape_string($connection,$string);
		return $escaped;			
	}

	function confirm_query($resultset) {
		if (!$resultset){
			die("Database query failed.");
		}
	}


	function display_tweet($t, $response){
		echo '<div class="email-item pure-g">';
		echo '<div class="pure-u-1-5">';
		echo '<img class="email-avatar" src="' . $t -> user -> profile_image_url .'" alt="profile image" />';
		echo '</div>'; //close pure u
		echo '<div class="pure-u-4-5">';
		echo '<h4 class="email-subject"> sentiment: '. $response['docSentiment']['type'] . '</h4>';
		echo '<h5 class="email-name">' . $t -> user -> screen_name . '</h5>';
		echo '<p class="email-desc">' . $t -> text . '</p>';
		echo '</div>'; //close pure u 3 4
		//echo $t-> id . '<br>';
		
	    //echo 'score: '. $response['docSentiment']['score'];
	    echo '</div>';//close email item pure g
	   // return $display_string;
	}

	function display_custom($t, $response){
		echo '<div class="email-item pure-g">';
		echo '<div class="pure-u-1-5">';
		echo '<img class="email-avatar" src="' . $t -> user -> profile_image_url .'" alt="profile image" />';
		echo '</div>'; //close pure u
		echo '<div class="pure-u-4-5">';
		echo '<h4 class="email-subject"> sentiment: '. $response . '</h4>';
		echo '<h5 class="email-name">' . $t -> user -> screen_name . '</h5>';
		echo '<p class="email-desc">' . $t -> text . '</p>';
		echo '</div>'; //close pure u 3 4
		
	    //echo 'score: '. $response['docSentiment']['score'];
	    echo '</div>';//close email item pure g
	   // return $display_string;
	}

	function store_tweet_db($db_search,$db_content,$db_sent,$response){
		global $connection;
		$db_search = mysql_esc($db_search);
		$db_content = mysql_esc($db_content);
		$db_sent = mysql_esc($db_sent);
		$query = "INSERT INTO tweets (";
		$query .= "search_term,content,sentiment,sentistrength";
		$query .= ") VALUES (";
		$query .= "'{$db_search}','{$db_content}','{$db_sent}','{$response['docSentiment']['score']}'";
		$query .= ")";		
		$db_result = mysqli_query($connection,$query);
		confirm_query($db_result);
	}



/*global $connection;
	$query = "INSERT INTO tweets (";
	$query .= "search_term,content,sentiment,sentistrength";
	$query .= ") VALUES (";
	$query .= "'{$db_search}','{$db_content}','{$db_sent}','{$response['docSentiment']['score']}'";
	$query .= ")";		
	$db_result = mysqli_query($connection,$query);
	confirm_query($db_result);	*/

?>