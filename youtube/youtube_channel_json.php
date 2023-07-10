<!DOCTYPE html>
<html lang="en">


  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>json feed</title>
  </head>

  <!--Contents of this JSON feed are the YouTube channel information and list of videos.-->

  <body>






    <?php
    $api_key = "AIzaSyASdShdK2seosUlVBwiwear0MMsrBdTsII";
    $base_url_channelInfo = "https://www.googleapis.com/youtube/v3/channels";
    $base_url_videoList = "https://www.googleapis.com/youtube/v3/";
    $channelId = "UCWJ2lWNubArHWmf3FIHbfcQ";
    $maxresult = 10;


    $api_url_channelInfo = $base_url_channelInfo . "?part=snippet&id=" . $channelId . "&maxResults=1&key=" . $api_key . "";
    $channelInfo = json_decode(file_get_contents($api_url_channelInfo));
    ?>
    <div>
      <h1>Channel Info</h1>
      <?php
      echo "<pre>";
      print_r($channelInfo);
      ?>
    </div>
    <div>
      <h1>Video list</h1>
    </div>
    <?php
    $counter = 1;
    $nxtPgtoken = '';
    while ($counter <= 10) {

      $api_url_videoList = $base_url_videoList . "search?order=date&part=snippet&channelId=" . $channelId . "&maxResults=" . $maxresult . "&pageToken=" . $nxtPgtoken . "&key=" . $api_key . "";
      $videolist = json_decode(file_get_contents($api_url_videoList));
      echo "<pre>";
      print_r($videolist);
      $nxtPgtoken = $videolist->nextPageToken;
      $counter++;
    }

    ?>

  </body>

</html>