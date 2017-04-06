<?php
use yii\helpers\Url;
?>
window.testSelAll = $('.testSelAll').SumoSelect({okCancelInMulti: false,selectAll: true});
$('#send-preview-mail').click(function(){
    var Content = $('#campaign-content').val();
    var Subject = $('#campaign-subject').val();
    alert(Content);
    $.ajax({
        url: '<?= URL::to(['campaign/previewmail']);?>',
        method: 'POST',
        data: {Content: Content,Subject: Subject},
        success: function (data, textStatus, jqXHR) {
            alert(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('An error occured!');
            alert('Error in ajax request');
        }
    });
});