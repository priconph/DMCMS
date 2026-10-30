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
          <a href="./operator_index.php?page=operator.home.php" class="navbar-brand"><img style="margin-top:-10%;" src="plugins/dmcms"></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li><p href="" style="font-size: 16px;" id="user_name" hidden></a></li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  <br>