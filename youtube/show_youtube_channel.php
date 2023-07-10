<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube</title>
    <link rel="stylesheet" href="styles.css">
  </head>


  <body class="page">



    <div class="chainfo">

      <?php
      $con1 = new PDO('mysql:host=localhost;dbname=youtube_db', 'root', '');
      if (!$con) {
        echo 'connection error' . mysqli_connect_error();
      }
      $query1 = "SELECT * FROM youtube_channels";
      $data1 = $con1->query($query1);

      foreach ($data1 as $chanfo) {
        $propic = $chanfo["profilePicUrl"];
        $chatitle = $chanfo["channelName"];
        $chadescript = $chanfo["desciption"];
        ?>

        <div class="image">
          <img class="propic" src="<?php echo $propic ?>" alt="image here">
        </div>
        <h2 class="chanfo">
          <?php echo $chatitle ?>
        </h2>
        <div class="description">
          <p>
            <?php echo $chadescript ?>
          </p>
        </div>

        <?php

      }
      ?>





      <?php
      $con = new PDO('mysql:host=localhost;dbname=youtube_db', 'root', '');
      if (!$con) {
        echo 'connection error' . mysqli_connect_error();
      }
      $query = "SELECT * FROM youtube_channel_videos";
      $data = $con->query($query);

      foreach ($data as $vidlist) {
        $imgurlDB = $vidlist["thumbnailUrl"];
        $titlefromDB = $vidlist["title"];
        ?>

        <div class="videoFeed">
          <center><img src="<?php echo $imgurlDB ?>" alt=""></center>

          <?php echo $titlefromDB ?>

        </div>

        <?php

      }
      ?>


  </body>

</html>