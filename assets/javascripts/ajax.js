//ajaxfunction to show todo items
function ajaxGetFunction(){
    $.ajax({
        type: "GET",
        url: "/ajaxcontrol/ajax"+"?getitems=1",
        success: function (response) {
            //console.log(response);
            $("#showitems").html(response.html);
            $("#addTodo")[0].reset();
            $(".mark").click(function() {
              //  alert(this.value); // or alert($(this).attr('id'));
              ajaxMarkfunction(this.value);
            });
            $(".delete").click(function() {
              //  alert(this.value); // or alert($(this).attr('id'));
              ajaxDelfunction(this.value);
            });

        }
    });
}
//ajaxfunction to delete an item
function ajaxDelfunction(x)
{
    let id =x;
  
        $.ajax({
            type: "GET",
            url: "/ajaxcontrol/ajax"+"?del="+id,
            success: function (response) {
                //console.log(response);
                ajaxGetFunction();
    
            }
        });
}
//ajaxfunction to mark an item complete
function ajaxMarkfunction(x)
{
    let id =x;
  
        $.ajax({
            type: "GET",
            url: "/ajaxcontrol/ajax"+"?mark="+id,
            success: function (response) {
                //console.log(response);
                ajaxGetFunction();

    
            }
        });
}

$(document).ready(function(){
    $("#addTodo")[0].reset();
    ajaxGetFunction();
});