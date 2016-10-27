/**
 * 
 */
var selectADID=0,selectPn=0,selectIn=0;
var ueditor;
$(function () {
	ueditor = UE.getEditor('container');
	 initList();
	 
	 $("#m-drafts").on("swipeleft",function(){
		  showItem("#m-audit");
		});
	 
	 
	 $("#m-audit").on("swipeleft",function(){
		 showItem("#m-publish");
		});
	 
	 $("#m-audit").on("swiperight",function(){
		 showItem("#m-drafts");
		});
	 
	 $("#m-publish").on("swipeleft",function(){
		 showItem("#m-user");
		});
	 
	 $("#m-publish").on("swiperight",function(){
		 showItem("#m-audit");
		});
	 
	 $("#m-user").on("swiperight",function(){
		 showItem("#m-publish");
		});
	 
	 $("#imgList").on("swipeleft",function(){
		 showItem("#m-ST");
		});
	 
	 $("#m-ST").on("swiperight",function(){
		 showItem("#imgList");
		});
	 
	 $("#Div-itemTxt").on("swipeleft",function(){
		 showItem("#Div-itemAttribute");
		});
	 
	 $("#Div-itemAttribute").on("swiperight",function(){
		 showItem("#Div-itemTxt");
		});
	 
	 $("#Div-TemST").on("swipeleft",function(){
		 showItem("#Div-TemplateList");
		});
	 
	 $("#Div-TemplateList").on("swiperight",function(){
		 showItem("#Div-TemST");
		});
	 
	});

//列表初始化
function initList() {	
    var adminName = getCookie('adminName');
	var url = "../PHP/GetAdList.php";
	data={
			adminName:adminName
	};
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data:data,
		error: function(){  
			PopupMessage('错误来自/PHP/GetAdList.php','AdvListDiv_Message');
        },  
		success: function(respsone){ 
			if(respsone!=null)
				{
					for(var i=0;i<respsone.length;i++)
						{
						    var GrpName=respsone[i].GrpName;
						    var GrpID=respsone[i].GrpID;
						    if(i==0)
						    	{
						    		AddGroup(GrpID,GrpName,"data-collapsed='false'",respsone[i].itemArr.length);
						    		AddGrpList(GrpID,GrpName,true);
						    	}
						    else
						    {
						    	AddGroup(GrpID,GrpName,"",respsone[i].itemArr.length);
						    	AddGrpList(GrpID,GrpName,false);
						    }
						    
						    
						    var itemArr = respsone[i].itemArr;
						    for(var j=0;j<respsone[i].itemArr.length;j++)
						    	{
									var ADID=respsone[i].itemArr[j].ADID;
									var infoName=respsone[i].itemArr[j].infoName;
									var advTypeID=parseInt(respsone[i].itemArr[j].advType);
									var dtStart=respsone[i].itemArr[j].LifeAct;
									var dtEnd=respsone[i].itemArr[j].LifeDie;
									var Infostatus =parseInt(respsone[i].itemArr[j].Infostatus);
									
									var ulName='uld'+GrpID;
									
									switch(Infostatus)
									{
										case 3:{ulName='ula'+GrpID;};break;
										case 4:{ulName='ulp'+GrpID;};break;
									}
									
									var advType="普通广告";
									switch(advTypeID)
									{
										case 1:advType="转场信息";break;
										case 2:advType="普通广告";break;
										case 3:advType="通知信息";break;
									}									
									AddList(ADID,infoName,advType,dtStart,dtEnd,ulName);
						    	}
						}
					$("#m-drafts ul").listview(); 
					$("#m-audit ul").listview(); 
					$("#m-publish ul").listview(); 
				}
		}
	});
}

