<?php 
//   REITH: SIGN OUT PROCESS
  require "../session.php";

  signOut();
  header("Location: ".$siteURL."index.php");
?>
