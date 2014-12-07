<?php
include './CMS/include/database_config.php';

class detail_display
{
    var $hostname;
    var $username;
    var $password;
    var $db;
    var $curlang;
    var $lang;
    var $l;
    var $id;
    var $website;
    var $brand;
    var $brand_name;
    var $brand_image;
    var $precalculated_model;
    var $model;
    var $model_name;
    var $category;
    var $link;
    var $name;
    var $name_without_special_characters;
    var $price;
    var $best_price;
    var $best_price_website;
    var $worst_price_website;
    var $comment;
    var $avgrating;
    var $description;
    var $description2;
    var $cat;
    var $subcat;
    var $catname;
    var $vnese_catname;
    var $english_catname;
    var $subcatname;
    var $vnese_subcatname;
    var $english_subcatname;
    var $image;
    var $thumbnails;
    var $suggested;
    var $similarproduct;
    var $namelink;
    var $seo_text;
    var $no_accent_name;
    var $last_updated_price;
    var $editorial_review;
    var $video_review;
    var $vote;
    
    function SetConfig($host, $user, $pass, $db, $id, $website, $lang)
    {
        $this->hostname = $host;
        $this->username = $user;
        $this->password = $pass;
        $this->db       = $db;
        $this->curlang  = $lang->current_language();
        $this->lang     = $lang;
        $this->l        = $lang->term_detail();
        $this->id       = $id;
        
        // If it is a detail page of a model
		if ($website != "modelproduct")
		{
            $website = translate_website_from_code($website);
        }
        
        $this->website = $website;
        
        /* If website code is wrong, return false */
        if (empty($this->website)) {
            return false;
        }
        
        try {
            // Prepare necessary database
            $allProductDB = new DBAllProduct();
            $allProductDB->SetConfig($host, $user, $pass, $db);
            
            $brandDB = new DBBrandModel();
            $brandDB->SetConfig($host, $user, $pass, $db);
            
            $catDB = new DBCategory();
            $catDB->SetConfig($host, $user, $pass, $db);
            
            $commentDB = new DBComment();
            $commentDB->SetConfig($host, $user, $pass, $db);
            
            $seotextDB = new DBSeoText();
            $seotextDB->SetConfig($host, $user, $pass, $db);
            
            $editorialreviewDB = new DBEditorialReview();
            $editorialreviewDB->SetConfig($host, $user, $pass, $db);
            
            $result = array();
            if ($website == "modelproduct") {
                $result = $brandDB->getModelId($id);
                /* Increment the number of click for the product by 1 */
                $brandDB->increment_product_numclick($id);
            } else {
                $result = $allProductDB->get_product($website, $id);
                /* Increment the number of click for the product by 1 */
                $allProductDB->increment_product_numclick($website, $id);
            }
            
            if ($result && sizeof($result) > 0) {
                $result         = $result[0];
                
                if ($website == "modelproduct") {
                    $this->link       = "";
                    $this->name       = str_replace(";", "/", $result["model"]);
                    $this->brand_name = $result["brand_name"];
                    if (!empty($this->brand_name)) {
                        $this->name = $this->brand_name . " " . $this->name;
                    }
                    
                    $this->no_accent_name      = $this->name;
                    $this->last_updated_price  = "";
                    $this->brand_image         = $result["brand_image"];
                    $this->precalculated_model = $result["id"];
                    $this->model_name          = $result["model"];
                    $this->editorial_review    = $editorialreviewDB->get(1, "", $this->id, 1);
                } else {
                    $this->link           = $result["link"];
                    $this->name           = $result["name"];
                    $this->no_accent_name = $result["no_accent_name"];
                    $this->price          = $result["price"];
                    if ($this->price < 0 || !is_numeric($this->price)) {
                        return false;
                    }
                    $this->last_updated_price  = $result["update_price_date"];
                    $this->brand_image         = "/img/website/" . $this->website . ".png";
                    $this->precalculated_model = $result["model"];
                    $this->editorial_review    = $editorialreviewDB->get(0, $this->website, $this->id, 1);
                    
                    // If the product is already included in a model, return a 301 redirect signal
                    if (!empty($this->precalculated_model)) {
                        $model_name  = $brandDB->getModelId($this->precalculated_model);
                        $redirectUrl = create_product_link($model_name[0]["brand_name"] . " " . $model_name[0]["model"], "", $model_name[0]["id"], $result["cat1"], $result["subcat1"]);
                        return "RedirectTo:" . $redirectUrl;
                    }

                    $this->description2 = trim($result["description2"]);
                    $this->comment      = trim($result["comment"]);
                    $this->video_review = trim($result["video1"]);
                }
                
                $this->name_without_special_characters = remove_special_characters($this->name);

                $this->image                           = array();
                //$this->thumbnail = array();
                for ($i = 1; $i <= 7; $i += 1) {
                    $this->image[] = $result["newimage$i"];
                    //$this->thumbnail[] = $result["thumbnail$i"];
                }

                $this->vote = explode("<>", $result["vote"]);

                $this->description = $this->clean_up_description(trim($result["description"]));
                
                // Get category and subcategory information
                if (!$this->get_category_info($result, $catDB))
                {
                    return false;
                }

                // Get the information of the brand and model of the product
                $this->get_brand_model_info($result);

                // Get Seo_text
                $this->seo_text = $this->get_seo_text($seotextDB);
                
                // Get the suggested product list 
                $this->get_suggested_product($allProductDB);

                // Find similar product 
                $this->get_similar_product($allProductDB);
                
                // If the model or product miss some information like description, images, video review, etc..., try to find them in other similar product
                $this->fill_in_empty_information();
                
                $this->comment = explode("<done>", $this->comment);
                
                // Calculate average rating
                $this->get_avg_rating();
                
                // Get price and best price information
                $this->get_price_info();
                
                /* Construct the url for the page */
                $namelink       = create_product_link($this->name, $website, $this->id, $this->cat, $this->subcat);
                $this->namelink = $namelink;
                return $namelink;
            }
        }
        catch (Exception $ex) {
            echo 'Caught exception: ', $ex->getMessage(), "\n";
        }
        return false;
    }
    