//新建广告
function CreatAdv() {	
	var infoName=$("#infoName").val();
	var advType=$("#advType").val();
	var dtStart=$("#dtStart").val();
	var dtEnd=$("#dtEnd").val();	
	var GrpID=$("#GrpList").val();	
	
	if(infoName.trim()=="")
	{return;}
	if(dtStart.trim()=="")
	{return;}
	if(dtEnd.trim()=="")
	{return;}
	
	data={
			infoName:infoName,
			advType:advType,
			dtStart:dtStart,
			dtEnd:dtEnd,
			GrpID:GrpID
	};
	
	var url = "../PHP/CreatAdv.php";
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data:data,
		error: function(){  
			PopupMessage('错误来自/PHP/CreatAdv.php','CreatAdvDiv_Message');
        },  
		success: function(respsone){ 
			if(respsone!=null)
				{
				if(respsone.msg=="OK")
					{
						AddList(respsone.ADID,infoName,advType,dtStart,dtEnd,'uld'+GrpID);
						$('#uld'+GrpID).listview('refresh');
						var itemCount = parseInt($('#spand'+GrpID).html());
						$('#spand'+GrpID).html(itemCount+1);
					}
				else{PopupMessage(respsone.msg,'CreatAdvDiv_Message');}
				}
		}
	});		
}
//删除广告
function deleteInfo() {
	CloseDialog("dialog_menu");
	var url = "../PHP/deleteInfoToDB.php";
	data={
			ADID:selectADID
	};
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data:data,
		error: function(){  
			PopupMessage('错误来自/PHP/deleteInfoToDB.php','AdvListDiv_Message');
        },  
		success: function(respsone){			
			if(respsone!=null)
				{
				if(respsone.msg=="OK")
					{
					var DivGrpID = $("#info"+selectADID).parents('.ui-collapsible').eq(0).attr('id');
					var GrpID= DivGrpID.substring(3, DivGrpID.length);
					$("#info"+selectADID).remove();
					
					var itemCount = parseInt($('#span'+GrpID).html());
					if(itemCount>0)
						{
						$('#span'+GrpID).html(itemCount-1);
						}
					}
				else{PopupMessage(respsone.msg,'AdvListDiv_Message');}
				}
			else{PopupMessage('错误来自/PHP/deleteInfoToDB.php','AdvListDiv_Message');}
		}
	});	
}
//复制广告
function copyInfo() {
	CloseDialog("dialog_menu");
	var url = "../PHP/copyInfoToDB.php";
	data={
			ADID:selectADID
	};
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data:data,
		error: function(){  
			PopupMessage('错误来自/PHP/copyInfoToDB.php','AdvListDiv_Message');
        },  
		success: function(respsone){			
			if(respsone!=null)
				{
				if(respsone.msg=="OK")
					{
					AddList(respsone.ADID,respsone.infoName,respsone.advType,respsone.dtStart,respsone.dtEnd,'uld'+respsone.GrpID);
					$('#uld'+respsone.GrpID).listview('refresh');
					var itemCount = parseInt($('#spand'+respsone.GrpID).html());
					$('#spand'+respsone.GrpID).html(itemCount+1);
					}
				else{PopupMessage(respsone.msg,'AdvListDiv_Message');}
				}
			else{PopupMessage('错误来自/PHP/copyInfoToDB.php','AdvListDiv_Message');}
		}
	});	
}
//审核广告
function auditInfo() {
	CloseDialog("dialog_menu");
	var url = "../PHP/auditInfoToDB.php";
	data={
			ADID:selectADID
	};
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data:data,
		error: function(){  
			PopupMessage('错误来自/PHP/auditInfoToDB.php','AdvListDiv_Message');
        },  
		success: function(respsone){			
			if(respsone!=null)
				{
				if(respsone.msg=="OK")
					{
					var DivGrpID = $("#info"+selectADID).parents('.ui-collapsible').eq(0).attr('id');
					var GrpID= DivGrpID.substring(4, DivGrpID.length);
					var txt=$("#info"+selectADID).html();
					
					$("#info"+selectADID).remove();
					
					var itemCount = parseInt($('#spand'+GrpID).html());
					if(itemCount>0)
						{
						$('#spand'+GrpID).html(itemCount-1);
						}

					var infoName=txt.substring(0,txt.indexOf('('));
					var advType=txt.substring(txt.indexOf('(') + 1,txt.indexOf(')'));
					var dtStart=txt.substring(txt.indexOf('[') + 1,txt.indexOf('~'));
					var dtEnd=txt.substring(txt.indexOf('~') + 1,txt.indexOf(']'));
					
					AddList(selectADID,infoName,advType,dtStart,dtEnd,'ula'+GrpID);
					$('#ula'+GrpID).listview('refresh');
					}
				else{PopupMessage(respsone.msg,'AdvListDiv_Message');}
				}
			else{PopupMessage('错误来自/PHP/auditInfoToDB.php','AdvListDiv_Message');}
		}
	});	
}
//发布广告
function publishInfo() {
	CloseDialog("dialog_menu");
	var url = "../PHP/publishInfoToDB.php";
	data={
			ADID:selectADID
	};
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data:data,
		error: function(){  
			PopupMessage('错误来自/PHP/publishInfoToDB.php','AdvListDiv_Message');
        },  
		success: function(respsone){			
			if(respsone!=null)
				{
				if(respsone.msg=="OK")
					{
					var DivGrpID = $("#info"+selectADID).parents('.ui-collapsible').eq(0).attr('id');
					var GrpID= DivGrpID.substring(4, DivGrpID.length);
					var txt=$("#info"+selectADID).html();
					$("#info"+selectADID).remove();
					
					var itemCount = parseInt($('#spana'+GrpID).html());
					if(itemCount>0)
						{
						$('#spana'+GrpID).html(itemCount-1);
						}
					var infoName=txt.substring(0,txt.indexOf('('));
					var advType=txt.substring(txt.indexOf('(') + 1,txt.indexOf(')'));
					var dtStart=txt.substring(txt.indexOf('[') + 1,txt.indexOf('~'));
					var dtEnd=txt.substring(txt.indexOf('~') + 1,txt.indexOf(']'));
					
					AddList(selectADID,infoName,advType,dtStart,dtEnd,'ulp'+GrpID);
					$('#ulp'+GrpID).listview('refresh');
					}
				else{PopupMessage(respsone.msg,'AdvListDiv_Message');}
				}
			else{PopupMessage('错误来自/PHP/publishInfoToDB.php','AdvListDiv_Message');}
		}
	});	
}
//向列表添加Item
function AddList(ADID,infoName,advType,dtStart,dtEnd,ulName) {
	var mli="<li><a id='info"+ADID+"'>"+infoName+"("+advType+")["+dtStart+"~"+dtEnd+"]</a></li>";
	$("#" +ulName).append(mli);
	
	var GrpID=ulName.substring(2,ulName.length);
	var itemCount = parseInt($('#span'+GrpID).html());
	$('#span'+GrpID).html(itemCount+1);
	
	$("#info"+ADID).on("tap",function(e){	 
		openItem(ADID);
		window.location.href="#ItemDiv";
	});
	
	$("#info"+ADID).on("taphold",function(event){
		selectADID=ADID;
		$("#dialog_menu").popup('open');				
	  });
}
//向列表添加Item
function AddGrpList(GrpID,GrpName,isSelect) {
	var mli="";
	if(isSelect)
		{mli="<option selected='selected' value='"+GrpID+"'>"+GrpName+"</option>";}
	else{mli="<option value='"+GrpID+"'>"+GrpName+"</option>";}
	
	$("#GrpList").append(mli);
}
function AddGroup(GrpID,GrpName,collapsed,itemCount) {
	itemCount=0;
	var draftsGrpID="d"+GrpID;
	var grpDiv ="<div data-role='collapsible' "+collapsed+" id='Grp"+draftsGrpID+"'><h4>"+GrpName+"<span id='span"+draftsGrpID+"' class='ui-li-count'>"+itemCount+"</span></h4><ul id='ul"+draftsGrpID+"' data-role='listview' data-inset='true' class='ui-listview ui-listview-inset ui-corner-all ui-shadow'></ul>";
	$("#m-drafts").append(grpDiv).collapsibleset(); 
	
	var auditGrpID="a"+GrpID;
	grpDiv ="<div data-role='collapsible' "+collapsed+" id='Grp"+auditGrpID+"'><h4>"+GrpName+"<span id='span"+auditGrpID+"' class='ui-li-count'>"+itemCount+"</span></h4><ul id='ul"+auditGrpID+"' data-role='listview' data-inset='true' class='ui-listview ui-listview-inset ui-corner-all ui-shadow'></ul>";
	$("#m-audit").append(grpDiv).collapsibleset(); 
	
	var publishGrpID="p"+GrpID;
	grpDiv ="<div data-role='collapsible' "+collapsed+" id='Grp"+publishGrpID+"'><h4>"+GrpName+"<span id='span"+publishGrpID+"' class='ui-li-count'>"+itemCount+"</span></h4><ul id='ul"+publishGrpID+"' data-role='listview' data-inset='true' class='ui-listview ui-listview-inset ui-corner-all ui-shadow'></ul>";
	$("#m-publish").append(grpDiv).collapsibleset(); 
}

