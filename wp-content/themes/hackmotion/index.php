<?php 

$cta_text = file_get_contents("wp-content/themes/hackmotion/cta-text.txt");

preg_match("/\[[^\]]*\]/", $cta_text, $special_texts);
$special_text = $special_texts[0];

$cta_text = str_replace($special_text, "", $cta_text);

if ($special_text == "[break 80]") {
	$cta_text .= "<br/><span class=\"company-color\">break 80</span>";
} else if ($special_text == "[break 90]") {
	$cta_text .= "<br/><span class=\"company-color\">break 90</span>";
} else if ($special_text == "[break 100]") {
	$cta_text .= "<br/><span class=\"company-color\">break 100</span>";
} else if ($special_text == "[break Par]" && isset($_GET["break"])) {
	$cta_text .= "<br/><span class=\"company-color\">break " . $_GET["break"] . "</span>";
}

?>

<!doctype html>
<html>
<head>
    <title> index </title>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <script>

    window.onload = function() {

		// lietotāja informācija, kuru nav iespējams iegūt caur PHP
		var user_info = {
			"screen-width": screen.width,
			"screen-height": screen.height,
		};
	
		// nosūta to serverim
		const request = new XMLHttpRequest();
		request.open("POST", "wp-json/stats/user-info/", false);
		request.send(JSON.stringify(user_info));
	
	
		// iestata video vizualizācijas tekstus sākuma stāvoklī
        function reset_video_text() {
            document.getElementById("statictop-text").style = "display:none";
            document.getElementById("dynamictop-text").style = "display:none";
            document.getElementById("topfull-text").style = "display:none";

            document.getElementById("statictop-chevron").style = "rotate:90deg";
            document.getElementById("dynamictop-chevron").style = "rotate:90deg";
            document.getElementById("topfull-chevron").style = "rotate:90deg";
        }

        reset_video_text();


        var video = document.getElementById("video-player");

		// video beigās paziņo par to serverim
        video.addEventListener("ended", function() {
			const request = new XMLHttpRequest();
			request.open("POST", "wp-json/stats/video-finish/", false);
			request.send(null);
        });

		// atjaunina video vizualizācijas tekstus, tad kad video tiek atskaņots
        video.addEventListener("timeupdate", function() {
            var progress = video.currentTime / video.duration;
            var precentage = (progress * 100) + "%";

            document.querySelector(':root').style.setProperty('--video-slider-progress', precentage);

            reset_video_text();
            
            if (video.currentTime < 5.0) {
                // te nekas netiek darīts
            } else if (video.currentTime < 14.0) {
                document.getElementById("statictop-text").style = "display:initial";
                document.getElementById("statictop-chevron").style = "rotate:-90deg";
            } else if (video.currentTime < 24.0) {
                document.getElementById("dynamictop-text").style = "display:initial";
                document.getElementById("dynamictop-chevron").style = "rotate:-90deg"
            } else {
                document.getElementById("topfull-text").style = "display:initial";
                document.getElementById("topfull-chevron").style = "rotate:-90deg";
            }
        });
		
		document.getElementById("statictop-link").onclick = function() {
			video.currentTime = 5.0;
			video.play();
        };
		
		document.getElementById("dynamictop-link").onclick = function() {
			video.currentTime = 14.0;
			video.play();
        };
		
		document.getElementById("topfull-link").onclick = function() {
			video.currentTime = 24.0;
			video.play();
        };
    }

    </script>
</head>
<body>
    <div id="title-div">
        <a href="#">
			<img src="<?php echo get_template_directory_uri(); ?>/logo.png"/>
		</a>
    </div>
    <br/>
    <div id="content-div">
        <div id="top-content-div">
            <div id="top-content-left-div">
                <h1><?php echo $cta_text; ?></h1>
                <h2>Pack includes:</h2>
                <div id="feature-list">
                    <h3>Swing Analyzer -  Hackmotion Core</h3>
                    <h3>Drills by coach Tyler Ferrell</h3>
                    <h3>Game improvement plan by HackMotion</h3>
                </div>
				<a href="#">
					<div id="cta-button">
						Start Now &rarr;
					</div>
				</a>
            </div>
            <div id="top-content-right-div">
                <img src="<?php echo get_template_directory_uri(); ?>/improvement-graph.png"/>
                <img class="bottom only-pc" src="<?php echo get_template_directory_uri(); ?>/improvement-combined.png"/>

                <img class="only-mobile" src="<?php echo get_template_directory_uri(); ?>/improvement-1.png"/>
                <img class="only-mobile" src="<?php echo get_template_directory_uri(); ?>/improvement-2.png"/>
            </div>
        </div>
        <h1 id="large-title">The best solution for you: Impact Training Program</h1>
        <div id="bottom-content-div">
            <div id="bottom-content-left-div">
                <video id="video-player" controls>
                    <source src="<?php echo get_template_directory_uri(); ?>/Impact-Drill.mp4" type="video/mp4">
                    Video unavailable.
                  </video> 
            </div>
            <div id="bottom-content-middle-div">
                <div id="video-slider"><div></div></div>
            </div>
            <div id="bottom-content-right-div">
                <a id="statictop-link" href="javascript:void(0);"><h3><span id="statictop-chevron">&lsaquo;</span>  Static top drill</h3></a>
                <div><p id="statictop-text">Get a feel for the optimal wrist position at Top of your swing</p></div>
                <a id="dynamictop-link" href="javascript:void(0);"><h3><span id="dynamictop-chevron">&lsaquo;</span>  Dynamic top drill</h3></a>
                <div><p id="dynamictop-text">Dynamically train your wrist position at Top</p></div>
                <a id="topfull-link" href="javascript:void(0);"><h3><span id="topfull-chevron">&lsaquo;</span>  Top full swing challenge</h3></a>
                <div><p id="topfull-text">Train your maximumm power swing</p></div>
            </div>
        </div>
    </div>

    <div id="footer">
        Copyright &copy; HackMotion | All Rights Reserved
    </div>
</body>
</html>