    // Find matching products
    private function get_similar_product($allProductDB)
    {
        $result = find_similarproduct($this->cat, $this->subcat, $this->no_accent_name, $this->website, $this->brand, $this->precalculated_model, $this->price, $allProductDB);

        if ($result) {
            $this->similarproduct = $result;
        }
    }

    // Find suggested products
    private function get_suggested_product($allProductDB)
    {
        $result = $allProductDB->get_suggested_product($this->name, $this->cat);

        if ($result) {
            $this->suggested = $result;
        }
    }

    // Update the category and subcategory information
    private function get_category_info($result, $catDB) 
    {
        $this->cat        = $result["cat1"];
        $this->subcat     = $result["subcat1"];
        $this->catname    = "";
        $this->subcatname = "";
        
        /* Get the name of the category */
        $result = $catDB->get_catname($this->cat);
        
        if ($result && sizeof($result) == 1) {
            $result                = $result[0];
            $this->catname         = $result["category"];
            $this->vnese_catname   = utf8_decode($result["vnese_name"]);
            $this->english_catname = $result["english_name"];
        } 
        else
        {
            return false;
        }
        
        /* Get the name of the subcategory */
        $result = $catDB->get_subcatname($this->subcat);
        if ($result && sizeof($result) == 1) {
            $result                   = $result[0];
            $this->subcatname         = $result["subcat"];
            $this->vnese_subcatname   = utf8_decode($result["vnese_name"]);
            $this->english_subcatname = $result["english_name"];
        }

        return true;
    }

    // Update the brand and model information
    private function get_brand_model_info($result) 
    {
        $this->brand      = $result["brand"];
        $this->model      = $result["model"];
        
        // If precalculated model is empty, try to guess the model name
        if (empty($this->model)) {
            $keyword_name = create_keyword_name($this->name);
            $keyword      = find_model_keyword_mobile($keyword_name);
            $this->model  = $keyword;
        }
    }

    // Update the price and best price information
    private function get_price_info() 
    {
        if ($this->website == "modelproduct") {
            $this->best_price = $this->get_best_price();
            /* Format the product price with , and . sign */
            $this->price      = "";
        } else {
            $this->best_price = $this->get_best_price();
            /* Format the product price with , and . sign */
            $this->price      = number_format($this->price, 2, '.', ',');
        }

        $this->best_price = number_format($this->best_price, 2, '.', ',');
    }

    // Calculate average rating
    private function get_avg_rating()
    {
        $this->avgrating = 0;

        // Go through each customer reviews to calculate average rating
        for ($i = 0; $i < sizeof($this->comment) - 1; ++$i) 
        {
            $rating = explode("<separator>", $this->comment[$i]);
            $rating = intval($rating[4]);
            $this->avgrating += $rating;
        }

        // Go through each vote to calculate average rating
        for ($i = 0; $i < sizeof($this->vote); ++$i) 
        {
            $this->avgrating += intval($this->vote[$i])*($i+1);
        }

        $size = sizeof($this->comment) + array_sum($this->vote) - 1;
        if ($size < 1)
        {
            $size = 1;
        }

        $this->avgrating = floatval($this->avgrating / $size);
    }

    // If the model or product miss some information like description, images, video review, etc..., try to find them in other similar product
    private function fill_in_empty_information()
    {
        // If description is empty, try to get it from other similar products
        if (empty($this->description)) {
            $this->get_description_from_similar_product();
        }
        
        // If description under CTA is empty, try to get it from other similar products
        if (empty($this->description2)) {
            $this->get_description2_from_similar_product();
        }
        
        // If video review is empty, try to get it from other similar products
        if (empty($this->video_review)) {
            $this->get_video_review_from_similar_product();
        }
        
        // If images are empty, try to get them from other similar products
        if (empty($this->image[0])) {
            $this->get_images_from_similar_product();
        }
        
        // If images are empty, try to get them from other similar products
        if (empty($this->comment)) {
            $this->get_comment_from_similar_product();
        }
    }
    
    private function get_video_review_from_similar_product()
    {
        for ($i = 0; $i < sizeof($this->similarproduct); ++$i) {
            $video_review = $this->similarproduct[$i]["video1"];
            if (!empty($video_review)) {
                $this->video_review = $video_review;
                return;
            }
        }
    }
    
    private function get_description_from_similar_product()
    {
        for ($i = 0; $i < sizeof($this->similarproduct); ++$i) {
            $description = $this->similarproduct[$i]["description"];
            if (!empty($description)) {
                $this->description = $description;
                return;
            }
        }
    }
    
    private function get_description2_from_similar_product()
    {
        for ($i = 0; $i < sizeof($this->similarproduct); ++$i) {
            $description2 = $this->similarproduct[$i]["description2"];
            if (!empty($description2)) {
                $this->description2 = $description2;
                return;
            }
        }
    }
    