function CloseDialog(DialogID) {
	$("#"+DialogID).popup("close");
}
//取广告信息存入session，画图
function openItem(ADID) {
	selectADID=ADID;
	var url = "../PHP/openItem.php";
	data={
			ADID:ADID
	};
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data:data,
		error: function(){  
			PopupMessage('错误来自/PHP/openItem.php','ItemDiv_Message');
        },  
		success: function(respsone){ 
		 if(respsone!=null){
			 showItem('#imgList');	
			 
			 if(respsone.length>0)
				 {
				///*
				 $("#imgList").empty();
				 	for(var i=0;i<respsone.length;i++)
				 	{
				 		var Scale = parseInt($(document.body).width()/128);				 			
				 		var imgID="A"+ADID+"P"+respsone[i].pageName+"I"+respsone[i].ItemName;
				 		 var arrayItem =ParseHtml(respsone[i].texts);
				 		 var JsonStr =JSON.stringify(arrayItem);
				 		var myDate = new Date();
				 		var imgSrc = "../PHP/DrawImage.php?ADID="+ADID+"&pageName="+respsone[i].pageName+"&itemName="+respsone[i].ItemName+"&Size=128,16&Scale="+Scale+"&Data="+window.encodeURIComponent(JsonStr)+"&time="+myDate;				 		
				 		var img="<img id='"+imgID+"'  src='"+imgSrc+"'/>";				 		
				 		$("#imgList").append(img);
				 		
				 		$("#"+imgID).on("taphold",function(event){		
				 			var imgSID = event.target.id.toString();
				 			selectADID= imgSID.substring(imgSID.indexOf('A') + 1, imgSID.indexOf('P'));
				 			selectPn = imgSID.substring(imgSID.indexOf('P') + 1, imgSID.indexOf('I'));
				 			selectIn = imgSID.substring(imgSID.indexOf('I') + 1, imgSID.length);
				 			
				 			window.location.href="#ItemEdit";		
				 			showItem("#Div-itemTxt");
				 			EditItem();
							  });
				 	}
				 	var mySTDate = new Date();				 	
			 		var imgSTSrc = "../PHP/DrawTemST.php?srceenW="+parseInt($(document.body).width())+"&ADID="+ADID+"&time="+myDate;				 		
			 		$("#ImgTemST").attr("src",imgSTSrc);
			 		ImgSTClick();
			 		$("#ImgTemST").on("taphold",function(event){
			 			window.location.href="#TemSTEdit";		
			 			initTemSTData();
						  });
			 		//*/
			 		//window.location="../PHP/Test.php?ADID="+ADID;
				 }
			 else{PopupMessage('广告条目数为0','ItemDiv_Message');}
		 }
		 else{PopupMessage('读取广告异常','ItemDiv_Message');}
		}
	});
}
//显示方式改变
function selectChange(o){
	 img1.src=o.value;
	}
