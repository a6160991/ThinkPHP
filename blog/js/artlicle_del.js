window.onload = function () {
	code();
	var fm = document.getElementsByTagName('form')[0];
	if (fm == undefined) return;
	fm.onsubmit = function() {
		//验证码验证
		if (fm.code.value.length != 4) {
			alert('验证码必须是4位');
			fm.code.value = ''; //清空
			fm.code.focus(); //将焦点以至表单字段
			return false;
		}
	//删除提示
	var del = document.getElementById('del');
	if (confirm("请确定是否要删除") == true) {
		return true;
	} else {
		return false;
	}
		return true;
	}
}