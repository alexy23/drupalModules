/**
 * Created by alexandr on 07.12.17.
 */
(function ($) {
    Drupal.behaviors.getClientMessageBehavior = {
        attach: function (context, settings) {
            $(function () {
                var message = drupalSettings.js_message.popup_get_message.get_message;
                var config = drupalSettings.js_message.popup_get_message.config;
                if(message.length != 0) {
                    createmodal();
                    $("#overlay").show();
                    dragElement($(".modal")[0]);
                    for(var i = 0; i < message.status.length; i++){
                        $(".modal").find(
                            $(".modal-body")
                        ).append(
                                $('<div class="modal-message modal-message-status">'
                                    + message.status[i] + '</div>').attr(
                                    'id', 'modal-status-' + i
                                )
                            );
                        $("#modal-status-" + i).click(function(){
                            $(this).hide('slow');
                        })
                    }
                    for(var i = 0; i < message.error.length; i++){
                        $(".modal").find(
                            $(".modal-body")
                        ).append(
                                $('<div class="modal-message modal-message-error">'
                                    + message.error[i] + '</div>').attr(
                                    'id', 'modal-error-' + i
                                )
                        );
                        $("#modal-error-" + i).click(function(){
                            $(this).hide('slow');
                        });
                    }
                    for(var i = 0; i < message.warning.length; i++){
                        $(".modal").find(
                            $(".modal-body")
                        ).append(
                                $('<div class="modal-message modal-message-warning">'
                                    + message.warning[i] + '</div>').attr(
                                    'id', 'modal-warning-' + i
                                )
                        );
                        $("#modal-warning-" + i).click(function(){
                            $(this).hide('slow');
                        });
                    }
                }
                function createmodal(){
                    $("body", context)
                        .append($('<div></div>')
                            .attr('id','overlay'));
                    $("body", context)
                        .append($('<div></div>')
                            .addClass('modal')
                                .css("position","absolute")
                                    .css("background", config.background_color)
                                        .css("width", config.width));
                    $(".modal", context)
                        .append($('<div></div>')
                            .addClass('modal-body'));
                    $(".modal", context)
                        .append($('<div><span>' + config.title_form + '</span></div>')
                            .addClass('modal-head')
                                .css("background", config.background_color_head));
                    $(".modal-head", context)
                        .append($('<input value="&times;">')
                            .addClass('modal-close'));

                }
                function dragElement(elmnt) {
                    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
                    if (document.getElementsByClassName("modal-head")[0]) {
                        document.getElementsByClassName("modal-head")[0].onmousedown = dragMouseDown;
                    } else {
                        elmnt.onmousedown = dragMouseDown;
                    }

                    function dragMouseDown(e) {
                        e = e || window.event;
                        pos3 = e.clientX;
                        pos4 = e.clientY;
                        document.onmouseup = closeDragElement;
                        document.onmousemove = elementDrag;
                    }

                    function elementDrag(e) {
                        e = e || window.event;
                        pos1 = pos3 - e.clientX;
                        pos2 = pos4 - e.clientY;
                        pos3 = e.clientX;
                        pos4 = e.clientY;
                        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
                        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
                    }

                    function closeDragElement() {
                        document.onmouseup = null;
                        document.onmousemove = null;
                    }
                }
                $(".modal-close").click(function(){
                        $("#overlay").hide();
                        $(".modal").hide();
                }

                );
                $("#overlay").click(function () {
                        $("#overlay").hide();
                        $(".modal").hide();
                    }
                );
            });
        }
    };

})(jQuery, Drupal);
