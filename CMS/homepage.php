<?php
require_once("./include/membersite_config.php");
if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}

include ('./header.php');
?>

    
    
            <div id="tabs"></div>    
    <div id="viewport_main">
    
            <div id="content" class="noTabs">    
                <div class="headline"><h1>Dashboard</h1><div class="wrapper"></div></div>    
                <div id="main">
                                        <section id="third-party-systems" class="dashboard-container"><header><h1>System connection</h1><ul><li class="reload" title="reload"><span>reload</span></li></ul></header><div id="solr"><p>Solr<span class="status"><img src="/img/ajax-loader-small.gif" height="16" width="16" /></span></p></div><div id="prudsys"><p>Prudsys<span class="status"><img src="/img/ajax-loader-small.gif" height="16" width="16" /></span></p></div></section><section id="notifications" class="dashboard-container"><header><h1>Last 10 notifications</h1><ul><li class="reload"><span>reload</span></li></ul></header><div id="notifications-container"></div></section><section id="catalog-status" class="dashboard-container"><header><h1>Catalog status of last 24 hours</h1><ul><li class="reload"><span>reload</span></li></ul></header><div><p>New products <span class="value"><span id="products_created">0</span></span></p></div><div><p>Updated products<span class="value"><span id="products_updated">0</span></span></p></div><div><p>New activated products<span class="value"><span id="products_activated">0</span></span></p></div></section><section id="ratings" class="dashboard-container"><header><h1>Ratings</h1><ul><li class="reload"><span>reload</span></li></ul></header><div><p><a href="/rating/index/index/page/1?activeSearch=1&search%5Bstatus%5D=PENDING" title="Show pending ratings">Pending</a> <span class="value"><span id="ratings_pending">0</span></span></p></div><div><p>Approved <span class="value"><span id="ratings_approved">0</span></span></p></div><div><p>Rejected <span class="value"><span id="ratings_rejected">0</span></span></p></div><br /><div><p>Total <span class="value"><span id="ratings_total">0</span></span></p></div></section>
                    <div class="wrapper"><!-- &nbsp; --></div>
                </div>
    
                    
            </div>    
<?php
    include ('./footer.html');
?>
    </body>
</html>
