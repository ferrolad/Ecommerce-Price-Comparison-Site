<?php
include "header.php";

if(!isset($_GET['website']))
{
    $fgmembersite->RedirectToURL("/CMS/homepage.php");
}

$website = $_GET["website"];
if (is_numeric($website[0]))
{
    $website = "_".$website;
}

${$website} = new $website();
${$website}->SetConfig($default_hostname, $default_username, $default_password, $default_db);
${$website."DB"} = new DBCrawler();
${$website."DB"}->SetConfig($default_hostname, $default_username, $default_password, $default_db, $_GET["website"]);
$categories_list = ${$website}->categories();

// Page
$page = 1;
if (isset($_GET['page']) && is_numeric($_GET['page'])) $page = $_GET['page'];

// Result crawl per page
$resultPerPage = 5;
if (isset($_GET["resultPerPage"]) && is_numeric($_GET["resultPerPage"]) && $_GET["resultPerPage"] > 0)
{
    $resultPerPage = $_GET["resultPerPage"];
}
?>


<div id="tabs"><a href="/catalog/category" class="item active">Categories</a></div> 

<div id="viewport_main">

    <div id="content">    
        <div class="headline"><h1><a href="/cart-ext/matrix-upload">Catalog</a> &gt; Categories</h1><div class="wrapper"></div></div>    

        <div id="<?php echo $_GET["website"] ?>">
            <h2><strong><?php echo $_GET["website"] ?></strong></h2>
            <form action = "./updatesite.php?website=<?php echo $_GET["website"] ?>" method = "GET">
                <input type="hidden" name="website" value="<?php echo $_GET["website"] ?>">
                <?php
                    foreach ($categories_list as $key => $value) 
                    {
                        $extra_info = "";
                        if (!isset($_GET["cat"]))
                        {
                            $cat = truncate_cat_name($key);
                            /* Get stats today */
                            $stats_today = ${$website."DB"}->get_stats_date($cat, 0);
                            /* Get stats yesterday */
                            $stats_yesterday = ${$website."DB"}->get_stats_date($cat, 1);
                            /* Get stats overall */
                            $stats_overall = ${$website."DB"}->get_stats_date($cat, -1);
                            /* Get stats of preprocess database for price > 0 */
                            $stats_preprocess_greater_than_zero = ${$website."DB"}->get_stats_preprocess($cat, true);
                            /* Get stats of preprocess database for price = 0 */
                            $stats_preprocess_equal_zero = ${$website."DB"}->get_stats_preprocess($cat, false);

                            $extra_info = "(Total: ".$stats_overall["count"].", Today: ".$stats_today["count"].", Yesterday: ".$stats_yesterday["count"].", Preprocess Price > 0: ".$stats_preprocess_greater_than_zero["count"].", Preprocess Price = 0: ".$stats_preprocess_equal_zero["count"].")";
                        }
                        
                        echo "<input type=\"checkbox\" name=\"cat[]\" value=\"".$key."\"><strong>".$key."</strong> <span style=\"color:red\">".$extra_info."</span> (".$value.") <br/>";
                    }
                ?>
                Page (Default to 1): <input type="text" name="page"><br>
                Result Per Page (Default to 5): <input type="text" name="resultPerPage"><br>
                <input type = "Submit" name = "crawl" value = "Info_one_page">
                <input type = "Submit" name = "crawl" value = "Image_one_page">
                <input type = "Submit" name = "crawl" value = "Price_one_page"><br/>
                <input type = "Submit" name = "crawl" value = "Info">
                <input type = "Submit" name = "crawl" value = "Image">
                <input type = "Submit" name = "crawl" value = "Price"><br/>
                <input type = "Submit" name = "crawl" value = "Image_All">
                <input type = "Submit" name = "crawl" value = "Price_All"><br/>
                <input type = "Submit" name = "crawl" value = "Price_All_Products"><br/>
                <input type = "Submit" name = "crawl" value = "Merge_From_Preprocess_To_Site_Database"><br/>
                <input type = "Submit" name = "crawl" value = "Clear_Preprocess_Price_Information"><br/>
            </form>  
        </div>

        <?php
        
        if (isset($_GET['crawl']) && $_GET['crawl'] ==  'Price_All') {    
            $cat2 = "all";
            $result = ${$website}->update_price_page($cat2, $page, $resultPerPage);
            if ($result > 0) {
                echo "<script>";
                echo "window.location = 'updatesite.php?website=".$_GET["website"]."&cat[]=".$cat2."&crawl=Price&page=".($page+1)."&resultPerPage=".$resultPerPage."';";
                echo "</script>";
            }
            else 
            {
                echo "Done with updating price<br/>";
            }
        } 
        // Crawl price option
        else if (isset($_GET['crawl']) && $_GET['crawl'] ==  'Price_All_Products') {    
            $cat2 = "all_products";
            $result = ${$website}->update_price_page($cat2, $page, $resultPerPage);
            if ($result > 0) {
                echo "<script>";
                echo "window.location = 'updatesite.php?website=".$_GET["website"]."&cat[]=".$cat2."&crawl=Price&page=".($page+1)."&resultPerPage=".$resultPerPage."';";
                echo "</script>";
            }
            else 
            {
                echo "Done with updating price<br/>";
            }
        }
        // Crawl image option 
        else if (isset($_GET['crawl']) && $_GET['crawl'] ==  'Image_All') 
        {  
            $cat2 = "all";  
            $result = ${$website}->get_images_page($cat2, $page, $resultPerPage);
            if ($result > 0) {
                echo "<script>";
                echo "window.location = 'updatesite.php?website=".$_GET["website"]."&cat[]=".$cat2."&crawl=Image&page=".($page+1)."&resultPerPage=".$resultPerPage."';";
                echo "</script>";
            }
            else 
            {
                echo "Done with updating image<br/>";
            }
        }
        else if (isset($_GET['crawl']) && $_GET['crawl'] == 'Merge_From_Preprocess_To_Site_Database')
        {
            $result = ${$website}->merge_preprocess_to_site_database();
            if ($result > 0)
            {
                echo "<script>";
                echo "window.location = 'updatesite.php?website=".$_GET["website"]."&crawl=Merge_From_Preprocess_To_Site_Database';";
                echo "</script>";
            }
        }
        else if (isset($_GET['crawl']) && $_GET['crawl'] == 'Clear_Preprocess_Price_Information')
        {
            $result = ${$website}->clear_preprocess_price_information();
        }

        if (isset($_GET["cat"])) {
            $cat = $_GET['cat'];
            
            $cat2 = truncate_cat_name($cat[0]);
            //echo "cat2=".$cat2." is".substr($cat, strlen($cat)-1, strlen($cat)-1);

            $result;
            /* Crawl Information */
            if ($_GET['crawl'] ==  'Info' || $_GET['crawl'] ==  'Info_one_page') {
                $result = ${$website}->add_categories($cat[0], $page);
            /* Crawl Image */
            } else if ($_GET['crawl'] ==  'Image' || $_GET['crawl'] ==  'Image_one_page') {
                $result = ${$website}->get_images_page($cat2, $page, $resultPerPage);
            /* Crawl new price */
            } else if ($_GET['crawl'] ==  'Price' || $_GET['crawl'] ==  'Price_one_page') {    
                $result = ${$website}->update_price_page($cat2, $page, $resultPerPage);
            }

            $cat_arg = cat_arg($website, $_GET['crawl'], $page, $cat, $result, $resultPerPage);
            
            // Echo this to close any open comment
            echo "-->";
            echo "<script>";
            echo "window.location = 'updatesite.php?".$cat_arg."';";
            echo "</script>";
        }

        function cat_arg($website, $crawl_type, $page, $cat, $result, $resultPerPage = 5)
        {
            $cat_arg = "website=".$_GET["website"];
            if ($_GET['crawl'] ==  'Info_one_page' || $_GET['crawl'] ==  'Image_one_page' || $_GET['crawl'] ==  'Price_one_page')
            {
                return $cat_arg;
            }

            $cat_arg .= "&crawl=$crawl_type";
            if ($result > 0)
            {
                for ($i = 0; $i < sizeof($cat); $i++)
                {
                    $cat_arg .= "&cat[]=".$cat[$i];
                }
                $cat_arg .= "&page=".($page+1);  
            }
            else
            {
                for ($i = 1; $i < sizeof($cat); $i++)
                {
                    $cat_arg .= "&cat[]=".$cat[$i];
                }
                $cat_arg .= "&page=1";  
            }

            $cat_arg .= "&resultPerPage=".$resultPerPage;

            return $cat_arg; 
        }

        function truncate_cat_name($cat)
        {
            $cat2 = $cat;
            if (is_numeric(substr($cat, strlen($cat)-1, strlen($cat)-1)) && $cat != 'mp3') {

                // There are two format. For ex: mobiles1 and mobiles_1
                $index = strrpos($cat, "_");
                if ($index === false)
                {
                    $cat2 = substr($cat, 0, strlen($cat)-1);
                }
                else
                {   
                    $number = substr($cat, $index+strlen("_"));   
                    if (is_numeric($number))
                    {
                        $cat2 = substr($cat, 0, $index);
                    }
                    else 
                    {
                        $cat2 = substr($cat, 0, strlen($cat)-1);       
                    }
                }
            }
            return $cat2;
        }
        ?>

    </div>    
    <div id="closing" class="">
        <a href="#header" class="generalIco icoPageUp"><!-- &nbsp; --></a>
        <div class="wrapper"><!-- &nbsp; --></div>
    </div>
    
<?php
    include ('./footer.html');
    ?>

</body>
</html>