    private function get_comment_from_similar_product()
    {
        for ($i = 0; $i < sizeof($this->similarproduct); ++$i) {
            $comment = $this->similarproduct[$i]["comment"];
            if (!empty($comment)) {
                $this->comment = $comment;
                return;
            }
        }
    }
    
    private function get_images_from_similar_product()
    {
        for ($i = 0; $i < sizeof($this->similarproduct); ++$i) {
            $image = $this->similarproduct[$i]["newimage1"];
            if (!empty($image)) {
                $this->image[0] = $this->similarproduct[$i]["newimage1"];
                $this->image[1] = $this->similarproduct[$i]["newimage2"];
                $this->image[2] = $this->similarproduct[$i]["newimage3"];
                $this->image[3] = $this->similarproduct[$i]["newimage4"];
                $this->image[4] = $this->similarproduct[$i]["newimage5"];
                return;
            }
        }
    }
    
    // Find the best price of the products
    private function get_best_price()
    {
        $best_price     = $this->price;
        $similarproduct = $this->similarproduct;
        
        if ($best_price == 0) {
            if (sizeof($similarproduct) > 0) {
                $best_price               = $similarproduct[0]["price"];
                $this->best_price_website = $similarproduct[0]["website"];
            } else {
                return "none";
            }
        }
        
        for ($i = 0; $i < sizeof($similarproduct); $i++) {
            $row = $similarproduct[$i];
            if ($best_price > $row["price"] && $row["price"] > 0) {
                $best_price               = $row["price"];
                $this->best_price_website = $row["website"];
            }
        }
        
        return $best_price;
    }
    
    /* Helper function that to clean up the product description to display */
    private function clean_up_description($description)
    {
        $description = strip_tags_custom($description);
        $description = str_replace("&nbsp;", "", $description);
        $description = preg_replace("/[\r\n]+\s*/", "\n", $description);
        $description = str_replace("\n", "<br/>", $description);
        $description = preg_replace('/\s+/', ' ', $description);
        
        /* Remove first <br> */
        if (substr($description, 0, 4) == "<br>") {
            $description = substr($description, 4);
        } else if (substr($description, 0, 5) == "<br/>") {
            $description = substr($description, 5);
        }
        
        if ($this->website == "nguyenkim") {
            $allowed_tags = '<a><br><b><h3><h4><i><li><ol><strong><tr><td><th><u><ul><em>';
            $description  = strip_tags($description, $allowed_tags);
        }
        
        return $description;
    }

    private function get_seo_text($seotextDB)
    {
        /* Get the review list */
        $seo_text = "";
        
        $result = $seotextDB->get($this->website, $this->id);
        
        if ($result) {
            $seo_info = $result[0];
            if ($seo_info["active"] == 1) {
                $seo_text = trim($seo_info["seo_text"]);
            }
        }
        
        return $seo_text;
    }
    
    /* Helper function that display the keyword, description, title part of the html header*/
    public function display_title_keyword_description_header()
    {
        $name          = str_replace("\"", "", $this->name);
        $name_keywords = str_replace("\"", "", implode(", ", explode(" ", $this->name)));
        $title         = detail_title($this->name, $this->cat, $this->subcat, $this->l[$this->curlang]["price_in_country"]);
        
        echo "<title>" . $title . "</title>\n";
        echo "  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">\n";
        echo "  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1\">\n";
        echo "<meta name=\"description\" content=\"Buy " . $name . " online ✓ " . $name . " Specifications & Reviews ✓ Best prices for " . get_catname_english_seo($this->cat) . " in Philippines at Price Pony.\">\n";
        echo "  <meta name=\"keywords\" content=\" " . $name_keywords . ", " . $name . ", " . strtolower($this->english_catname) . ", " . strtolower($this->english_subcatname) . ", buy, cheapest, review, comparison, guide, lowest, best\" />\n";
        echo "  <meta name=\"robots\" content=\"index,follow\" />\n";
        echo "  <meta property=\"og:title\" content=\"" . $name . "\"/>\n";
        echo "  <meta property=\"og:type\" content=\"product\"/>\n";
        echo "  <meta property=\"og:image\" content=\"" . $this->image[0] . "\"/>\n";
    }

    
    /* Helper function that display the right ad on the detail page */
    public function display_ad($pos)
    {
        if ($pos == 1 || $pos == 2) 
        {
            // echo "<script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>\n";
            // echo "<!--Bottom Landing Page-->\n";
            // echo "<ins class=\"adsbygoogle\"\n";
            // echo "style=\"display:inline-block;margin-left:150px;margin-bottom:10px;margin-top:10px;width:728px;height:90px\"\n";
            // echo "data-ad-client=\"ca-pub-4796993490224996\"\n";
            // echo "data-ad-slot=\"8480295261\"></ins>\n";
            // echo "<script>\n";
            // echo "(adsbygoogle = window.adsbygoogle || []).push({});\n";
            // echo "</script>\n";
        }

        $adsDB = new DBAds();
        //Provide config setting
        $adsDB->SetConfig($this->hostname, $this->username, $this->password, $this->db);
        $result = $adsDB->get($pos);
        
        if ($pos == 8 || $pos == 9) {
            echo "<a href=\"" . $result[1] . "\" rel=\"nofollow\">";
            echo "<img src=\"" . $result[2] . "\" alt=\"" . $result[3] . "\"/>";
            echo "</a>";
        }
        
        if ($pos == 10) {
            echo "<a href=\"" . $result[1] . "\" rel=\"nofollow\">";
            echo "<img src=\"" . $result[2] . "\" alt=\"" . $result[3] . "\" style=\"padding:0px;\"/>";
            echo "</a>";
        }
        
        if ($pos == 11 || $pos == 12) {
            echo "<div class=\"AD" . ($pos - 10) . "\" rel=\"nofollow\">\n";
            echo "<a href=\"" . $result[1] . "\"><img src=\"" . $result[2] . "\" alt=\"" . $result[3] . "\" width=\"468\" height=\"90\"/>\n";
            echo "</a>\n";
            echo "</div>\n";
        }
    }
    
