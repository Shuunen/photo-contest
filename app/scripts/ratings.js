$(document).ready(function () {

    $('input.rating').rating()
        .on('change', function (event) {
            // console.info('Rating: ' + $(this).val());
            // console.log(event);
            // console.log($(event.currentTarget).parents(".rating-category").attr("data-category-id"));
            var category = $(event.currentTarget).parents(".rating-category");
            $.ajax({
                type: 'get',
                data: 'type=rate&photoId=' + category.attr("data-photo-id") + '&categoryId=' + category.attr("data-catgerory-id") + '&rate=' + $(this).val() + '&ajax=true',
                success: function (json) {
                    //console.log(json);
                }
            });
        });
});
