<html>
<title>DMCMS</title>
<head>
<!--Affix Scripts/CSS here-->
<?php
require_once('./includes.php');
$page=$_GET['page'];
?>
</head>
<body class="hold-transition skin-blue layout-top-nav">
<!--Start of bootstrap navbar-->
<header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a href="./maintenance_index.php?page=maintenance.home.php" class="navbar-brand"><img style="margin-top:-7%;" src="plugins/dmcms"></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
           <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i style="color:red;font-size:26px;" class="fa fa-bell-o"></i>
              <span class="label" style="background-color: red;color:black;font-size:11px;" id="container_100"></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header" id="red_notif"></li>
              <li>
                <ul class="menu" id="red_menu">
                 
                </ul>
              </li>
              <li class="footer"><a href="maintenance_index.php?page=maintenance.listview.php&type=red&from=<?php echo $page;?>">View as a List</a></li>
            </ul>
          </li>
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i style="color:yellow;font-size:26px;" class="fa fa-bell-o"></i>
              <span class="label" style="background-color: yellow;color:black;font-size:11px;" id="container_90"></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header" id="yellow_notif"></li>
              <li>
                <ul class="menu" id="yellow_menu">
                </ul>
              </li>
              <li class="footer"><a href="maintenance_index.php?page=maintenance.listview.php&type=yellow&from=<?php echo $page;?>">View as a List</a></li>
            </ul>
          </li>
<!--           <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i style="color:#f7c07e;font-size:26px;" class="fa fa-bell-o"></i>
              <span class="label" style="background-color: #f7c07e;color:black;font-size:11px;" id="container_82"></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header" id="beige_notif"></li>
              <li>
                <ul class="menu" id="beige_menu">
                  <li>

                </ul>
              </li>
              <li class="footer"><a href="maintenance_index.php?page=maintenance.listview.php&type=beige&from=<?php echo $page;?>">View as a List</a></li>
            </ul>
          </li> -->
            <li><p href="" style="font-size: 16px;" id="user_name" hidden></a></li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  <br>