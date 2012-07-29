// if ( TableForm !== 'undefined' ) eixt;
var TableForm = function(form, table, nuldlg) {
  this.nldlg = nuldlg;
  this.$form = $('#'+form);
  this.$table = $('#'+table);
  this.$form.append(
  '<div id="'+form+'page_nav" style="text-align: center; padding-top:10px;"><input type="hidden" name="from" id="'+form+'from" value="0" /><label><?=_("Number of Records each page");?>: </label>'+
  '<input type="text" size="2" maxlength="2" value="10" id="'+form+'limit" name="limit" />'+
  '<input type="submit" value="<?=_("Previous")?>" id="'+form+'previous" class="hidden"></input><input type="submit" value="<?=_("Next")?>" id="'+form+'next" class="hidden" ></input></div>');
  this.$limit = $('#'+form+'limit');
  this.$next = $('#'+form+'next');
  this.$previous = $('#'+form+'previous');
  this.$from = $('#'+form+'from');
  
  this.$loading = $('#loading');
  this.json = '';
  
  this.dynamicButtons();
}
TableForm.prototype.submitForm = function(event, url, form)
{
   event.preventDefault();
   this.$table.html('');
//    this.$loading.removeClass('hidden').fadeIn('slow');
   
   if (!(this.nldlg == null))
     this.nldlg.hide('slow');
   
   var TableForm=this;
   $.ajax({
      type: "GET",
      url: url,
      data: $(form).serialize(),
      dataType: "json",
      cache: false,
      success: function(json) {TableForm.json=json; TableForm.operations();}
   });
}
TableForm.prototype.operations = function() {
  if (jsonError(this.json)){
//     this.$loading.addClass('hidden');
    return (false);
  }
  
  if (!this.json.count){
//     this.$loading.addClass('hidden');
    this.$next.addClass('hidden');
    
    var msg='اطلاعاتی برای نمایش وجود ندارد! <br /> از فیلدهای مناسبی برای نمایش اطلاعات استفاده کنید.'
    if (this.nldlg == null)
      showMessage(msg);
    else
      this.nldlg.html(msg).show('slow');
    
    return (false);
  }

    
  this.$table.append('<tr>'+this.json.th+'</tr>');
  this.$limit.val()==this.json.count ? this.$next.removeClass('hidden') : this.$next.addClass('hidden');
  if (this.customOperations) this.customOperations();
//   this.$loading.addClass('hidden');
};

TableForm.prototype.dynamicButtons = function()
{
    var This=this;
    this.$limit.change(function(){
	This.$from.val(0);
	This.$next.addClass('hidden');
	This.$previous.addClass('hidden');
    });
    this.$next.click(function(){
	This.$previous.removeClass('hidden');
	This.$from.val()>0 && This.$previous.removeClass('hidden');
	This.$from.val(Number(This.$from.val())+Number(This.$limit.val()));
    });
    this.$previous.click(function(){
	This.$next.removeClass('hidden');
	(num=Number(This.$from.val())-Number(This.$limit.val())) > 0 ? This.$from.val(num):
	This.$from.val(0) && This.$previous.addClass('hidden');
    });
}
