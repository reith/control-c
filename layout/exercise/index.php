<div class="span4">
<?=_("This is help page for exercises.");?>
<br>
<?php
	echo _("You can search for exerises from here: ");
	printf( "<a href='%s'>Search</a>", Routing::url('exercise/search') );
?>
<br>
</div>

<div class="span4">
<?php
printf( '<h3>%s</h3><hr>', _('Student') );
echo _('You can see just registered courses');
echo _('You can see just sent answer');
?>
</div>

<div class="span4">
<?php
printf( '<h3>%s</h3><hr>', _('Teacher') );
echo _('You can see your own courses ');
?>
</div>
