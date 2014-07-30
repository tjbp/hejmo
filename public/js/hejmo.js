jQuery.fn.swap = function(b){
    // method from: http://blog.pengoworks.com/index.cfm/2008/9/24/A-quick-and-dirty-swap-method-for-jQuery
    b = jQuery(b)[0];
    var a = this[0];
    var t = a.parentNode.insertBefore(document.createTextNode(''), a);
    b.parentNode.insertBefore(a, b);
    t.parentNode.insertBefore(b, t);
    t.parentNode.removeChild(t);
    return this;
};

$(function()
{
    $().dndPageScroll();

    $('.datepicker').pikaday({ firstDay: 1 });

    $('input[name=recurring]').on('click', function()
    {
        if ($(this).prop('checked')) {
            $(this).parent().parent().find('.recurring').show();
        } else {
            $(this).parent().parent().find('.recurring').hide();
        }
    }).triggerHandler('click');

    $('input[name=limit_season]').on('click', function()
    {
        if ($(this).prop('checked')) {
            $(this).parent().parent().find('.season').show();
        } else {
            $(this).parent().parent().find('.season').hide();
        }
    }).triggerHandler('click');

    $('.description .view').on('click', function()
    {
        $(this).hide().siblings().show();
    });

    $('.description .edit').on('focusout', function()
    {
        $(this).find('form').submit();
    });

    $('.options').on('click', function()
    {
        $(this).parent().find('.complete, .delete, .move').toggle();
    });

    $('.delete-button').on('click', function(e)
    {
        if (!confirm("Are you sure you want to delete this?")) {
            e.preventDefault();
        }
    });

    $('input[value=Import]').on('click', function(e)
    {
        if (!confirm("Are you sure you want to import? Any existing data will be overwritten.")) {
            e.preventDefault();
        }
    });

    $('.admin button').on('click', function(e)
    {
        var admin = $('.' + $(this).data('admin'));

        admin.show().siblings().hide();

        if (admin.hasClass('log')) {
            var log = admin.find('div');

            log.scrollTop(log[0].scrollHeight);
        }

        $(this).addClass('pure-button-active').siblings().removeClass('pure-button-active');
    }).first().trigger('click');

    $('.config input[name=mail_driver]').on('click', function(e)
    {
        $('.config .email').hide();

        $('.config .email.' + $(this).val()).show();
    }).filter(':checked').trigger('click');

    $('.config input[name=database_connections_hejmo_driver]').on('click', function(e)
    {
        $('.config .database').hide();

        $('.config .database.' + $(this).val()).show();
    }).filter(':checked').trigger('click');

    $('.task').draggable({
        'handle': $(this).find('.move button'),
        'cancel': $(this).find('.view, .edit, .complete, .delete'),
        'revert': true,
        'start': function()
        {
            $('.tasks').addClass('dragging');
        },
        'stop': function()
        {
            $('.tasks').removeClass('dragging');
        }
    });

    $('.emptytask').droppable({
        'hoverClass': "dragover",
        'accept': ".task",
        'tolerance': "pointer",
        'drop': function(e, ui)
        {
            var blocker_id = $(this).data('taskid');

            var task_id = ui.draggable.data('taskid');

            $(this).swap(ui.draggable);

            $.post('/tasks/blocker', {
                'task_id': task_id,
                'blocker_id': blocker_id
            }, function() {
                location.reload();
            }).fail(function() {
                alert('Error, please try again later.');

                location.reload();
            });
        }
    });
});
