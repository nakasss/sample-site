/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/*
 * Toggle tutorial content
 */
$(function(){
    $("#tutorial-open-wrapper .tutorial-link, #tutorial-open-wrapper button, .tutorial-close-btn-wrapper .tutorial-link, .tutorial-close-btn-wrapper button").on("click", function() {
        flag = $("#tutorial-open-wrapper .handlediv .toggle-indicator").attr("aria-hidden") + '';
        if (flag === 'true') {
            flag = 'false';
        } else {
            flag = 'true';
        }
        $("#tutorial-open-wrapper .handlediv .toggle-indicator").attr("aria-hidden", flag);
        $("#tutorial-post-wrapper").slideToggle();
    });
});