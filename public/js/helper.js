$(document).ready(function(){
    $("[data-pos]").each(function(){
	var data=$(this).attr('data-pos');
	data=JSON.parse(data);
	$(this).attr('data-pos',null);
	$(this).data('data',data);
        console.log($(this));
    });
});




