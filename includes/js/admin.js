jQuery(document).ready(function($){

    /*********************************
     * Referentie add/remove/move
     *********************************/
    //* Set referentienumber to pass on to inserted referentie 
    var new_referentie_number = $( ".referenties-table tbody tr" ).length;

    //* Add referentie
    $( "#add_referentie" ).on('click', function(e) {

        e.preventDefault();

        process_clone_number();
        clone_the_referentie_clone();
    });

    function process_clone_number() {
        $(".referentie-clone .referentie-title").attr("name", "referenties[" + new_referentie_number + "][title]");
        $(".referentie-clone .referentie-content").attr("name", "referenties[" + new_referentie_number + "][content]");
        $(".referentie-clone .referentie-caption").attr("name", "referenties[" + new_referentie_number + "][caption]");
        new_referentie_number++;
    }

    function clone_the_referentie_clone() {
        $(".referentie-clone").clone().attr('class', 'referentie').appendTo( $( ".referenties-table tbody" ) );
    }

    //* Remove referentie
    //* Call from parent to enable removal of dynamicly added referenties
    $( ".referenties-table" ).on('click', '.remove-referentie', function(e) {

        e.preventDefault();

        $(this).closest("tr").remove();
    });

    //* Move referentie
    $( ".referenties-table tbody" ).sortable({
        revert: true,
        handle: ".move-referentie",
    });
 
});