    /* Helper function thats displays product categories */
   public  function display_product_categories()
    {
        display_categories($this->hostname, $this->username, $this->password, $this->db, $this->curlang, true);
    }
    
    /* Function display stars on top of the product in product detail page */
    public function display_star_detail($number)
    {
        $number_star = 4;
        if (is_numeric($number) && $number >= 0 && $number <= 5) {
            $number_star = $number;
        }
        for ($i = 0; $i < $number_star; $i++) {
            echo "<li><img src=\"/images/star.png\" alt=\"star\" title=\"star\"></li>\n";
        }
        for ($i = $number_star; $i < 5; $i++) {
            echo "<li><img src=\"/images/none-star.png\" alt=\"star\" title=\"star\"></li>\n";
        }
    }
    
    public function display_text_above_price_comparison()
    {
        echo $this->name . " " . $this->l[$this->curlang]["price_in_country"];
    }
    
    /* Display product detail */
    public function display_product_detail()
    {
        //Craete the tracking link for the product
        $link = create_utm_link($this->link, $this->website, $this->id, $this->cat, $this->subcat, $this->name);
        // Shorten the name if too long 
        $name = truncate_string($this->name, 80);
        
        // Price
        $price   = $this->price;
        // Website
        $website = $this->website;
        
        if ($website == "modelproduct") {
            $row  = $this->similarproduct[0];
            $link = create_utm_link($row["link"], $row["website"], $row["id"], $row["cat1"], $row["subcat1"], $row["name"]);
        }
        
        echo "<div class=\"productname\"><h1>" . $name . "</h1>";
        $this->display_star_rating($this->avgrating);
        
        echo "<a href=\"#\"/><span id=\"num_rating\">".(sizeof($this->comment)+array_sum($this->vote))." ratings</span></a></div>\n";
        //echo "<a href=\"#\"/><span>" . (sizeof($this->comment) - 1) . " " . $this->l[$this->curlang]["review"] . "s</span></a></div>\n";
        
        echo "<div class=\"product_text\" style=\"height=20px;overflow=hidden\">\n";
        
        if ($this->website == "modelproduct") {
            //echo "<span style=\"font-size:25px; font-weight:bold; color:#bb0000; line-height:25px\">Best Price: RM ".$this->best_price."</span><br/>";
            echo "<span style=\"font-size: 20px; /* font-weight:bold; */ color: black; line-height:25px\">" . $this->l[$this->curlang]["best_price"] . ": " . $this->l[$this->curlang]["currency"] . " " . $this->best_price . "</span><br/>";
        } else if ($price > 0) {
            echo "<span style=\"font-size: 20px; /* font-weight:bold; */ color: black; line-height:25px\">" . $this->l[$this->curlang]["price"] . ": " . $this->l[$this->curlang]["currency"] . " " . $price . "</span><br/>";
        }
        
        echo "<br/>";
        
        if ($website == "modelproduct") {
            echo "<a href=\"" . $link . "\" rel=\"nofollow\" target=\"_blank\" onClick=\"trackOutboundLink(this, 'Outbound Links', '" . $this->similarproduct[0]["website"] . "'); return false;\"><img src=\"/img/GoToShop.png\" alt=\"Buy Now\" title=\"Buy Now\"/></a>\n";
        } else {
            echo "<a href=\"" . $link . "\" rel=\"nofollow\" target=\"_blank\" onClick=\"trackOutboundLink(this, 'Outbound Links', '" . $this->website . "'); return false;\"><img src=\"/img/GoToShop.png\" alt=\"Buy Now\" title=\"Buy Now\"/></a>\n";
        }
        
        echo "</div>\n";
        echo "<div style=\"margin-left:30px;font-size:130%;height=300px;float:left;margin-top:30px\">";
        echo $this->description2;
        echo "</ul>";
        echo "</div>";
        
        $fullurl = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        echo "<div class=\"fb-like\" data-href=\"" . $fullurl . "\" data-layout=\"button_count\" data-action=\"like\" data-show-faces=\"false\" data-share=\"false\" style=\"margin-top:10px;margin-bottom:10px;width:400px\"></div>\n";
        echo "<div class=\"g-plusone\" style=\"margin-top:5px\"></div>\n";
    }
    
    /* Helper function that displaies category list at the top of the detail page */
    public function display_category_list()
    {
        echo "<span class=\"youah\">\n";
        
        echo "<a href=\"/index.php\">" . $this->l[$this->curlang]["homepage"] . "</a>\n";
        
        echo "</span>\n";
        echo "<span class=\"pathway separator\"><img src=\"/img/icon_arrow_grey.png\" alt=\"\" /></span>\n";
        
        $cat_remove_sign = strtolower(remove_sign($this->english_catname));
        echo "<span class=\"pathway\"><a href=\"/" . $cat_remove_sign . "\">" . $this->english_catname . "</a></span>\n";
        if ($this->subcat != 0 && $this->subcat <> "") {
            $subcat_remove_sign = strtolower(remove_sign($this->english_subcatname));
            echo "<span class=\"pathway separator\"><img src=\"/img/icon_arrow_grey.png\" alt=\"\" /></span>\n";
            echo "<span class=\"pathway\"><a href=\"/" . $cat_remove_sign . "/" . $subcat_remove_sign . "\">" . $this->english_subcatname . "</a></span>\n";
        }
        
        echo "<span class=\"pathway separator\"><img src=\"/img/icon_arrow_grey.png\" alt=\"\" /></span>\n";
        
        $name = truncate_string($this->name, 50);
        
        echo "<span class=\"pathway last\">" . $name . " " . $this->l[$this->curlang]["price_in_country"] . "</span>\n";
    }
    
