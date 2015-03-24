<!--REITH:  THIS IS MAIN FRAME VISITOR SEES-->
<!-- NEED NO TRANSLATE SO FAR -->
<!-- News... -->
<?php
require_once 'libcc/general.functions.php';
?>

<ul>
  <?php
     $err=getTopNotices(5);
      if ($err)
 	echo "<div class='errorText'>$err</div>";
  ?>
  <li><a href="./?sh=ntc" ><?php echo _('Archive'); ?></a></li>
</ul>
<!-- about -->

<div style="padding-top:30px; width:60%; border-top: gray dotted 1px;">
<?php
echo '<h1>'. _('about') . '</h1><p>';
echo _('Please read the project page:');
?>
 <a href="http://12eith.com/code/^C/">http://12eith.com/code/^C</a>
</p>
</div>
