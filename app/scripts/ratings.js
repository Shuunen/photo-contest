//$(document).ready(function () {
function initRating(){
    $('input.rating').rating()
        .on('change', function (event) {
            var category = $(event.currentTarget).parents(".rating-category");
            $.ajax({
                type: 'get',
                data: 'type=rate&photoId=' + category.attr("data-photo-id") + '&categoryId=' + category.attr("data-catgerory-id") + '&rate=' + $(this).val() + '&ajax=true',
                success: function (json) {
                    //console.log(json);
                }
            });
        });
}
//});
