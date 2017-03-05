jQuery(document).ready(function($){

    /*********************************
     * testimonial add/remove/move
     *********************************/
     //* Set testimonialnumber to pass on to inserted testimonial 
    // var new_testimonial_number = $( ".testimonials-table tbody tr" ).length;
    var new_testimonial_number;
    var testimonial_count;

    //* Add testimonial
    $( ".add_testimonial" ).on('click', function(e) {

        e.preventDefault();

        //* Calculate new testimonial number
        new_testimonial_number = get_new_testimonial_number();

        testimonial_count = get_testimonial_count();

        //* Prepare the clone
        process_clone_number();

        if (testimonial_count >= 10) {
            alert("No more than 10 testimonials are allowed. After all, it's supposed to be a *simple* plugin ;)");
        }
        else {
            //* Cloning action!
            clone_the_testimonial_clone();
        }
    });

    //* Calculate new clone number
    function get_new_testimonial_number()
    {
        // var last_testimonial_id = parseInt( $( ".testimonials-table tbody tr:last-child .id" ).text() );
        var highest_id = 0;
        var num = 0;
        $( ".testimonials-table tr .id" ).each( function(){
            num = parseInt( $(this).text(), 10 );
            if ( num >= highest_id) 
                highest_id = num + 1;
        });

        return highest_id;
    }

    //* Calculate amount of testimonials
    function get_testimonial_count()
    {
        return $( ".testimonials-table tr.testimonial" ).length;
    }


    function process_clone_number() {
        $(".clone .id").text(new_testimonial_number);
        $(".clone .testimonial-title").attr("name", "testimonials[" + new_testimonial_number + "][title]");
        $(".clone .testimonial-content").attr("name", "testimonials[" + new_testimonial_number + "][content]");
        $(".clone .testimonial-caption").attr("name", "testimonials[" + new_testimonial_number + "][caption]");
    }

    function clone_the_testimonial_clone() {
        $(".clone").clone().attr('class', 'testimonial').appendTo( $( ".testimonials-table tbody" ) );
    }

    //* Remove testimonial
    //* Call from parent to enable removal of dynamicly added testimonials
    $( ".testimonials-table" ).on('click', '.remove-testimonial', function(e) {

        e.preventDefault();

        $(this).closest("tr").remove();
    });

    //* Move testimonial
    $( ".testimonials-table tbody" ).sortable({
        revert: true,
        handle: ".move-testimonial",
    });
 
});