    /* Helper function that displaies products images and thumbnails */
    public function display_product_image()
    {
        echo "<div id=\"large_img\">\n";
        echo "<div id=\"slider\" class=\"flexslider_detail\">\n";
        echo "<ul class=\"slides\">\n";
        /* Display the big images */
        for ($i = 0; $i < 5; $i++) {
            if ($this->image[$i] <> "" && valid_image("." . $this->image[$i])) {
                echo "<li>\n";
                echo "<a href=\"" . ($this->image[$i]) . "\" alt=\"".$this->no_accent_name."\" title=\"".$this->no_accent_name."\" rel=\"lightbox\">";
                echo "<img src=\"" . ($this->image[$i]) . "\" alt=\"".$this->no_accent_name."\" title=\"".$this->no_accent_name."\" width = \"258\" height = \"262\"/>\n";
                echo "</a>";
                echo "</li>\n";
            }
        }
        echo "</ul>\n";
        echo "</div>\n";
        echo "</div>\n";
        echo "<div id=\"thumbnail_img\">\n";
        echo "<div id=\"carousel\" class=\"flexslider\" style=\"min-height:30px\">\n";
        echo "<ul class=\"slides\">\n";
        
        /* Display the thumbnails */
        for ($i = 0; $i < 5; $i++) {
            if (valid_image("." . $this->image[$i])) {
                if ($this->image[$i] <> "") {
                    echo "<li style = \"width: 44px; height:45px\">\n";
                    /* If thumbnail exists and valid, send to client thumbnail. Otherwises, the original image */
                    /*if ($this->thumbnail[$i] <> "") {
                    echo "<img src=\"".$this->thumbnail[$i]."\"/>\n";	
                    } else {*/
                    echo "<img src=\"" . $this->image[$i] . "\" alt=\"".$this->no_accent_name."\" title=\"".$this->no_accent_name."\"/>\n";
                    //}
                    echo "</li>\n";
                } else {
                    /* If the product has only 1 picture, display two picture looks the same to fix display problem */
                    if ($i == 1) {
                        echo "<li style = \"width: 44px; height:45px\">\n";
                        /* If thumbnail exists and valid, send to client thumbnail. Otherwises, the original image */
                        /*if ($this->thumbnail[$i] <> "") {
                        echo "<img src=\"".$this->thumbnail[0]."\"/>\n";	
                        } else {*/
                        echo "<img src=\"" . $this->image[0] . "\" alt=\"".$this->no_accent_name."\" title=\"".$this->no_accent_name."\"/>\n";
                        //}
                        echo "</li>\n";
                    }
                }
            }
        }
        echo "</ul>\n";
        echo "</div>\n";
        echo "</div>\n";
    }
    
    /* Helper function that displays product's description */
    public function display_seo_text()
    {
        echo "<strong>" . $this->name . "</strong> <br /><br />\n";
        if (!empty($this->seo_text)) {
            echo $this->seo_text;
        } else {
            echo strip_tags_custom($this->description);
        }
    }
    
    /* Helper function that display price comparisons in the detail page */
    public function display_price_comparison()
    {
        $count          = 0;
        $similarproduct = $this->similarproduct;
        
        /* Traverse through the list of similar product */
        for ($i = 0; $i < sizeof($similarproduct); $i++) {
            $row = $similarproduct[$i];
            /* Doesn't print out the product as a similar product of itself*/
            if ($row["id"] == $this->id && $row["website"] == $this->website) {
                //continue;
            }
            
            $count++;
            /* Only display at most 10 products */
            if ($count > 10) {
                break;
            }
            
            /* Create the right name link */
            $utmlink  = create_utm_link($row["link"], $row["website"], $row["id"], $row["cat1"], $row["subcat1"], $row["name"]);
            $namelink = $utmlink;
            
            // If this is a model, there is no namelink for the specific product
            // Lead the user straight to the store
            if ($this->website != "modelproduct") {
                $namelink = create_product_link($row["name"], $row["website"], $row["id"], $row["cat1"], $row["subcat1"]);
            }
            
            /* Name of the similar product */
            if ($this->cat != 8 && $this->cat != 9) {
                $row["name"] = remove_non_ascii_beginning($row["name"]);
            }

            $row["name"] = truncate_string($row["name"], 25);
            
            echo "<div class=\"table_row\">\n";

            // Website logo portion
            $this->display_price_comparison_website_logo($utmlink, $namelink, $row);

            // Product image and name portion
            $this->display_price_comparison_product_image_name($utmlink, $namelink, $row);

            // Price portion
            $this->display_price_comparison_price($utmlink, $namelink, $row);

            // Price portion
            $this->display_price_comparison_delivery_info($utmlink, $namelink, $row);
            
            // CTA portion
            $this->display_price_comparison_cta_button($utmlink, $namelink, $row);
        }
    }

