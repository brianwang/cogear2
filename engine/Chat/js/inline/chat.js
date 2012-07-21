var Chat = function($el){
    this.init($el);
}
Chat.prototype = {
    id: 0,
    el: {},
    init: function($el){
        this.el = $el;
        this.id = $el.attr('data-id');
        this.scroll();
        this.bind();
        this.inviter();
        $chat = this;
        setInterval(function(){
            $chat.refresh();
        },5000);
    },
    refresh: function(){
        $this = this;
        $(this.el).load(l('/chat/view/'+this.id+' #chat-window-'+this.id+''),{},function(){
            $this.scroll();
        });

    },
    scroll: function(){
        this.window = this.el.find('.chat-window')[0];
        $(this.window).scrollTop($(this.window).height());
    },
    inviter: function(){
        $('#form-chat-invite').ajaxForm({
            dataType: 'json',
            success: function(data){
                if(data.code){
                    $('#chat-users-container').append($(data.code));
                }
                $('#form-chat-invite input[name=users]').val('');
            }
        })
    },
    bind: function(){

    }

};

$(document).ready(function(){
    $('.chat').each(function(){
        $chat = new Chat($(this));
        $form = $('#form-chat-msg');
        $form.ajaxForm({
            beforeSubmit: function(){
                $form.find('[type=submit]').after(cogear.ajax.inline.show());
            },
            success: function(){
                $chat.refresh()
                $form.find('[name=body]').val('');
            },
            complete: function(){
                cogear.ajax.inline.hide();
            }
        })
    })
    $(document).on('click','.chat-action',function(event){
        event.preventDefault();
        $link = $(this);
        bootbox.confirm(t('Are you sure?'), function(confirmed) {
            if(confirmed){
                $.getJSON($link.attr('href'),function(data){
                    if(data.success){
                        if(data.code){
                            $link.parent().parent().replaceWith($(code));
                        }
                        else {
                            $link.parent().parent().slideUp();
                        }
                    }
                });
            }
        });
    })
})