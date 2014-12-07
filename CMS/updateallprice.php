<?php
include ('./header.php');
if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}

if(isset($_POST['submitted']))
{
   if($fgmembersite->Login())
   {
    $fgmembersite->RedirectToURL("/CMS/homepage.php");
}
}

?>

<div id="tabs"><a href="/catalog/category" class="item active">Categories</a></div> 

<div id="viewport_main">
    
    <div id="content">    
        <div class="headline"><h1><a href="/cart-ext/matrix-upload">Catalog</a> &gt; Categories</h1><div class="wrapper"></div></div>    
        
        <div id="yes24">
            <h2><strong>Update All Price</strong></h2>
            <form action = "./updateallprice.php" method = "POST">
                <input type = "Submit" name = "crawl" value = "All_Price"><br/>
                <input type = "Submit" name = "crawl" value = "Accessories"><br/>
                <input type = "Submit" name = "crawl" value = "Audio"><br/>
                <input type = "Submit" name = "crawl" value = "Camera"><br/>
                <input type = "Submit" name = "crawl" value = "Computer"><br/>
                <input type = "Submit" name = "crawl" value = "Mobiles"><br/>
                <input type = "Submit" name = "crawl" value = "Tablets"><br/>
                <input type = "Submit" name = "crawl" value = "TV"><br/>
                <input type = "Submit" name = "crawl" value = "Female_fashion"><br/>
                <input type = "Submit" name = "crawl" value = "Male_fashion"><br/>
            </form>  
        </div>

        <?php
        if (isset($_POST["crawl"])) {
            if ($_POST["crawl"] == 'All_Price') {
                $result = $allproductDB->update_all_price();
            } else if ($_POST["crawl"] == 'Accessories') {
                $result = $allproductDB->update_accessories();
            } else if ($_POST["crawl"] == 'Audio') {
                $result = $allproductDB->update_audio();
            } else if ($_POST["crawl"] == 'Camera') {
                $result = $allproductDB->update_camera();
            } else if ($_POST["crawl"] == 'Computer') {
                $result = $allproductDB->update_computer();
            } else if ($_POST["crawl"] == 'Mobiles') {
                $result = $allproductDB->update_mobiles();
            } else if ($_POST["crawl"] == 'Tablets') {
                $result = $allproductDB->update_tablets();
            } else if ($_POST["crawl"] == 'TV') {
                $result = $allproductDB->update_tv();
            } else if ($_POST["crawl"] == 'Female_fashion') {
                $result = $allproductDB->update_female_fashion();
            } else if ($_POST["crawl"] == 'Male_fashion') {
                $result = $allproductDB->update_male_fashion();
            } 

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