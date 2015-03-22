jQuery(document).ready(function($){

    /*********************************
     * testimonial add/remove/move
     *********************************/
     //* Set testimonialnumber to pass on to inserted testimonial 
    // var new_testimonial_number = $( ".testimonials-table tbody tr" ).length;
    var new_testimonial_number = get_new_testimonial_number();

    //* Add testimonial
    $( ".add_testimonial" ).on('click', function(e) {

        e.preventDefault();

        process_clone_number();
        clone_the_testimonial_clone();

        new_testimonial_number = get_new_testimonial_number();
    });

    function get_new_testimonial_number()
    {
        var last_testimonial_id = parseInt( $( ".testimonials-table tbody tr:last-child .id" ).text() );

        if (isNaN(last_testimonial_id)) 
            new_testimonial_number = 0;
        else
            new_testimonial_number = last_testimonial_id + 1;

        return new_testimonial_number;
    }

    function process_clone_number() {
        $(".testimonial-clone .testimonial-title").attr("name", "testimonials[" + new_testimonial_number + "][title]");
        $(".testimonial-clone .testimonial-content").attr("name", "testimonials[" + new_testimonial_number + "][content]");
        $(".testimonial-clone .testimonial-caption").attr("name", "testimonials[" + new_testimonial_number + "][caption]");
    }

    function clone_the_testimonial_clone() {
        $(".testimonial-clone").clone().attr('class', 'testimonial').appendTo( $( ".testimonials-table tbody" ) );
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