    private function display_price_comparison_website_logo($utmlink, $namelink, $row)
    {
        // Website logo portion
        echo "<div class=\"text1\"><a href=\"" . $utmlink . "\" rel=\"nofollow\" target=\"_blank\" onClick=\"trackOutboundLink(this, 'Outbound Links', '" . $row["website"] . "'); return false;\"><img src=\"/img/website/" . $row["website"] . ".png\" alt=\"" . $row["name"] . "\" title=\"" . $row["name"] . "\" width=\"128\" height=\"29\"/></a></div>\n";
    }

    private function display_price_comparison_product_image_name($utmlink, $namelink, $row)
    {
        // Product image and name portion
        echo "<div class=\"text2\"><img src=\"" . $row["newimage1"] . "\" alt=\"" . $row["name"] . "\" title=\"" . $row["name"] . "\" width=\"40\" height=\"50\"/>\n";

        if ($this->website != "modelproduct") 
        {
            echo "<a href=\"" . $namelink . "\">" . $row["name"] . "</a>";
        } 
        else 
        {
            echo "<a href=\"" . $utmlink . "\" rel=\"nofollow\" target=\"_blank\" onClick=\"trackOutboundLink(this, 'Outbound Links', '" . $row["website"] . "'); return false;\">" . $row["name"] . "</a>";
        }
        echo "</div>\n";
    }

    private function display_price_comparison_price($utmlink, $namelink, $row)
    {
        $numsigfig      = $this->l[$this->curlang]["num_sigfig"];
        $decsep         = $this->l[$this->curlang]["dec_separator"];
        $thoussep       = $this->l[$this->curlang]["thousand_separator"];

        // Price portion
        echo "<div class=\"text3\"><span>\n";
        if ($this->website != "modelproduct") 
        {
            echo "<a href=\"" . $namelink . "\" >" . $this->l[$this->curlang]["currency"] . " " . number_format($row["price"], $numsigfig, $decsep, $thoussep) . "</a></span>\n";
        } 
        else 
        {
            echo "<a href=\"" . $utmlink . "\" rel=\"nofollow\" target=\"_blank\" onClick=\"trackOutboundLink(this, 'Outbound Links', '" . $row["website"] . "'); return false;\">" . $this->l[$this->curlang]["currency"] . " " . number_format($row["price"], $numsigfig, $decsep, $thoussep) . "</a></span>\n";
        }
        echo "</div>\n";
    }

    private function display_price_comparison_delivery_info($utmlink, $namelink, $row)
    {
        $delivery_info = get_website_delivery_info($row["website"]);
        // CTA portion
        //<img src=\"/index_images/delivery.png\"/>
        echo "<div class=\"text4\"><span>".$delivery_info."</span></div>\n";
    }

    private function display_price_comparison_cta_button($utmlink, $namelink, $row)
    {
        // CTA portion
        echo "<div class=\"text5\"><a href=\"" . $utmlink . "\" rel=\"nofollow\" target=\"_blank\" onClick=\"trackOutboundLink(this, 'Outbound Links', '" . $row["website"] . "'); return false;\"><img src=\"/index_images/button1.png\" alt=\"" . $row["name"] . "\" /></a></div>\n";
        echo "</div>\n";
    }
    
    public function display_related_search()
    {
        $cat_remove_sign = cat_remove_sign_english($this->catname);
        
        echo "<a href=\"/" . $cat_remove_sign . "\" >" . strtolower($this->english_catname) . "</a>, \n";
        
        // Subcategory is a related search
        if ($this->subcat != 0 && $this->subcat <> "") {
            $subcat_remove_sign = subcat_remove_sign_english($this->subcatname);
            echo "<a href=\"/" . $cat_remove_sign . "/" . $subcat_remove_sign . "\" >" . strtolower($this->english_catname . " " . $this->english_subcatname) . "</a>, \n";
        }
        
        // Brand is a related search
        // if ($this->brand <> "" && $this->brand <> $this->subcat) {
        // 	echo "<a href=\"/search.php?term=".$this->brand."\" rel=\"nofollow\">".$this->brand."</a>, \n";
        // }
        
        // Model is a related search 
			if (!empty($this->model)) {
            echo "<a href=\"/search.php?term=" . $this->model . "\" >" . $this->model . "</a>, \n";
        }
    }
    
    /* Helper function that displays related product at the top */
    public function display_related_items()
    {
        // Suggested products
        $suggested = $this->suggested;
        $count     = 0;
        
        echo "<h2>" . $this->l[$this->curlang]["related"] . "</h2>\n";
        
        for ($i = 0; $i < sizeof($suggested); $i++) {
            /* Fetch each product out */
            $row = $suggested[$i];
            /* We don't suggest the product to itself */
            if ($row["id"] != $this->id || $row["website"] != $this->website) {
                /* Check for 404 image link */
                /*if (!valid_image(".".$row["newimage1"])) {
                continue;
                }*/
                
                $count++;
                
                /* Create the right name link */
                $namelink = create_product_link($row["name"], $row["website"], $row["id"], $row["cat1"], $row["subcat1"]);
                $price    = number_format($row["price"], 2, '.', ',');
                
                echo "<div class=\"text_holder\">\n";
                echo "<div class=\"prd_img\"><a href=\"/" . $namelink . "\" target=\"_blank\"><img src=\"" . $row["newimage1"] . "\" alt=\"" . $row["name"] . "\" title=\"" . $row["name"] . "\" width=\"51\" height=\"48\"/></a></div>\n";
                echo "<div class=\"prd_text\"><a href=\"/" . $namelink . "\" target=\"_blank\">" . $row["name"] . "</a><br />" . $this->l[$this->curlang]["currency"] . " " . $price . "</div>\n";
                echo "</div>\n";
                
                if ($count >= 5)
                    break;
            }
        }
    }
    
