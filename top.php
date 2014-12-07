<?php
  $l_top = $lang->term_top();

  echo "<div id=\"logo\"> <a href=\"/\"><img src=\"/img/logo_Giare.png\" alt=\"Pricepony Price Comparison\"/ width=\"187\" height=\"65\"></a> </div>\n";
  echo "<div id=\"slogan\">".$l_top[$curlang]["slogan"]."</div>\n";
  echo "<div id=\"menu_Top\">\n";
  echo "<ul>\n";
  echo "<li><a onmouseover=\"detect_active(event);\" onmouseout=\"remove_active(event);\" id=\"home\" class=\"active\" href=\"/\">".$l_top[$curlang]["homepage"]."</a></li>\n";
  echo "<li><a onmouseover=\"detect_active(event);\" onmouseout=\"remove_active(event);\" href=\"/contact.html\">".$l_top[$curlang]["contactus"]."</a></li>\n";
  echo "</ul>\n";
  echo "</div>\n";
  echo "<div class=\"clear\"></div>\n";
  echo "</div>\n";
  echo "<div id=\"page\">\n";
  echo "<div id=\"box\">\n";
  echo "<div id=\"productCalogies\" onmouseover=\"show_menu()\" onmouseout=\"close_menu()\">\n";
  echo "Categories\n";
  echo "<div style=\"float: right; width: 20px; height: 20px; margin-top: 16px; margin-right: 5px;\"><a href=\"#\"><img src=\"/img/icon_select_category.png\" width=\"20\" height=\"20\" /></a></div>\n";
  echo "</div>\n";
  echo "<div id=\"search\">\n";
  echo "<div id=\"box_search_left\"></div>\n";
  echo "<div id=\"box_search_right\">\n";
  echo "</div>\n";
  echo "<div id=\"box_search_center\">\n";
  echo "<form name=\"f_search\" method=\"get\" action=\"/search.php\" onsubmit=\"sub()\">\n";
  echo "<input name=\"term\" id=\"serch_text\" type=\"text\" value=\"".$l_top[$curlang]["search_product"]."\" onfocus=\"if (this.value == 'Search Product') {this.value = '';}\" onblur=\"if (this.value == '') {this.value = 'Search Product';}\" />\n";
  echo "<input id=\"box_search_submit\" type=\"image\" src=\"/img/btn_search.png\" alt=\"Submit button\" onclick=\"return sub()\" />\n";
  echo "</form>\n";
  echo "</div>\n";
  echo "</div>\n";
  echo "<div class=\"clear\"></div>\n";
?>