<script src="{{asset('/vendor/notifyjs/notify.min.js')}}"></script>
<script>
    $(document).ajaxComplete(function(event, xhr, settings){
        $( ".log" ).text( "Triggered ajaxComplete handler." );
        console.log(xhr.responseText);
        response = $.parseJSON(xhr.responseText);

        // Check for Notifications and Display Them
        if(response.hasOwnProperty('notifications')){
            for(var i=0; i< response.notifications.length; i++){
                notification = response.notifications[i];
                $.notify(notification.message,notification.type);
            }
        }
    });

    $(document).ready(function(){
        @if(Session::has('notifications'))
            @foreach(Session::pull('notifications') as $notification)
                $.notify('{{$notification['message']}}','{{$notification['type']}}');
        @endforeach
    @endif
});

</script>