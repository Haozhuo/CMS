tinymce.init({ selector:'textarea' });


$(document).ready(function(){
	$('#selectAllBoxes').on('click',function(){
		if(this.checked){
			$(".checkBoxes").each(function(){
				this.checked=true;
			});
		}else{
			$(".checkBoxes").each(function(){
				this.checked=false;
			});
		}
	});

	$('#con').on('click',function(event){
		//prevent the link
		event.preventDefault();
		var answer = confirm("Are you sure you want to delete?");

		var href = $(this).attr('href');
		if(answer){
			//redirect to the link
			window.location=href;
		}

	});
});
