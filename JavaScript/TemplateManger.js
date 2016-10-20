/**
 * 
 */
$(function () {
	GetTemplate();
});

function GetTemplate() {
	var adminName = getCookie("adminName");
	var url = "../PHP/getTemplateList.php";
	var data={
			adminName:adminName
	}
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data: data,  
		error: function(){  
			PopupMessage('错误来自/PHP/getTemplateList.php','TemplateDiv_Message');
        },  
		success: function(respsone){ 
			if(respsone!=null)
				{
				if(respsone.length>0)
					{		
						for(var i=0;i<respsone.length;i++)
						{
							var GrpName=respsone[i].GrpName;
						    var GrpID=respsone[i].GrpID;
						    if(i==0)
					    	{
					    		AddGroup(GrpID,GrpName,"data-collapsed='false'");
					    		AddGrpList(GrpID,GrpName,true);
					    	}
						    else
						    {
						    	AddGroup(GrpID,GrpName,"");
						    	AddGrpList(GrpID,GrpName,false);
						    }
						    
						    var itemArr = respsone[i].itemArr;
						    for(var j=0;j<respsone[i].itemArr.length;j++)
					    	{
								var TemplateID=respsone[i].itemArr[j].TemplateID;
								var TemplateName=respsone[i].itemArr[j].TemplateName;
								var TemplateCycle=parseInt(respsone[i].itemArr[j].TemplateCycle);
								var TemTpList=respsone[i].itemArr[j].TemTpList;							
								
								var ulName='ul'+GrpID;								
								AddList(TemplateID,TemplateName,TemplateCycle,TemTpList,ulName);
					    	}
						    
						    $("#Grp"+GrpID +" ul").listview(); 
						}
					}
				else{PopupMessage("分组数为空",'TemplateDiv_Message');}				
				}
			else{PopupMessage('错误来自/PHP/getTemplateList.php','TemplateDiv_Message');}
		}
	});
}

function AddList(TemplateID,TemplateName,TemplateCycle,TemTpList,ulName) {
	var strtp="";
	for(var i=0;i<TemTpList.length;i++)
		{
			var tp="<p class='temTp'>" +TemTpList[i].TemplateStart+"---"+TemTpList[i].TemplateEnd+"</p>";
			strtp=strtp +tp
		}
	var mli="<li><a href='#'  id='Tem"+TemplateID+"'><h2>"+TemplateName+"</h2><p class='temcycle'>模板周期:"+TemplateCycle+"秒</p><p>模板时间段:"+strtp+"</p></a></li>";
	$("#" +ulName).append(mli);
		
	$("#Tem"+TemplateID).on("tap",function(e){	 		
		var ti = e.target.id.substring(3,e.target.id.length);				
		openTem(ti);
	});
}

function AddStrList(TemplateID,TemplateName,TemplateCycle,TemTpList,ulName) {
	var strtp="";
	for(var i=0;i<TemTpList.length;i++)
		{
			var tp="<p class='temTp'>" +TemTpList[i]+"</p>";
			strtp=strtp +tp
		}
	var mli="<li><a href='#'  id='Tem"+TemplateID+"'><h2>"+TemplateName+"</h2><p class='temcycle'>模板周期:"+TemplateCycle+"秒</p><p>模板时间段:"+strtp+"</p></a></li>";
	$("#" +ulName).append(mli);
		
	$("#Tem"+TemplateID).on("tap",function(e){	 		
		var ti = e.target.id.substring(3,e.target.id.length);				
		openTem(ti);
	});
}

function AddGrpList(GrpID,GrpName,isSelect) {
	var mli="";
	if(isSelect)
		{mli="<option selected='selected' value='"+GrpID+"'>"+GrpName+"</option>";}
	else{mli="<option value='"+GrpID+"'>"+GrpName+"</option>";}
	
	$("#GrpList").append(mli);
}

