/**
 * Password Preview
 * ====================
 */
$('.pass_show').show();
$('.pass_hide').hide();
$('.pass_show').on('click', function () {
    $(this).parent().find('input').attr('type', 'text')
    $(this).prev().show();
    $(this).hide();
})
$('.pass_hide').on('click', function () {
    $(this).parent().find('input').attr('type', 'password')
    $(this).next().show();
    $(this).hide();
})
