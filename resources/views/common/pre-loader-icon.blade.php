<script>
    $( document ).ready(function() {
        // Animate loader off screen
        $(".se-pre-con").fadeOut("slow");
        $(".ajax-loader").fadeOut("slow");
    });
</script>

<script>
    $(window).bind('beforeunload', function(){
        $(".se-pre-con").fadeIn("slow");
    });
</script>