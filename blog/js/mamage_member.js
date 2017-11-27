// window.onload = function() {
// 	function getTableContent(node) {  
// // 按钮的父节点的父节点是tr。  
//     var tr1 = node.parentNode.parentNode;  
//     alert(tr1.rowIndex);  
//     alert(tr1.cells[0].childNodes[0].value); //获取的方法一  
// 	alert(tr1.cells[0].innerText);  
 
// // 通过以下方式找到table对象，在获取tr，td。然后获取td的html内容  
// 	    var table = document.getElementById("tb1");//获取第一个表格  

// 	    var child = table.getElementsByTagName("tr")[rowIndex - 1];//获取行的第一个单元格  
	      
// 	    var text = child.firstChild.innerHTML;  
// 	    window.alert("表格第" + rowIndex + "的内容为: " + text);  
// 	}  
// }