<?php

/*
 *  Download File script
 *  Aurthor: Mohammad Ghasembeigi
 *  Url: https://mohammadg.com
 *  Usage: /download.php?file=XXXX
 *
*/

ignore_user_abort(true);
set_time_limit(0); // disable the time limit for this script

if ($_GET['file'] != "") {
  $path_parts = pathinfo($_GET['file']);
  $file_name  = $path_parts['basename'];
  $path_to_filevault_dir = "./filevault";
  
  //Build absolute file path
  $path_to_file  = $path_to_filevault_dir . '/' . $file_name;

  //Safe string for files with spaces
  $path_to_file_no_space  = $path_to_filevault_dir . '/' . rawurlencode($file_name);
  
  //Check if file exists
  if (!file_exists($path_to_filevault_dir . '/' . $file_name)) {
    
    print '<div>Invalid file specified. Contact me know if this is a mistake!</div>';
    exit(1);
  }
  
  //Check if file is readable
  if (!is_readable($path_to_filevault_dir . '/' . $file_name)) {
    print "An error has occurred. Please notify the webmaster.";
    exit(1);
  }
  
  # detect MIME type (http://stackoverflow.com/a/32092523/1800854)
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  header('Content-Type: ' . finfo_file($finfo, $path_to_file));
  
  $finfo = finfo_open(FILEINFO_MIME_ENCODING);
  header('Content-Transfer-Encoding: ' . finfo_file($finfo, $path_to_file)); 

  header('Content-disposition: attachment; filename="' . basename($path_to_file) . '"'); 

  readfile($path_to_file_no_space);
}
else {
  print "'file' parameter is missing and is required.";
  exit(1);
}
?>
 <script type="text/javascript">
  window.close();
</script>