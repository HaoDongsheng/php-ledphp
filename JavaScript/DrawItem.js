/**
 * 
 */
function GetArrayImgs(arrayItem) {
	 var arrImg = new Array();
		for(var i=0;i<arrayItem.length;i++)
			{
			var arrImgLine = new Array();
			for(var j=0;j<arrayItem[i].length;j++)
				{
					var itemType = arrayItem[i][j].itemType;
					var fontName = arrayItem[i][j].fontName;
					var fontSize = arrayItem[i][j].fontSize;
					var value = arrayItem[i][j].value;			 			
		 			switch(itemType)
					{
						case 0/*文字*/:{							
							canvasBuffer = document.createElement('canvas');							
							canvasBufferContext = canvasBuffer.getContext('2d');		
							//"12px sans-serif"
							canvasBufferContext.font=fontSize+"px "+fontName;
							
							var itemW = canvasBufferContext.measureText(value).width
							
							canvasBuffer.width = itemW;
							canvasBuffer.height = fontSize;
														
							canvasBufferContext.fillRect(0,0,itemW,fontSize);
							canvasBufferContext.fillStyle="#ff0000";
							canvasBufferContext.font=fontSize+"px "+fontName;
							canvasBufferContext.fillText(value,0,fontSize - 2);
							
							var img = new Image();  
							img.src = canvasBuffer.toDataURL("image/png");  
						    arrImgLine.push(img);
						};break;
						case 1/*图片*/:{							
							var img=new Image();  
						    img.src=value;
						    
						    arrImgLine.push(img);
						};break;
					}
				}
			arrImg.push(arrImgLine);
			}
		return arrImg;
}

function PrefixInteger(num, n) {
    return (Array(n).join(0) + num).slice(-n);
}

function DrawSrcImg(arrImgs,width,heigth) {
	try {
		canvasBuffer = document.createElement('canvas');					
		canvasBufferContext = canvasBuffer.getContext('2d');	
		canvasBuffer.width = width;
		canvasBuffer.height = heigth;
		canvasBufferContext.fillRect(0,0,width,heigth);
		if(arrImgs.length>0){
			var x=0,y=0,c=0;
			for(var i=0;i<arrImgs.length;i++)
				{
					itemH=0,x=0;
					for(var j=0;j<arrImgs[i].length;j++)
					{			
						var img=arrImgs[i][j];
						
						canvasBufferContext.drawImage(img,x,y,img.width,img.height);
						x+=img.width;
						if(img.height>itemH){itemH=img.height;}
					}
					y+=itemH;
				}
		}
		return canvasBufferContext;
	} catch (e) {
		return null;
	}	
}

function DrawBigImg(canvasSrcCtx,width,heigth,scale) {
	try {
		canvasScale = document.createElement('canvas');					
		canvasScaleContext = canvasScale.getContext('2d');	
		canvasScale.width = width * scale;
		canvasScale.height = heigth * scale;
		
		for(var x=0;x<canvasBuffer.width;x++)
			{
				for(var y=0;y<canvasBuffer.height;y++)
				{
					var imgData=canvasSrcCtx.getImageData(x,y,1,1);
					
					var r = PrefixInteger(imgData.data[0].toString(16),2);
					var b = PrefixInteger(imgData.data[1].toString(16),2);
					var g = PrefixInteger(imgData.data[2].toString(16),2);
					var h = PrefixInteger(imgData.data[3].toString(16),2);
					
					var color ="#"+ r+ b+ g;
					canvasScaleContext.beginPath();
					canvasScaleContext.arc(x*scale + scale/2,y*scale + scale/2,scale/2,0,360,false);
					canvasScaleContext.fillStyle=color;//填充颜色,默认是黑色
					canvasScaleContext.fill();//画实心圆
					canvasScaleContext.closePath();
				}
			}
		return canvasScale;
	} catch (e) {
		return null;
	}	
}

function DrawItem(dataSrc) {
	try {		
		var scale=dataSrc.Scale;
		var width=dataSrc.width,heigth=dataSrc.height;
		var canvasID=dataSrc.canvasID;
		var arrImgs=dataSrc.arrImg;
		
		var canvasSrcCtx =DrawSrcImg(arrImgs,width,heigth);
		
		if(canvasSrcCtx!=null){
			var canvasBig = DrawBigImg(canvasSrcCtx,width,heigth,scale)
			if(canvasBig!=null)
				{
				var canvas=document.getElementById(canvasID);
				var context=canvas.getContext('2d');		
				context.drawImage(canvasBig, 0, 0);
				}
		}			
	} catch (e) {
		// TODO: handle exception
	}	
}