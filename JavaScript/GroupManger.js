/**
 * 
 */
$(function () {
	GetGroup();
});

function GetGroup() {
	var adminName = getCookie("adminName");
	var url = "../PHP/getGroupList.php";
	var data={
			adminName:adminName
	}
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data: data,  
		error: function(){  
			PopupMessage('错误来自/PHP/getGroupList.php','groupDiv_Message');
        },  
		success: function(respsone){ 
			if(respsone!=null)
				{
				if(respsone.length>0)
					{			
					$("#GrpList").empty();
						for(var i=0;i<respsone.length;i++)
						{
							var GrpName=respsone[i].GrpName;
						    var GrpID=respsone[i].GrpID;
						    
							var mli="<li><a id='grp"+GrpID+"'>"+GrpName+"</a></li>";
							$("#GrpList").append(mli);
							
							$("#grp"+GrpID).on("tap",function(e){	 		
								
								var gi = e.target.id.substring(3,e.target.id.length);
								var gn =e.target.text;
								openGrp(gi,gn);
							});
						}		
						$("#GrpList").listview('refresh');				
					}
				else{PopupMessage("分组数为空",'groupDiv_Message');}				
				}
			else{PopupMessage('错误来自/PHP/getGroupList.php','groupDiv_Message');}
		}
	});
}

function CreatGrp() {
	var adminName = getCookie("adminName");
	var url = "../PHP/CreatGroup.php";
	var data={
			adminName:adminName,
			GrpName:$("#GrpName").val()
	}
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data: data,  
		error: function(){  
			PopupMessage('错误来自/PHP/CreatGroup.php','CreatGrpDiv_Message');
        },  
		success: function(respsone){ 
			if(respsone!=null)
				{
				if(respsone.msg=='OK')
					{			
						var GrpName=respsone.grpName;
					    var GrpID=respsone.grpID;
					    
						var mli="<li><a id='grp"+GrpID+"'>"+GrpName+"</a></li>";
						$("#GrpList").append(mli);		
						
						$("#grp"+GrpID).on("tap",function(e){	 
							var gi = e.target.id.substring(3,e.target.id.length);
							var gn =e.target.text;
							openGrp(gi,gn);
						});
						
						$("#GrpList").listview('refresh');	
						$("#GrpName").val('');
						window.location.href="#groupDiv";
					}
				else{PopupMessage(respsone.msg,'CreatGrpDiv_Message');}				
				}
			else{PopupMessage('错误来自/PHP/CreatGroup.php','CreatGrpDiv_Message');}
		}
	});
}

function openGrp(GrpID,GrpName) {
	$("#EGrpName").val(GrpName);
	$("#EGrpName").attr('name',GrpID);
	window.location.href="#EditGrpDiv";
}

function DeleteGrp() {	
	var adminName = getCookie("adminName");
	var url = "../PHP/DeleteGroup.php";
	var data={
			adminName:adminName,
			GrpID:$("#EGrpName").attr('name')
	}
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data: data,  
		error: function(){  
			PopupMessage('错误来自/PHP/DeleteGroup.php','EditGrpDiv_Message');
        },  
		success: function(respsone){ 
			if(respsone!=null)
				{
				if(respsone.msg=='OK')
					{			
						$('#grp'+$("#EGrpName").attr('name')).remove();
						$("#GrpList").listview('refresh');	
						window.location.href="#groupDiv";
					}
				else{PopupMessage(respsone.msg,'EditGrpDiv_Message');}				
				}
			else{PopupMessage('错误来自/PHP/DeleteGroup.php','EditGrpDiv_Message');}
		}
	});
}

function EditGrp() {
	var adminName = getCookie("adminName");
	var url = "../PHP/EditGroup.php";
	var data={
			adminName:adminName,
			GrpName:$("#EGrpName").val(),
			GrpID:$("#EGrpName").attr('name')
	}
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data: data,  
		error: function(){  
			PopupMessage('错误来自/PHP/EditGroup.php','EditGrpDiv_Message');
        },  
		success: function(respsone){ 
			if(respsone!=null)
				{
				if(respsone.msg=='OK')
					{			
						var GrpName=respsone.grpName;
						$('#grp'+$("#EGrpName").attr('name')).html(GrpName);		
						
						$("#GrpList").listview('refresh');			
						window.location.href="#groupDiv";
					}
				else{PopupMessage(respsone.msg,'EditGrpDiv_Message');}				
				}
			else{PopupMessage('错误来自/PHP/EditGroup.php','EditGrpDiv_Message');}
		}
	});
}
