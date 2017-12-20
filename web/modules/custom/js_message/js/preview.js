/**
 * Created by alexandr on 08.12.17.
 */

(function ($) {
    Drupal.behaviors.previewBehavior = {
        attach: function (context, settings) {
            $(function () {
                $("input[name='background']").on('input', function () {
                    $(".modal").css("background", $("input[name='background']").val());
                });
                $("input[name='background_head']").on('input', function () {
                    $(".modal-head").css("background", $("input[name='background_head']").val());
                });
                $("input[name='width']").on('keydown', function (e) {
                    switch(e.keyCode){
                        case 38:   // if push key up
                            $(this).val(parseInt($(this).val())  + 1);
                            $(".modal").css("width", $(this).val() + 'px');
                            break;
                        case 40:    // if push key down
                            $(this).val(parseInt($(this).val())  - 1);
                            $(".modal").css("width", $(this).val() + 'px');
                            break;
                    }
                });
                $("input[name='width']").on('input', function () {
                    $(".modal").css("width", $(this).val() + 'px');
                });
                $("input[name='title']").on('input', function () {
                    $(".modal-head span").text($("input[name='title']").val());
                });
                function createmodal(){
                    $(".setting-modal-form", context).append($('<div></div>').addClass('modal').css("position", "relative"));
                    $(".modal", context).append($('<div></div>').addClass('modal-body'));
                    $(".modal", context).append($('<div><span>Информация</span></div>').addClass('modal-head'));
                    $(".modal-head", context).append($('<input value="&times;">').addClass('modal-close'));
                    $(".modal").css("background", $("input[name='background']").val());
                    $(".modal-head").css("background", $("input[name='background_head']").val());
                    $(".modal").css("width", $("input[name='width']").val() + 'px');
                    $(".modal-head span").text($("input[name='title']").val());
                }
                createmodal();
            });
        }
    };

})(jQuery, Drupal);

