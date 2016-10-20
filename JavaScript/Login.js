/**
 * 
 */
function login()
{
	var userName=$("#UserName").val();
	var pwd=$("#pwd").val();
	if(userName==""){PopupMessage("用户名不能为空!");return;}
	var url = "../PHP/Login.php";
	var data={
			adminName:userName,
			pwd:pwd
	}
	$.ajax({  
		type: "post",  
		url: url,  
		dataType: "json",  
		data: data,  
		error: function(){  
			PopupMessage('错误来自/PHP/Login.php','popupMessage');
        },  
		success: function(respsone){ 
			if(respsone!=null)
				{
				if(respsone.msg=="OK")
					{
					setCookie("adminName",userName,1);
					window.location="../HTML/Main.html";						
					}
				else{PopupMessage(respsone.msg,'popupMessage');}				
				}
			else{PopupMessage('错误来自/PHP/Login.php','popupMessage');}
		}
	});
}

function PopupMessage(strMessage) {
	$("#popupMessage").empty();
	$("#popupMessage").append('<p>'+strMessage+'</p>')
	$("#popupMessage").popup('open');
}