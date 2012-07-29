<?php
?>
<script src='/script/compiler.js'></script>
<div class="comment">
<strong style="color: red;"><?php echo _("Note");?>: </strong>
<?php echo _("Your program must be valid for ^C standard compilers. So consider compiling your code with our compilers before submitting it."); ?>
</div>
<form id="ff" action="<?=Routing::genProc('compiler')?>" method="post" enctype="multipart/form-data" target="reswp">
<input checked=1 type="radio" name="src_send" id="isrc" value="i"><?php echo _("Insert your code") ?>: </input>
<br />
<textarea name="source" id="txtsrc" rows="15" class="code" style="width: 90%;"></textarea>
<br />
<input type="radio" id="fsrc" name="src_send" value="f"><?php echo _("Select the file"); ?>: </input>
<input id="sendFile" type="file" name="src_file" disabled="1"></input>
<br />
<label><?php echo _("Programming Language") ?>: </label>
<select id="lang" dir="ltr" name="lang">
  <option value="pas">Free Pascal</option>
  <option value="c" selected="1">C</option>
  <option value="cpp">C++</option>
  <option value="java">Java (JDE 7)</option>
</select>
&nbsp;&nbsp;&nbsp;
<label><?php echo _("Continue to"); ?>: </label>
<select name="stage">
  <option value="asm">Assembling</option>
  <option value="cmp" selected="1">Compiling</option>
  <option value="ld">Linking</option>
</select>
<br />
<label><?php echo _("Show"); ?>:&nbsp;</label>
  <input name="view_e" type="checkbox" disabled=1 checked=1><?php echo _("Errors"); ?></input>
  <input name="view_w" type="checkbox" checked=1><?php echo _("Warnings"); ?></input>
  <input name="view_s" type="checkbox"><?php echo _("Assembly Code"); ?></input>
<br />
<input id="submit" type="submit" value="<?php echo _("Do"); ?>" />
</form>

<iframe id="reswp" name="reswp" style="display: none;"></iframe>
<div id="result" class="comment"><?php echo _("JavaScript support must be enabled.."); ?></div>

<script type="text/javascript">
$(document).ready(function(){
validateForJava = function() {
    if ( ($('#lang').children(':selected').val()=='java') && $('#isrc').is(':checked'))
      {
          showMessage('<?php echo _("You shoud select a source file if your code is wrote in Java"); ?>');
          $('#submit').attr({'disabled':1});
      }
    else
        $('#submit').removeAttr('disabled');
}
    $('#lang').change(validateForJava);
    $('input:[type=radio]').change(validateForJava);
});
</script>