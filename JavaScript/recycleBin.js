/**
 * 
 */

var pageNo=1,pageSize=10,pageSum=1;
$(function () {
	initList();
	
	$('#previousPage').click(function(){
		if(pageNo - 1 >=1)
		{
			pageNo=pageNo - 1;
			initList();
		}
	});

	$('#nextPage').click(function(){
		if(pageNo + 1 <=pageSum)
		{
			pageNo=pageNo+ 1;
			initList();
		}
	});
});

function initList() {	
    var adminName = getCookie('adminName');
	var url = "../PHP/GetDelAdList.php";
	data={
			adminName:adminName,
			pageNo:pageNo,
			pageSize:pageSize
	};
	$.ajax({
		type: "post",  
		url: url,  
		dataType: "json",  
		data:data,
		error: function(){  
			PopupMessage('错误来自/PHP/GetDelAdList.php','recycleBinDiv_Message');
        },  
		success: function(respsone){ 
			if(respsone!=null)
				{
					pageSum = respsone.pageSum;
									    
				    for(var j=0;j<respsone.itemArr.length;j++)
				    	{
							var ADID=respsone.itemArr[j].ADID;
							var infoName=respsone.itemArr[j].infoName;
							var advTypeID=parseInt(respsone.itemArr[j].advType);
							var dtStart=respsone.itemArr[j].LifeAct;
							var dtEnd=respsone.itemArr[j].LifeDie;
							var Infostatus =parseInt(respsone.itemArr[j].Infostatus);
															
							var advType="普通广告";
							switch(advTypeID)
							{
								case 1:advType="转场信息";break;
								case 2:advType="普通广告";break;
								case 3:advType="通知信息";break;
							}									
							AddList(ADID,infoName,advType,dtStart,dtEnd,"infoList");
				    	}	
				    $('#pageNo').html(pageNo+"/"+pageSum);
				    $("#infoList").listview('refresh'); 
				}
		}
	});
}

function AddList(ADID,infoName,advType,dtStart,dtEnd,ulName) {
	var mli="<li><a id='info"+ADID+"'>"+infoName+"("+advType+")["+dtStart+"~"+dtEnd+"]</a><a href='#' data-icon='delete' onclick='delInfo(this)'>删除广告</a></li>";
	$("#" +ulName).append(mli);	
}

var selectInfoItem=null;
function delInfo(item) {	
	selectInfoItem=item;
	$("#DeleteDiv_Message").popup("open");
}

function deleteInfo() {
	if(selectInfoItem!=null)
		{
		 	var selADID =selectInfoItem.previousSibling.id.substring(4,selectInfoItem.previousSibling.id.length);		 				
			
			var url = "../PHP/DeleteInfoFromRecycle.php";
			data={
					ADID:selADID
			};
			$.ajax({
				type: "post",  
				url: url,  
				dataType: "json",  
				data:data,
				error: function(){  
					PopupMessage('错误来自/PHP/DeleteInfoFromRecycle.php','recycleBinDiv_Message');
		        },  
				success: function(respsone){ 
					if(respsone!=null)
						{							
							if(respsone.msg=="OK")
								{
									selectInfoItem.parentNode.remove();	
									$("#infoList").listview('refresh');	
								}
							else{
								PopupMessage(respsone.msg,'recycleBinDiv_Message');
							}
						}
					else {
						PopupMessage('错误来自/PHP/DeleteInfoFromRecycle.php','recycleBinDiv_Message');
					}
				}
			});
		}
}