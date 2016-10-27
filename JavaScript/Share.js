/**
 * 
 */
function goBack(backPath,pageName) {
	window.location=backPath;
	if(pageName!="")
	{showItem(pageName);}
}
//MesageBox
function PopupMessage(strMessage,DivID) {
	$("#"+DivID).empty();
	$("#"+DivID).append('<p>'+strMessage+'</p>')
	$("#"+DivID).popup('open');
}

//显示界面切换
function showItem(id){
	$('div.m-item').hide();
	$(id).show();
	
	switch(id)
	{
		case '#m-drafts':{
			$('#auditInfo').show();
			$('#publishInfo').hide();
			$('#creatAdv').show();
		};break;
		case '#m-audit':{
			$('#auditInfo').hide();
			$('#publishInfo').show();
			$('#creatAdv').hide();
		};break;
		case '#m-publish':{
			$('#auditInfo').hide();
			$('#publishInfo').hide();
			$('#creatAdv').hide();
		};break;
	}
}