    public function display_summary_information()
    {
        $the = $this->l[$this->curlang]["the"];
        
        echo "<div class=\"block_container\">\n";
        echo "</div>\n";
        echo "<div class=\"block_container\">\n";
        echo "<ul style=\"font-size:110%; margin-left:30px; width:95%\">";
        echo "<li>" . $this->l[$this->curlang]["summary_currency"] . "</li>";
        echo "<li>" . $the . " <strong>" . $this->l[$this->curlang]["summary_latest_price"] . " " . $this->name . "</strong> " . $this->l[$this->curlang]["summary_obtain"] . " " . date("d-m-y") . "</li>";
        echo "<li>" . $the . " " . $this->name . " " . $this->l[$this->curlang]["summary_available"] . " ";
        
        if ($this->website != "modelproduct") {
            echo $this->website;
        }
        
        $website = array();
        for ($i = 0; $i < sizeof($this->similarproduct); ++$i) {
            if (!in_array($this->similarproduct[$i]["website"], $website)) {
                $website[] = $this->similarproduct[$i]["website"];
                if ($i > 0 || $this->website != "modelproduct") {
                    echo ", ";
                }
                
                echo $this->similarproduct[$i]["website"];
            }
        }
        
        echo "</li>";
        echo "<li>" . $the . " <strong>" . $this->l[$this->curlang]["summary_best_price"] . " " . $this->name . "</strong> " . $this->l[$this->curlang]["is"] . " " . $this->l[$this->curlang]["currency"] . " " . $this->best_price . " in " . $this->best_price_website . "</li>";
        echo "<li>" . $this->l[$this->curlang]["summary_location"] . "</li>";
        echo "</ul>";
        echo "</div>\n";
    }
    
    public function display_video_review()
    {
        $video_review = $this->video_review;
        if (!empty($video_review)) {
            echo "<div class=\"block_container\">\n";
            echo "<div class=\"block_title\">" . $this->l[$this->curlang]["video_review"] . "</div>\n";
            echo "<div class=\"block_text_holder\">\n";
            echo "<iframe width=\"720\" height=\"315\" src=\"" . $video_review . "\" frameborder=\"0\" allowfullscreen></iframe>\n";
            echo "</div>\n";
            echo "</div>\n";
            echo "</div>\n";
        }
        ;
    }
    
    public function display_editorial_review()
    {
        $editorial_review_list = $this->editorial_review;
        for ($i = 0; $i < sizeof($editorial_review_list); ++$i) {
            $review = $editorial_review_list[$i];
            
            echo "<div class=\"block_container\">\n";
            echo "<div class=\"block_title\">" . $this->l[$this->curlang]["editorial_review"] . " " . $this->name . "</div>\n";
            echo "<div class=\"block_subtitle\">" . $this->l[$this->curlang]["by"] . " " . $review["reviewer"] . " " . $this->l[$this->curlang]["at"] . " " . format_time_review($review["time"]) . "<span><img src=\"/img/logo_Giare.png\" alt=\"Oizoioi\" title=\"Oizoioi\" width=\"84\" height=\"12\" alt=\"\" /></span></div>\n";
            echo "<div class=\"block_text_holder\">\n";
            echo $review["seo_text"] . "\n";
            echo "</div>\n";
            echo "<div class=\"block_rating\">\n";
            echo $this->l[$this->curlang]["rating"] . ":\n";
            
            $rating = $review["rating"];
            
            if (is_numeric($rating) && $rating > 0 && $rating <= 5) {
                $this->display_star($rating);
            }

            echo "<span class=\"rating\" style=\"color:#e36e11\"> (" . $rating . ")</span>";
            
            echo "</div>\n";
            echo "</div>\n";
        }
    }
    
    public function display_customer_reviews()
    {
        $review_list = $this->comment;
        
        echo "<a class=\"thickbox2\" style=\"margin-top:20px; margin-bottom:20px\" href=\"/review.php?id=" . $this->id . "&website=" . $this->website . "&image=" . $this->image[0] . "&name=" . $this->name . "&height=270&width=920&KeepThis=true&TB_iframe=true\">Add Review</a>\n";
        
        echo "<div class=\"block_title\">Review Of " . $this->name . "</div>\n";
        echo "<div style=\"height:30px\"></div>\n";
        
        $this->display_history_bar();
        
        $size = sizeof($review_list);
        if ($size > 5)
            $size = 6;
        for ($i = 0; $i < $size - 1; ++$i) {
            $review            = $review_list[$i];
            $review_components = explode("<separator>", $review);
            $name              = $review_components[0];
            $date              = $review_components[1];
            $title             = $review_components[2];
            $content           = $review_components[3];
            $value             = $review_components[4];
            
            echo "<div class=\"block_container\">\n";
            echo "<div class=\"block_subtitle\">By " . $name . " (" . $date . ")<span><img src=\"/img/logo_Giare.png\" alt=\"Oizoioi\" title=\"Oizoioi\" width=\"84\" height=\"12\" alt=\"\" /></span></div>\n";
            echo "<div class=\"block_text_holder\">\n";
            echo $content . "\n";
            echo "</div>\n";
            echo "<div class=\"block_rating\">\n";
            echo "Rating:\n";
            
            if (is_numeric($value) && $value > 0 && $value <= 5) {
                $this->display_star($value);
            }
            echo "<span class=\"rating\" style=\"color:#e36e11\"> (" . $value . ")</span>";
            
            echo "</div>\n";
            echo "</div>\n";
        }
        
        $website = $this->website;
        if ($website == "modelproduct")
            $website = "model";

        if ((sizeof($review_list) - 6) > 0)
        {
            $name = str_replace(" ", "-", $this->no_accent_name);
            echo "<a href =\"/review/".$name."-" . $this->id . "-" . $website . "\">View " . (sizeof($review_list) - 6) . " more customer reviews</a>";
            //echo "<a href =\"/customerReview.php?param=Review-" . $this->id . "-" . $website . "\">View " . (sizeof($review_list) - 6) . " more customer reviews</a>";
        }
    }
    
