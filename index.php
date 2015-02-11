<?php require_once 'db_connection.php'; ?>
<?php require_once 'functions.php'; ?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/pure-min.css">
	<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/grids-responsive-min.css">
	<link rel="stylesheet" href="css/layouts/marketing.css">
	<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">

	<meta charset="UTF-8" />
	<title>Twitter Sentiment Analysis </title>
	<script src="Chart.js"></script>
	<style>
		body{
			padding: 0;
			margin: 0;
		}
		#canvas-holder{
			width:30%;
		}
	</style>


</head>

<body>

	<div class="header">
		<div class="home-menu pure-menu pure-menu-open pure-menu-horizontal pure-menu-fixed">
			<a class="pure-menu-heading" href="">COMP 30440 - Colm O' Reilly</a>
		</div>
	</div>

	<div class="splash-container">
		<div class="splash">
			<h1 class="splash-head">Twitter Sentiment Analysis</h1>
			<p class="splash-subhead">

			</p>
			<p>
				<!-- <a href="http://purecss.io" class="pure-button pure-button-primary">Get Started</a> -->
			</p>
		</div>
	</div>

	<div class="content-wrapper">
		<div class="content">
			<h2 class="content-head is-center">Type your keyword and select how many Tweets to analyse:</h2>

			<div class="pure-g">
				<div class="l-box pure-u-1-1">

					<form method="POST" class="pure-form pure-form-stacked">
						<fieldset>
							<label>Keyword: </label> <input type="text" name="q" placeholder="e.g. Scotland"/>
							<label>Number of Tweets:</label>
							<select name="num_tweets">
								<option value="20" selected>20</option>
								<option value="40">40</option>
								<option value="60">60</option>
								<option value="80">80</option>
							</select>

							<label>Select a classifier:</label>
							Alchemy (positive | negative | neutral)<input type="radio" name="classifier" value="alchemy"><br>
							Custom Naive Bayes (positive | negative)<input type="radio" name="classifier" value="custom">
						</fieldset>
						<input type="submit" /> <br>
					</form>				
				</div>
			</div>
		</div>

		<div class="content">
		<div id="list" class="pure-u-1">



			<?php
			require_once 'alchemyapi.php';
			require_once 'Sentiment1.php';
			$custom_sent = new Sentiment1();
			$custom_sent -> train(19000);
			$alchemyapi = new AlchemyAPI();


			/*use TwitterAPIExchange for handling calls */
			ini_set('display_errors', 1);
			require_once('TwitterAPIExchange.php');

			/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
			$oauth_file = file_get_contents("oauthkeys.txt");
			$oauth_arr = explode("\n", $oauth_file);

			$settings = array(
				'oauth_access_token' => $oauth_arr[0],
				'oauth_access_token_secret' => $oauth_arr[1],
				'consumer_key' => $oauth_arr[2],
				'consumer_secret' => $oauth_arr[3]
				);
	//echo $_POST['classifier'];

			if ( isset($_POST['q'])){
		//$query = 'monday';
				$url = 'https://api.twitter.com/1.1/search/tweets.json';
				$getfield = '?lang=en&count=' . $_POST['num_tweets'] . '&q=' . $_POST['q'] . '-filter:retweets';
				$requestMethod = 'GET';
				$twitter = new TwitterAPIExchange($settings);
				$tweets = json_decode($twitter->setGetfield($getfield)
					->buildOauth($url, $requestMethod)
					->performRequest());


				$pos_count=0;
				$neg_count=0;
				$neu_count=0;
				$cust_pos=0;
				$cust_neg=0;


				foreach ($tweets -> statuses as $t) {
					// foreach ($tweet as $t){
				//calculate the sentiment of each tweet
						if ($t -> text != ''){
							if($_POST['classifier']=='alchemy'){
								$response = $alchemyapi -> sentiment('text', $t -> text , null);				
								display_tweet($t, $response);
								// store_tweet_db($_POST['q'], $t->text, $response['docSentiment']['type'],$response);

								if ($response['docSentiment']['type']=='neutral'){
									$neu_count++;
								}
								if ($response['docSentiment']['type']=='positive'){
									$pos_count++;
								}
								if ($response['docSentiment']['type']=='negative'){
									$neg_count++;
								}
							} else {
								$sentiment = $custom_sent ->classify($t->text);
								display_custom($t,$sentiment);
								if ($sentiment=='pos'){
									$cust_pos++;
								} else {
									$cust_neg++;
								}
							}											
						} 
					}
				}
		/*if($_POST['classifier']=='alchemy'){
			echo 'positives: ' . $pos_count . '<br>';
			echo 'negatives: ' . $neg_count . '<br>';
			echo 'neutrals: ' . $neu_count . '<br>';
		}*/
			// }	
			?>

		</div>
<!-- <div class="content-wrapper"> -->
	<!-- <div class="content"> -->
		<?php if(isset($_POST['q'])){
			echo '<h2 class="content-head is-center content">Here are the results for ' . $_POST['q'] . '</h2>';
		}?>
		<!-- <div class="pure-g content"> -->
			<div id="canvas-holder" class="content">
				<canvas id="chart-area" width="500" height="500"></canvas>
			</div>
			<script>

				var doughnutData = [
				{
					value: <?php echo ($_POST['classifier']=='alchemy' ? $neg_count : $cust_neg); ?>,
					color:"#F7464A",
					highlight: "#FF5A5E",
					label: "Negative"
				},
				{
					value: <?php echo ($_POST['classifier']=='alchemy' ? $pos_count : $cust_pos); ?>,
					color: "#46BFBD",
					highlight: "#5AD3D1",
					label: "Positive"
				},
				{
					value: <?php echo $neu_count; ?>,
					color: "#949FB1",
					highlight: "#A8B3C5",
					label: "Neutral"
				}


				];

				window.onload = function(){
					var ctx = document.getElementById("chart-area").getContext("2d");
					window.myDoughnut = new Chart(ctx).Doughnut(doughnutData, {responsive : true});
				};
			</script>
			<!-- </div> -->
		</div>
	</div>
</div>
</div>
</body>
</html>
<?php
	//close db connection
if (isset($connection)) {
	mysqli_close($connection);
}
?> 