<?php
include './CMS/include/database_config.php';
$l_footer = $lang->term_footer();
?>

<div id="fotter">
  <div id="fotter960">
    <div class="fotterLeft">

      <?php
      echo "<div class=\"title\">".$l_footer[$curlang]["join_us"]."</div>\n";
      ?>

      <div class="iconWeb">
        <ul>
          <li><a href=<?php echo "\"".$l_footer[$curlang]["fb_link"]."\""; ?> rel="nofollow" target="_blank"><img src="/img/icon_FaceBook.png" alt="Facebook" /></a></li>
          <li><a href="https://twitter.com/OIzoioi1" rel="nofollow" target="_blank"><img src="/img/icon_ Twister.png" alt="Twitter" /></a></li>
          <li><a href="/blog/" target="_blank"><img src="/index_images/ico5.png" alt="Blog" /></a></li>
          <li><a href=<?php echo "\"".$l_footer[$curlang]["gplus_link"]."\""; ?> rel="nofollow" target="_blank"><img src="/img/icon_Google.png" alt="Google Plus" /></a></li>
        </ul>
      </div>
      <div class="iconWeb1">
        <!-- <ul>
          <li><a href="#"><img src="/img/icon_Zing.png" alt="Zing Me" /> </a></li>
        </ul> -->
      </div>

    </div>
    <div class="fotterRight">

      <?php
      echo "<div class=\"title\">".$l_footer[$curlang]["product_categories"]."</div>\n";
      ?>

      <div class="menuProductsFotter">

        <?php
        echo "<ul class=\"fist\"><li><a href=\"/".strtolower(remove_sign($l_footer[$curlang]["mobile"]))."\">".$l_footer[$curlang]["mobile"]."</a></li>\n";
        echo "<li><a href=\"/".strtolower(remove_sign($l_footer[$curlang]["tablet"]))."\">".$l_footer[$curlang]["tablet"]."</a></li>\n";
        echo "<li><a href=\"/".strtolower(remove_sign($l_footer[$curlang]["computer"]))."\">".$l_footer[$curlang]["computer"]."</a></li>\n";
        echo "</ul>\n";
        echo "<ul><li><a href=\"/".strtolower(remove_sign($l_footer[$curlang]["camera"]))."\">".$l_footer[$curlang]["camera"]."</a></li>\n";
        echo "<li><a href=\"/".strtolower(remove_sign($l_footer[$curlang]["tv"]))."\">".$l_footer[$curlang]["tv"]."</a></li>\n";
        echo "<li><a href=\"/".strtolower(remove_sign($l_footer[$curlang]["audio"]))."\">".$l_footer[$curlang]["audio"]."</a></li>\n";
        echo "</ul>\n";
        echo "<ul>\n";
        echo "<li><a href=\"/".strtolower(remove_sign($l_footer[$curlang]["accessory"]))."\">".$l_footer[$curlang]["accessory"]."</a></li>\n";
        echo "<li><a href=\"/".strtolower(remove_sign($l_footer[$curlang]["women_clothing"]))."\">".$l_footer[$curlang]["women_clothing"]."</a></li>\n";
        echo "<li><a href=\"/".strtolower(remove_sign($l_footer[$curlang]["men_clothing"]))."\">".$l_footer[$curlang]["men_clothing"]."</a></li>\n";
        echo "</ul>\n";
        ?>

      </div>     
    </div>
    <div class="clear"></div>
    <div class="lastFotter">
      <div class="menuContactUs" >
        <ul>

          <?php
          echo "<li class=\"fist\"><a href=\"/contact.html\">".$l_footer[$curlang]["contact"]."</a></li>\n";
          echo "<li><a href=\"/privacy_policy.html\">".$l_footer[$curlang]["privacy_policy"]."</a></li>\n";
          echo "<li><a href=\"/term_condition.html\">".$l_footer[$curlang]["term_condition"]."</a></li>\n"; 
          ?>

        </ul>
      </div>
      <div class="copyRight">© 2012 Pricepony. All Rights Reserved </div>

    </div>
  </div>
</div>
<!-- Register popup -->
<!-- <script src="./js/javascript.js"></script> -->