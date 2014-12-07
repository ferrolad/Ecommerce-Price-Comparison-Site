<?php
require_once("./include/membersite_config.php");
require_once("./include/customer_config.php");

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

$page = 1;
if (isset($_GET["page"])) {
	$page = $_GET["page"];
}
$customerList = $customerDB->get();
$numCustomers = sizeof($customerList);
$numPages = round($numCustomers/500+1, 0);

include ('./header.php');
?>
    
            <div id="tabs"></div>    
    <div id="viewport_main">
    
            <div id="content" class="noTabs">    
                <div class="headline"><h1>Customers</h1><div class="wrapper"></div></div>    

                <div id = "Add">
                  <form method = "GET" action = "addCustomer.php">
                  Email     : <input type = "text" name = "email"/>
                  Firstname : <input type = "text" name = "firstname"/>
                  Lastname  : <input type = "text" name = "lastname"/>
                  Gender    : <input type = "text" name = "gender"/>
                  Birthdate  : <input type = "date" name="birthdate">
                  <input type="submit" value="Add" />
                  </form><br/><br/>
                </div>


                <div id="main">
                                        <div class="extrasBar"><a href="/CMS/customer.php" class="item refresh"></a><span class="item export expandable">
                                  <div class="subMenu">
                                      <a class="action" href="/CMS/customer.php?&grid_export=view&grid_identifier=1">Export Current View</a><a class="action" href="/CMS/customer.php?grid_export=all&grid_identifier=1">Export All Data</a>
                                  </div>
                              </span><span class="item search expandable">
                                  <div class="subMenu">
                                      <form action="" method="GET"><input type="hidden" value="1" name="activeSearch" /><input type="text" name="search[id_customer]" value="ID" /><input type="text" name="search[increment_id]" value="Increment ID" /><input type="text" name="search[first_name]" value="First Name" /><input type="text" name="search[last_name]" value="Last Name" /><input type="text" name="search[middle_name]" value="Middle Name" /><input type="text" name="search[email]" value="E-Mail" /><input type="text" name="search[start_date]" value="Created from" /><input type="text" name="search[end_date]" value="Created to" /><input type="hidden" name="search[datetarget]" value="created_at" /><input type="text" name="search[gender]" value="Gender" /><input type="submit" value="search" />
                                          <span class="wrapper"></span>
                                      </form>
                                  </div>
                              </span></div><table class="grid linked" border="0" cellspacing="0" cellpadding="0" width="100%"><thead><tr class="cellCaption"><th><span></span></th><th><span>ID</span></th><th><span>E-Mail</span></th><th><span>First Name</span></th><th><span>Last Name</span></th><th><span>Created At</span></th><th><span>Updated At</span></th><th><span>Gender</span></th><th><span>Birthday</span></th></tr></thead><tbody>
							  
							  <?php
								//Print out customer list
								$index = 0;
                for ($i = 0; $i < $numCustomers; $i++) {
                  $customer = $customerList[$i];
									$index++;
									if ($index <= (($page-1)*500)) continue;
									if ($index > $page*500) break;
									echo "<tr><td class=\"narrow\"><b>";
									echo "<a href=\"/CMS/customer.php?id=".$customer["id"].">\" >edit</a></b></td>\n";
									echo "<td>".$customer["id"]."</td>\n";
									echo "<td>".$customer["email"]."</td>\n";
									echo "<td>".$customer["firstname"]."</td>\n";
									echo "<td>".$customer["lastname"]."</td>\n";
									echo "<td>".$customer["createdate"]." ".$customer["createtime"]."</td>\n";
									echo "<td>".$customer["updatedate"]." ".$customer["updatetime"]."</td>\n";
									echo "<td>".$customer["gender"]."</td>\n";
									echo "<td>".$customer["birthdate"]."</td>\n";
									echo "</tr>";
								}
							  ?>
							  
							  </tbody>  <tfoot>
                                <tr>
                                    <td colspan="99">
                                        <div class="pagination">
			<?php
				//Navigation button
				if ($page > 1) {
					echo "<a href=\"/CMS/customer.php?page=1\" class=\"button first\"><!-- &nbsp; --></a>";
					echo "<a href=\"/CMS/customer.php?page=".($page-1)."\" class=\"button prev\"><!-- &nbsp; --></a>";
				}
				echo "<span class=\"position\">".(($page-1)*25+1)." - ".(($page-1)*25+$index)." of $numCustomers</span>\n";
				
				if ($page < $numPages) {
					echo "<a href=\"/CMS/customer.php?page=".($page+1)."\" class=\"button next\"><!-- &nbsp; --></a>";
					echo "<a href=\"/CMS/customer.php?page=$numPages\" class=\"button last\">";
				}
			?>
            <!-- &nbsp; --></a>
    </div>

                                    </td>
                                </tr>
                            </tfoot></table>                    <div class="wrapper"><!-- &nbsp; --></div>
                </div>
    
                    
            </div>    

<?php
    include ('./footer.html');
?>

    </body>
</html>
