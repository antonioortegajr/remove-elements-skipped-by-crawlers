
<?php

//copy above the input
$copy_above_form = "<p>Websites have two kinds of visitors. Humans like you and me and robots crawling the site to help us humans find what we are looking for.</p>
<p>The goal of this page is to illiastrate the differences between how humans see web pages and how a bot would \"see\" the same page.</p>";

//url to cURL
$url = htmlspecialchars($_POST["f"]);

//look for indicator of ajax-crawling
if (str_contains($url, "!#") !=== FALSE){
  $url = str_replace("!#", "?_escaped_fragment_=", $url);
}

$out = NULL;
if($url != NULL){
  //cURL the provided url if a url was provided
   $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $data = curl_exec($ch);
  curl_close($ch);

  //find the elements skipped by crawlwers in the returned html
  $patterns = $patterns = array();
  $patterns[0] = "/<iframe(.*)<\/iframe>/U";
  $patterns[1] = "/<script(.*)<\/script>/U";
  $patterns[2] = "/<frameset(.*)<\/frameset>/U";
  $patterns[3] = "/<object(.*)<\/object>/U";
  $patterns[4] = "/<embed(.*)<\/embed>/U";
  $replacements = array();
  $replacements[0] = "<br><br>iframes contents not indexed as part of this site<br><br>";
  $replacements[1] = "<br>There is some scripting here, but javascripting is not run entirely by crawlers<br>";
  $replacements[2] = "<br>frameset contents not indexed as part of this site<br>";
  $replacements[3] = "<br>object contents not indexed as part of this site<br>";
  $replacements[4] = "<br>embed contents not indexed as part of this site<br>";
  //find and replace all above elements
  $data = preg_replace($patterns, $replacements, $data);
  //show message and render edited html
  echo "Below is this page ".$url. " seen through robot eyes<br><hr>";
  var_dump($data);
}
else{
  include_once("html/top.html");
  echo $copy_above_form;
  //form to enter url
  echo '<form action="" method="post">
          See pages more like a robot does: <input id="url-in" type="text" name="f" placeholder="Enter a page"><br>
          <input id="sub" type="submit">
        </form>';
        include_once("html/footer.html");

}

?>