//显示选择条目数据
function EditItem() {
		data={
				ADID:selectADID,
				pageName:selectPn,
				itemName:selectIn
		};
		$.ajax({  
			type: "post",  
			url: "../PHP/showItem.php",  
			dataType: "json",  
			data:data,
			error: function(){  
				PopupMessage('错误来自/PHP/showItem.php','ItemDiv_Message');
	        },  
			success: function(respsone){ 
			 if(respsone!=null){
				 if(respsone.msg=="OK")
					 {
					 	$("#itemleft").val(respsone.x1);
					 	$("#itemtop").val(respsone.y1);					 	
					 	$("#itemwidth").val(respsone.x2-respsone.x1+1);
					 	$("#itemheight").val(respsone.y2 -respsone.y1 +1);
					 	$("#playClc").val(respsone.cycCount);
					 	
					 	$("#displayMode option[selected='selected']").attr("selected",false);
					 	$("#displayMode option").eq(parseInt(respsone.displayType)).attr("selected",true);
					 	//$("#displayMode option").eq(1).attr("selected",true);
					 	$("#displayMode").val($("#displayMode option").eq(parseInt(respsone.displayType)).val());
					 	$("#displayMode").selectmenu('refresh');
					 	img1.src=$("#displayMode").val();
					 	$("#speedID").val(respsone.speedID);

					 	$("#stayMode option[value='"+respsone.lastStopMode+"']").attr("selected", "selected");
					 	$("#stayMode").slider("refresh");
					 			 	
					 	$("#stayTime").val(respsone.stayTime);
					 	$("#rollSpace").val(respsone.rollSpace);
					 	
					 	ueditor.setContent(respsone.texts);
					 }
				 else
					 {PopupMessage('错误来自/PHP/showItem.php'+respsone.msg,'ItemDiv_Message');}
			 }
			 else{PopupMessage('读取广告异常','ItemDiv_Message');}
			 showItem('#Div-itemTxt');
			}
		});
}
//删除选择条目数据
function DeleteItem() {
		data={
				ADID:selectADID,
				pageName:selectPn,
				itemName:selectIn
		};
		$.ajax({  
			type: "post",  
			url: "../PHP/deleteItem.php",  
			dataType: "json",  
			data:data,
			error: function(){  
				PopupMessage('错误来自/PHP/DeleteItem.php','ItemDiv_Message');
	        },  
			success: function(respsone){ 
			 if(respsone!=null){
				 if(respsone.msg=="OK")
					 {
				 		var imgID="A"+selectADID+"P"+selectPn+"I"+selectIn;
				 		$("#"+imgID).remove();
					 }
				 else
					 {PopupMessage('错误来自/PHP/DeleteItem.php'+respsone.msg,'ItemDiv_Message');}
			 }
			 else{PopupMessage('读取广告异常','ItemDiv_Message');}
			}
		});
}
//保存Item信息并写入session
function SaveItem() {
	data={
			ADID:selectADID,
			pageName:selectPn,
			itemName:selectIn,
			x1:parseInt($("#itemleft").val()),
			y1:parseInt($("#itemtop").val()),
			x2:parseInt($("#itemleft").val()) + parseInt($("#itemwidth").val()) - 1,
			y2:parseInt($("#itemleft").val()) + parseInt($("#itemheight").val()) - 1,
			cycCount:$("#playClc").val(),
			displayType:$("#displayMode").prop('selectedIndex'),
			speedID:$("#speedID").val(),
			lastStopMode:$("#stayMode").val(),
			stayTime:$("#stayTime").val(),
			rollSpace:$("#rollSpace").val(),
			texts:ueditor.getContent()
	};
	$.ajax({  
		type: "post",  
		url: "../PHP/saveItemBySession.php",  
		dataType: "json",  
		data:data,
		error: function(){  
			PopupMessage('错误来自/PHP/saveItemBySession.php','ItemDiv_Message');
        },  
		success: function(respsone){		
		 if(respsone!=null){
			 if(respsone.msg=="OK")
				 {
				 var arrayItem =ParseHtml(ueditor.getContent());
				 if(parseInt(respsone.isRefresh)==1)
					 {
					    var JsonStr =JSON.stringify(arrayItem);

						var imgID="A"+selectADID+"P"+selectPn+"I"+selectIn;
						var Scale = parseInt($(document.body).width()/128);		
						var myDate = new Date();
						var imgSrc="../PHP/DrawImage.php?ADID="+selectADID+"&pageName="+selectPn+"&itemName="+selectIn+"&Size=128,16&Scale="+Scale+"&Data="+window.encodeURIComponent(JsonStr)+"&time="+myDate;
						$("#" +imgID).attr("src",imgSrc);

					 }
				 PopupMessage('保存成功!','ItemDiv_Message');
				 }			 
			 else
				 {PopupMessage('错误来自/PHP/showItem.php'+respsone.msg,'ItemDiv_Message');}
		 }
		 else{PopupMessage('保存广告异常','ItemDiv_Message');}
		 showItem('#imgList');
		}
	});
}
//新增条目饼写入Session
function AddItem() {
	var url = "../PHP/addItem.php";
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		error: function(){  
			PopupMessage('错误来自/PHP/addItem.php','ItemDiv_Message');
        },  
		success: function(respsone){ 
		 if(respsone!=null){
		 		var Scale = parseInt($(document.body).width()/128);
		 		var imgID="A"+selectADID+"P"+respsone.pageName+"I"+respsone.ItemName;
		 		 var arrayItem =ParseHtml(respsone.texts);
		 		 var JsonStr =JSON.stringify(arrayItem);
		 		var myDate = new Date();
		 		var imgSrc = "../PHP/DrawImage.php?ADID="+selectADID+"&pageName="+respsone.pageName+"&itemName="+respsone.ItemName+"&Size=128,16&Scale="+Scale+"&Data="+window.encodeURIComponent(JsonStr)+"&time="+myDate;				 		
		 		var img="<img id='"+imgID+"'  src='"+imgSrc+"'/>";				 		
		 		$("#imgList").append(img);
		 		
		 		$("#"+imgID).on("taphold",function(event){		
		 			var imgSID = event.target.id.toString();
		 			selectADID= imgSID.substring(imgSID.indexOf('A') + 1, imgSID.indexOf('P'));
		 			selectPn = imgSID.substring(imgSID.indexOf('P') + 1, imgSID.indexOf('I'));
		 			selectIn = imgSID.substring(imgSID.indexOf('I') + 1, imgSID.length);
		 			
		 			window.location.href="#ItemEdit";		
		 			showItem("#Div-itemTxt");
		 			EditItem();
					  });		 		
		 }
		 else{PopupMessage('读取广告异常','ItemDiv_Message');}
		}
	});		
}
//保存广告
function SaveinfoDB() {
	var url = "../PHP/SaveInfoToDB.php";
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		error: function(){  
			PopupMessage('错误来自/PHP/SaveInfoToDB.php','AdvListDiv_Message');
        },  
		success: function(respsone){
			showItem('#m-drafts');
			if(respsone!=null)
				{
				if(respsone.msg=="OK")
					{
					PopupMessage('保存成功','AdvListDiv_Message');
					}
				else{PopupMessage(respsone.msg,'AdvListDiv_Message');}
				}
		}
	});		
}
//解析html文本
function ParseHtml(strHtml) {
	 var root = UE.htmlparser(strHtml, true);
	 var arrNode = new Array();
	 for(var i=0;i<root.children.length;i++)
		 {
		 		var node=root.children[i];
		 		fontName='宋体';fontSize=12;
		 		arrNode.push(ParseNode(node));
		 }	 
	 return arrNode;
}
var fontName='宋体';var fontSize=12;
function ParseNode(node) {
	var arrNode = new Array()
	for(var i=0;i<node.children.length;i++)
	 {
	 		var nodeType = node.children[i].tagName;
	 		switch(nodeType)
	 		{
	 			case "Text":{};break;
	 			case "span":{
	 				var style = node.children[i].attrs['style'];
	 				var ArraySl = style.split(";"); 
 					for(var s=0;s<ArraySl.length;s++)
 						{
 							var attr =ArraySl[s].trim();
 							if(attr.trim()!="")
 								{
 								 	var attrArr =attr.split(":");
 								 	if(attrArr.length>=2)
 								 		{
 								 			var attrName=attrArr[0];
 								 			switch(attrName.trim())
 								 			{
 								 			case 'font-family':{fontName=attrArr[1].split(",")[0].trim();};break;
 	                                        case 'font-size':{fontSize=parseInt(attrArr[1].replace("px", ""));};break;
 								 			}
 								 		}
 								}
 						}
 					arrNode = arrNode.concat(ParseNode(node.children[i]));
	 			};break;
	 			case "img":{
	 				var data={
	 						fontName:fontName,
	 						fontSize:fontSize,
	 						itemType:1,
	 						value:node.children[i].attrs['src']					
	 				};
	 				arrNode.push(data);
	 			};break;
	 			default:{
	 				if(node.tagName=='p'){fontName='宋体';fontSize=12;}
	 				var data={
	 						fontName:fontName,
	 						fontSize:fontSize,
	 						itemType:0,
	 						value:node.children[i].data	 						
	 				};
	 				arrNode.push(data);
	 			};break;
	 		}
	 }
	return arrNode;
}
//单击排期图
function ImgSTClick() {
	$("#ImgTemST").on("tap",function(e){	 
		var left = e.pageX - $("#ImgTemST").offset().left;
	    var top = e.pageY - $("#ImgTemST").offset().top;
	    
	    var myDate = new Date();				 	
 		var imgSTSrc = "../PHP/ImgSTClick.php?left="+left+"&top="+top+"&time="+myDate;				 		
 		$("#ImgTemST").attr("src",imgSTSrc);
      });  
}
//初始化排期数据列表（排期图长按激发）
function initTemSTData()
{
	$.ajax({  
		type: "post",  
		url: "../PHP/showTemST.php",  
		dataType: "json",  
		error: function(){  
			PopupMessage('错误来自/PHP/showTemST.php','TemSTEdit_Message');
        },  
		success: function(respsone){ 
		 if(respsone!=null){
			 if(respsone.ArrayTp!=null)
				 {				 	
				 	$("#field-TemList").empty();
					 for(var i=0;i<respsone.ArrayTp.length;i++)
				 		{
						 var val="";
						 var TemplateID = respsone.ArrayTp[i].TemplateID;
						 var TemplateName = respsone.ArrayTp[i].TemplateName;
						 val+="模板名称:"+TemplateName+",";
						 var TemplateCycle = respsone.ArrayTp[i].TemplateCycle;
						 val+="模板周期:"+TemplateCycle+"秒,模板时段:";
						 var TemplateList = respsone.ArrayTp[i].TemplateList;
						 for(var j=0;j<TemplateList.length;j++)
					 		{
					 			var tStart=new Date(TemplateList[j].tStart);
					 			var tEnd=new Date(TemplateList[j].tEnd);
					 			var item=tStart.getHours()+":"+tStart.getMinutes()+":"+tStart.getSeconds()+"---"+tEnd.getHours()+":"+tEnd.getMinutes()+":"+tEnd.getSeconds();
					 			val+=item+"|";
					 		}
						 var lab="<label for='Tem"+TemplateID+"'>"+val+"</label>";
						 var inp="";
						 if(respsone.TemplateName==TemplateName)
							 {inp="<input type='radio' name='gender' id='Tem"+TemplateID+"' value='"+val+"' checked>";}
						 else{inp="<input type='radio' name='gender' id='Tem"+TemplateID+"' value='"+val+"'>";}
						 $("#field-TemList").append(lab+inp);
						 $("#Tem"+TemplateID).checkboxradio();
				 		}
				 }

			 if(respsone.TemplateST!=null)
			 {
				 var arrayST=respsone.TemplateST.arrayST;
				 $("#m-list-TemST").empty();
				 for(var i=0;i<arrayST.length;i++)
					 {
						 var Listitem = "<li id='TemST"+arrayST[i]+"'><a>"+arrayST[i]+"</a><a data-transition='pop' data-icon='delete'  onclick='DeleteTemST("+arrayST[i]+")'>删除排期</a></li>"
						 $("#m-list-TemST").append(Listitem);
					 }
				 $("#m-list-TemST").listview("refresh");   				 
			 }
		 }
		showItem('#Div-TemST');
		}
	});
}
//删除排期
function DeleteTemST(TemSTValue) {
	var url = "../PHP/DeleteTemSTbyValue.php";
	data={
			TemSTValue:TemSTValue
	};
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data:data,
		error: function(){  
			PopupMessage('错误来自/PHP/DeleteTemSTbyValue.php','TemSTEdit_Message');
        },  
		success: function(respsone){ 
		 if(respsone!=null){
			 if(respsone.msg=="OK")
				 {$("#TemST"+TemSTValue).remove();}
			 else {
				 PopupMessage('删除排期失败','TemSTEdit_Message');
			}
		 }
		}
	});
}

