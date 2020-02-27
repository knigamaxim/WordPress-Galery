$(function(){
	function readURL(input) {
		if (input.files) {
			for (var i = 0; i < input.files.length; i++) {
				var reader = new FileReader();
				reader.readAsDataURL(input.files[i]);
				reader.onload = (e) => {
					console.log(e.target);
					$('.images-wrap').append('<img src="' + e.target.result + '">');
				};
			}

		}
	}
	$("#imgInput").change(function(){
		readURL(this);
	});
});