function AddGroup(GrpID,GrpName,collapsed) {
	var grpDiv ="<div data-role='collapsible' "+collapsed+" id='Grp"+GrpID+"'><h4>"+GrpName+"</h4><ul id='ul"+GrpID+"' data-role='listview' data-inset='true' class='ui-listview ui-listview-inset ui-corner-all ui-shadow'></ul>";
	$("#templateMain").append(grpDiv).collapsibleset(); 
}

function addTemTp() {
	var dts = $('#dtStart').val();
	var dte = $('#dtEnd').val();

	var isF=false;
	
	if(dts>=dte && dte!='00:00')
		{isF=true;}
	$.each($("#TemplateTpList .tpclass"),function(i,n){
	    var tp=n.text;
	    arrayTp = tp.split("---"); 
	    var oldts=arrayTp[0];
	    var oldte=arrayTp[1];	    
	    if( (dts>=oldts && dts<oldte) || (dte>oldts && dte<=oldte)  || (dts<=oldts && dte>=oldte))
	    	{isF=true;return;}
	    else {isF=false;}
	    
	    if(oldts==oldte && dte=='00:00'){isF=true;return;}
	});
	
	if(!isF)
		{
			var val=dts+"---"+dte;	
			var item="<li><a class='tpclass'>"+val+"</a><a href='#' data-icon='delete' onclick='delTemTp(this)'>删除时间段</a></li>";
			$("#TemplateTpList").append(item);
			$("#TemplateTpList").listview('refresh');	
    	}
}

function delTemTp(item) {
	item.parentNode.remove();	
	$("#TemplateTpList").listview('refresh');	
}

function openTem(temID) {
	var divid='Tem'+temID;
	var temName = $("#" +divid+" h2").html();
	$('#TemplateName').attr('tag',temID);
	$('#TemplateName').val(temName);
	var tmpcycle = $("#" +divid+" .temcycle").html();
	var temcycle=tmpcycle.substring(5,tmpcycle.length - 1);	
	$('#TemplateCycle').val(temcycle);	
	$('#TemplateCycle').attr('value',temcycle);	
		
	$("#TemplateTpList .tpclass").each(function(index, element) {
		$(this).parent().remove();
	});
	
	var ulid = $("#" +divid).parents('ul').attr('id')
	var grpid = ulid.substring(2,ulid.length);	
	$("#GrpList").val(grpid);
	
	$("#" +divid+" .temTp").each(function(index, element) {
	    var tp =$(this).text();
	    var item="<li><a class='tpclass'>"+tp+"</a><a href='#' data-icon='delete' onclick='delTemTp(this)'>删除时间段</a></li>";
		$("#TemplateTpList").append(item);		
	});
	
	$("#temTitle").html('编辑模板');
	$("#delTem").show();

	window.location.href="#CreatTemplateDiv";
	$("#CreatTemplateDiv").page();	
	$('#TemplateCycle').slider('refresh');
	$("#TemplateTpList").listview('refresh');	
}

function openCreat() {
	$("#temTitle").html('新建模板');
	$("#delTem").hide();
}