function TemSTBack() {
	var ID=$('input:radio[name="gender"]:checked').attr('id');
	var val=$('input:radio[name="gender"]:checked').val();
	var TemplateID=ID.substring(3,ID.length);
	var ArrayTemplate = val.split(",")
	var TemName="",TemCycle="",TemList="";
	if(ArrayTemplate.length>=3)
		{
		TemName = ArrayTemplate[0].substring(ArrayTemplate[0].indexOf(":") + 1,ArrayTemplate[0].length); 	
		TemCycle = ArrayTemplate[1].substring(ArrayTemplate[1].indexOf(":") + 1,ArrayTemplate[1].length); 	
		TemList = ArrayTemplate[2].substring(ArrayTemplate[2].indexOf(":") + 1,ArrayTemplate[2].length); 	
		}

	var url = "../PHP/TemSTBack.php";
	data={
			TemID:TemplateID,
			TemName:TemName,
			TemCycle:TemCycle,
			TemList:TemList
	};
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data:data,
		error: function(){  
			PopupMessage('错误来自/PHP/TemSTBack.php','TemSTEdit_Message');
        },  
		success: function(respsone){ 
		 if(respsone!=null){
			 if(respsone.msg=="OK")
				 {
				 	var myDate = new Date();				 	
			 		var imgSTSrc = "../PHP/DrawTemST.php?srceenW="+parseInt($(document.body).width())+"&ADID="+selectADID+"&time="+myDate;				 		
			 		$("#ImgTemST").attr("src",imgSTSrc);
				 }			
		 }
		}
	});

	showItem('#m-ST');
}

function OpenHtml(htmlPath) {
	window.location=htmlPath;
}
