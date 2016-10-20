/**
 * 
 */
function ChangePwd()
{
	if($("#oldPwd").val()=="")
		{PopupMessage("旧密码不能为空！",'adminDiv_Message');return;}
	if($("#newPwd1").val()=="")
	{PopupMessage("新密码不能为空！",'adminDiv_Message');return;}
	if($("#newPwd1").val()!=$("#newPwd2").val())
	{PopupMessage("两次新密码不一致！",'adminDiv_Message');return;}
	
	var adminName = getCookie("adminName");
	var url = "../PHP/ChangePwd.php";
	var data={
			adminName:adminName,
			oldPwd:$("#oldPwd").val(),
			newPwd1:$("#newPwd1").val()			
	}
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data: data,  
		error: function(){  
			PopupMessage('错误来自/PHP/ChangePwd.php','adminDiv_Message');
        },  
		success: function(respsone){ 
			if(respsone!=null)
				{
				if(respsone.msg=="OK")
					{					
						PopupMessage('修改密码成功！','adminDiv_Message');
					}
				else{PopupMessage(respsone.msg,'adminDiv_Message');}				
				}
			else{PopupMessage('错误来自/PHP/ChangePwd.php','adminDiv_Message');}
		}
	});
}