function CreatTemplate() {
	var TemplateID=$("#TemplateName").attr('tag');
	var TemplateName=$("#TemplateName").val();
	var TemplateGrp=$("#GrpList").val();
	var TemplateCycle=$("#TemplateCycle").val();
	
	var arrayTp=new Array();
	$.each($("#TemplateTpList .tpclass"),function(i,n){
	    var tp=n.text;
	    arrayTp.push(tp);
	});
	
	if(TemplateName==""){PopupMessage('模板名称不能为空!','CreatTemplateDiv_Message');return;}
	if(arrayTp.length<=0){PopupMessage('模板时间集合不能为空','CreatTemplateDiv_Message');return;}
	
	for(var i=0;i<arrayTp.length;i++)
		{
			Tp = arrayTp[i].split("---"); 
		    var oldts=Tp[0];
		    var oldte=Tp[1];	    
		    var hourStart=parseInt(oldts.split(":")[0]);
		    var minuteStart=parseInt(oldts.split(":")[1]);
		    var hourEnd=parseInt(oldte.split(":")[0]);
		    var minuteEnd=parseInt(oldte.split(":")[1]);
		    
		    var timeLength=(hourEnd - hourStart)*3600 + (minuteEnd - minuteStart)*60;
		    
		    if(timeLength%parseInt(TemplateCycle)!=0)
		    	{PopupMessage('时间段集合的时间长度必须是模板周期的整数倍!','CreatTemplateDiv_Message');return;}
		}
	
	var adminName = getCookie("adminName");
	
	var temTitle=$("#temTitle").html();
	switch (temTitle) {
	case '新建模板':		
		var url = "../PHP/CreatTemplate.php";
		var data={
				adminName:adminName,
				TemplateName:TemplateName,
				TemplateGrp:TemplateGrp,
				TemplateCycle:TemplateCycle,
				TemplateTpList:arrayTp
		}
		$.ajax({  
			type: "post",  
			url: url,  
			dataType: "json",  
			data: data,  
			error: function(){  
				PopupMessage('错误来自/PHP/CreatTemplate.php','CreatTemplateDiv_Message');
	        },  
			success: function(respsone){ 
				if(respsone!=null)
					{
					if(respsone.msg=='OK')
						{			
							var TemplateID =respsone.TemplateID;
							
							var ulName='ul'+TemplateGrp;								
							AddList(TemplateID,TemplateName,TemplateCycle,TemplateTpList,ulName);
							
							window.location.href="#TemplateDiv";
							
							$("#Grp"+TemplateGrp +" ul").listview(); 
						}
					else{PopupMessage(respsone.msg,'CreatTemplateDiv_Message');}				
					}
				else{PopupMessage('错误来自/PHP/CreatTemplate.php','CreatTemplateDiv_Message');}
			}
		});			
		break;

	case '编辑模板':
		var url = "../PHP/EditTemplate.php";
		var data={
				adminName:adminName,
				TemplateID:TemplateID,
				TemplateName:TemplateName,
				TemplateGrp:TemplateGrp,
				TemplateCycle:TemplateCycle,
				TemplateTpList:arrayTp
		}
		$.ajax({  
			type: "post",  
			url: url,  
			dataType: "json",  
			data: data,  
			error: function(){  
				PopupMessage('错误来自/PHP/CreatTemplate.php','CreatTemplateDiv_Message');
	        },  
			success: function(respsone){ 
				if(respsone!=null)
					{
					if(respsone.msg=='OK')
						{						
							var ulName='ul'+TemplateGrp;
							
							//var ulid = $("#Tem"+TemplateID).parents('ul').attr('id')
							$("#Tem"+TemplateID).parent().remove();
							AddStrList(TemplateID,TemplateName,TemplateCycle,arrayTp,ulName);
							window.location.href="#TemplateDiv";		
							$("#TemplateDiv").page();	
							$("#Grp"+TemplateGrp +" ul").listview('refresh'); 
						}
					else{PopupMessage(respsone.msg,'CreatTemplateDiv_Message');}				
					}
				else{PopupMessage('错误来自/PHP/CreatTemplate.php','CreatTemplateDiv_Message');}
			}
		});			
	break;
	} 	
}

function DeleteTem() {
	var adminName = getCookie("adminName");
	var TemplateID=$("#TemplateName").attr('tag')
	var url = "../PHP/DeleteTemplate.php";
	var data={
			adminName:adminName,
			TemplateID:TemplateID
	}
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data: data,  
		error: function(){  
			PopupMessage('错误来自/PHP/DeleteTemplate.php','CreatTemplateDiv_Message');
        },  
		success: function(respsone){ 
			if(respsone!=null)
				{
				if(respsone.msg=='OK')
					{			
						$("#Tem"+TemplateID).parent().remove();
						window.location.href="#TemplateDiv";		
						$("#TemplateDiv").page();	
					}
				else{PopupMessage(respsone.msg,'CreatTemplateDiv_Message');}				
				}
			else{PopupMessage('错误来自/PHP/DeleteTemplate.php','CreatTemplateDiv_Message');}
		}
	});
}