    public function display_history_bar()
    {
        echo "<div style=\"margin-left:10px; margin-top:20px\">";
        $this->display_star($this->avgrating);
        echo "<a href=\"#\"/><span style=\"margin-top:10px\">(" . (sizeof($this->comment) - 1) . ")</span></a>";
        echo "<br/>";
        //echo "<span style=\"color:#888!important; font-size:15px\">" . round($this->avgrating, 1) . " out of 5 stars</span>";
        echo "</div>\n";
        
        echo "<table id=\"histogramTable\" class=\"a-normal a-align-middle a-spacing-base\" style=\"margin-left:8px;margin-top:5px\">\n";
        echo "<tr class=\"a-histogram-row\">\n";
        
        // Calculate number of reviews of each star
        $total       = 0;
        $numReview   = array();
        $numReview[] = 0;
        $numReview[] = 0;
        $numReview[] = 0;
        $numReview[] = 0;
        $numReview[] = 0;
        
        for ($i = 0; $i < sizeof($this->comment) - 1; ++$i) {
            $rating = explode("<separator>", $this->comment[$i]);
            $rating = intval($rating[4]);
            $numReview[$rating - 1]++;
            $total++;
        }
        if ($total == 0)
            $total = 1;
        
        // Display history bar
        for ($i = 5; $i >= 1; --$i) {
            echo "<td class=\"a-nowrap\" width=\"5%\">\n";
            echo "<a class=\"a-link-normal\" title=\"50% of reviews have 5 stars\" href=\"#\" style=\"color:#00AFF0\">" . $i . " star</a><span class=\"a-letter-space\"></span>\n";
            echo "</td>\n";
            echo "<td class=\"a-span10\" width=\"100\">\n";
            echo "<a class=\"a-link-normal\" title=\"" . ($numReview[$i - 1] / $total * 100) . "% of reviews have " . $i . " stars\" href=\"#\"><div class=\"a-meter\"><div class=\"a-meter-bar\" style=\"width: " . ($numReview[$i - 1] / $total * 100) . "%;\"></div></div></a>\n";
            echo "</td>\n";
            echo "<td class=\"a-nowrap\">\n";
            echo "<span class=\"a-letter-space\"></span><span>" . $numReview[$i - 1] . "</span>\n";
            echo "</td>\n";
            echo "</tr>\n";
            echo "<tr class=\"a-histogram-row\">\n";
        }
        
        echo "</tr>\n";
        echo "</table>\n";
    }
    
    public function display_star($value)
    {
        // If there is no review, give a default value of 4
        if (sizeof($this->comment) + array_sum($this->vote) == 1) {
            $value = 4;
        }
        echo "<ul class=\"star-rating\" style=\"background:none; width:90px; margin-right:0px; margin-top:0px\">";

        // Display full star
        for ($j = 0; $j < intval($value); ++$j) {
            echo "<img src=\"/index_images/star.jpg\" alt=\"star\" title=\"star\"/>";
        }

        // Display half a star
        if ($value > intval($value) && $value < intval($value) + 1) {
           echo "<img src=\"/index_images/hstar.jpg\" alt=\"star\" title=\"star\"/>";
           $value = intval($value) + 1;
       }

        // Display empty star
       for ($j = intval($value); $j < 5; ++$j) {
           echo "<img src=\"/index_images/estar.jpg\" alt=\"star\" title=\"star\"/>";
       }
       echo "</ul>";
   }

    public function display_star_rating($value)
    {
        // If there is no review, give a default value of 4
        if (sizeof($this->comment) + array_sum($this->vote) == 1) {
            $value = 4;
        }
        echo "<ul class=\"star-rating\">";

        // Display stars and current rating
        for ($j = 0; $j < 5; ++$j) {
           // echo "<img src=\"/index_images/estar.jpg\" alt=\"star\" title=\"star\"/>";
            if($j!=(intval($value)-1))
                echo "<li><a href=\"javascript:rateImg(1,'".($j+1)."')\" title=\"".($j+1)." star out of 5\" onclick=\"recordVote(".($j+1).", ".$this->id.", '".$this->website."')\" class=\"stars_".($j+1)."\">1</a></li>";
            else
            {
                if ($value > intval($value) && $value < intval($value) + 1) {
                    echo "<li class=\"current-rating\" style=\"width:".(25*( intval($value) + 0.5))."px\"><a href=\"javascript:rateImg(1,'".($j+1)."')\" title=\"".($j+1)." star out of 5\" onclick=\"recordVote(".($j+1).", ".$this->id.", '".$this->website."')\" class=\"stars_".($j+1)."\">1</a></li>";
                 }
                else{
                    echo "<li class=\"current-rating\" style=\"width:".(25*($j+1))."px\"><a href=\"javascript:rateImg(1,'".($j+1)."')\" title=\"".($j+1)." star out of 5\" onclick=\"recordVote(".($j+1).", ".$this->id.", '".$this->website."')\" class=\"stars_".($j+1)."\">1</a></li>";
                }
            }
        }
        echo "</ul>";
    }
}