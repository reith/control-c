<form id="password_recovery_form">
    <fieldset>
        <legend><?php echo _("Password Recovery"); ?>: </legend>
        <label><?php echo _("Please enter your E-mail address"); ?>: </label>
        <input type="text" name="email" />
        <input type="submit" value="<?php echo _("Send"); ?>" />
    </fieldset>
</form>


<script type="text/javascript">
    $('#password_recovery_form').submit( function(e) {
        e.preventDefault();
        $.ajax({data: $('#password_recovery_form').serialize(), dataType: 'json', url: '<?=__url__?>/libcc/account/password_recovery.php',  type: 'POST',
            success: function(j) {
                if ( j.error != null )
                    showError(j.error);
                else
                    showMessage(j.message, 4, null, '<?=__url__?>');
            }
        });
    });
</script>