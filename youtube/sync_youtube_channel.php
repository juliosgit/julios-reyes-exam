<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>hello</title>
  </head>
  <!-- todo:
  Save YouTube channel information (profile picture, name and description) in youtube_channels table
  Save 100 latest videos (video link, title, description, thumbnail) in youtube_channel_videos table

  nba - UCWJ2lWNubArHWmf3FIHbfcQ
  pewds - UC-lHJZR3Gqxm24_Vd_AJ5Yw
  -->


  <body>
    <?php
    $api_key = "AIzaSyASdShdK2seosUlVBwiwear0MMsrBdTsII";
    $base_url_channelInfo = "https://www.googleapis.com/youtube/v3/channels";
    $base_url_videoList = "https://www.googleapis.com/youtube/v3/";
    $channelId = "UCWJ2lWNubArHWmf3FIHbfcQ";
    $maxresult = 10;
    $nxtPgtoken = '';


    $api_url_videoList = $base_url_videoList . "search?order=date&part=snippet&channelId=" . $channelId . "&maxResults=" . $maxresult . "&pageToken=" . $nxtPgtoken . "&key=" . $api_key . "";
    $api_url_channelInfo = $base_url_channelInfo . "?part=snippet&id=" . $channelId . "&maxResults=1&key=" . $api_key . "";

    $videolist = json_decode(file_get_contents($api_url_videoList));
    $channelInfo = json_decode(file_get_contents($api_url_channelInfo));

    foreach ($channelInfo->items as $channel) {


      $chaname = $channel->snippet->title;
      $propicurl = $channel->snippet->thumbnails->default->url;
      $descript = $channel->snippet->description;

    }


    ?>
    <link rel="stylesheet" type="text/css" href="styles.css" />

    <div class="ChaInfo">
      <h1>Channel Info</h1>

      <h2>Channel Name:
        <?php echo $chaname ?> <img src="<?php echo $propicurl ?>" alt="">
      </h2>

      <h2>Description:
        <?php echo $descript ?>
      </h2>

      <?php
      if (array_key_exists('savebtn', $_POST)) {
        $conn = new PDO('mysql:host=localhost;dbname=youtube_db', 'root', '');
        if ($conn)
          echo 'Channel info saved to database. ';
        foreach ($channelInfo->items as $channel) {

          $sql = "INSERT INTO `youtube_channels` (`id`, `profilePicUrl`, `channelName`, `desciption`) 
    VALUES (NULL,:propic,:chaname,:descrip)";

          $stmt = $conn->prepare($sql);
          $stmt->bindParam(":propic", $channel->snippet->thumbnails->default->url);
          $stmt->bindParam(":chaname", $channel->snippet->title);
          $stmt->bindParam(":descrip", $channel->snippet->description);

          $stmt->execute();
        }

        if ($conn)
          $counter1 = 1;
        $nxtPgtoken = "";
        while ($counter1 <= 10) {
          $api_url_videoList = $base_url_videoList . "search?order=date&part=snippet&channelId=" . $channelId . "&maxResults=" . $maxresult . "&pageToken=" . $nxtPgtoken . "&key=" . $api_key . "";
          $videolist = json_decode(file_get_contents($api_url_videoList));

          $nxtPgtoken = $videolist->nextPageToken;
          $counter1++;

          foreach ($videolist->items as $videos) {
            if ($videos->id->kind == 'youtube#video') {
              $sql = "INSERT INTO `youtube_channel_videos` (`id`, `videoLink`, `title`, `desciption`, `thumbnailUrl`) 
          VALUES (NULL,:vlink,:vtitle,:vdescrip,:vthumb);";

              $stmt = $conn->prepare($sql);
              $stmt->bindParam(":vlink", $videos->id->videoId);
              $stmt->bindParam(":vtitle", $videos->snippet->title);
              $stmt->bindParam(":vdescrip", $videos->snippet->description);
              $stmt->bindParam(":vthumb", $videos->snippet->thumbnails->medium->url);

              $stmt->execute();
            }
          }
        }
        echo 'Videolist saved to database. ';

      }

      ?>
      <form method="post">
        <input type="submit" name="savebtn" value="SAVE">
      </form>

    </div>






    <?php
    $counter = 1;
    $nxtPgtoken = "";
    while ($counter <= 10) {

      $api_url_videoList = $base_url_videoList . "search?order=date&part=snippet&channelId=" . $channelId . "&maxResults=" . $maxresult . "&pageToken=" . $nxtPgtoken . "&key=" . $api_key . "";
      $videolist = json_decode(file_get_contents($api_url_videoList));

      $nxtPgtoken = $videolist->nextPageToken;
      $counter++;


      foreach ($videolist->items as $vidlist) {

        if ($vidlist->id->kind == 'youtube#video') {
          $vidThumbnail = $vidlist->snippet->thumbnails->medium->url;
          $vidtitle = $vidlist->snippet->title;
          ?>

          <div class="videoFeed">
            <center><img src="<?php echo $vidThumbnail ?>" alt=""></center>
            <?php echo $vidtitle ?>

          </div>

          <?php
        }
      }


    }

    ?>













    <?php
    /*foreach($youtube_videos_arr['items'] as $ytvideo){
        if($ytvideo['id']['kind']  == 'youtube#video'){
        ?>
            <tr><td><img src="<?=$ytvideo['snippet']['thumbnails']['high']['url']?>" /><td><?=$ytvideo['snippet']['title']?></td></tr>
        <?php
        }
    }*/
    ?>

  </body>

</html>