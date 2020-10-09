/*!
 *
 * Date: Jun 26 2013 
 */

$().ready(function() {
  $("#form1").validate({
	rules: {
		name1: "required",
		name2: "required",
		add: "required",
		comment: "required",
		email: {
			required: true,
			email: true
		},
		email2: {
			required: true,
			email: true,
			equalTo: "#email"
		}
	},
	messages: {
		name1: "未入力",
		name2: "未入力",
		add: "未入力",
		comment: "未入力",
		email: {
			required: "未入力",
			email: "メールアドレスの書式ではありません"
		},
		email2: {
			required: "未入力",
			email: "メールアドレスの書式ではありません",
			equalTo: "確認用メールアドレスが一致しません"
		}
	}
  });

$("#form1 input").keypress(e_disable);

});

function e_disable(e) {
	if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
	   return false;
	}
	return true;
}