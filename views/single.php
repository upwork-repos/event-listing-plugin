<?php
/**
 * The template for displaying single posts and pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

get_header();
?>


<main style="align-content: center; text-align: center;">
    <?php

    if ( have_posts() ) {

        while ( have_posts() ) {
            the_post();
    ?>
        <h1 style="padding-bottom: 20px"><?php the_title();  ?></h1>
        <?php
        global $post;
        $excerpt = get_the_excerpt($post->ID);
        $date_value = get_post_meta( $post->ID, 'date', true );
        $date = $date_value;
        $url = get_post_meta( $post->ID, 'url', true );
        $location_value = get_post_meta( $post->ID, 'location', true );
        $location = get_post_meta( $post->ID, 'location_name', true );
        $timestamp = strtotime($date_value);
        $lat = 46.15242437752303;
        $lng = 2.7470703125;
        $location_value = explode('|', $location_value);
        if( sizeof($location_value) == 2 ){
            $lat = $location_value[0];
            $lng = $location_value[1];
        }
        echo "<a class='button' href='https://www.google.com/calendar/render?action=TEMPLATE&text=$title&details=$excerpt&location=$location&dates=$timestamp%2F$date'  target='_blank' >Add to Google Calendar</a> &nbsp; <a class='button' href='$url' title='Go to the external site' target='_blank'>Go to the event site</a>";

        ?>

        <div style="margin-top: 20px"><?php the_content();  ?></div>

        <script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBkmFAzmTPiOnVTRmfkaQACABtYcFCcjrI"></script>
<script type="text/javascript" src="https://rawgit.com/Logicify/jquery-locationpicker-plugin/master/dist/locationpicker.jquery.js"></script>



<script type="text/javascript">
    $(function() {
 
$('#us2').locationpicker({
   location: {latitude:  <?php echo $lat?>, longitude: <?php echo $lng?>},   
   radius: 0,
   inputBinding: {
      latitudeInput: $('#lat'),
      longitudeInput: $('#lng'),
      locationNameInput: $('#location')
   },
   enableAutocomplete: true,
   onchanged: function(currentLocation, radius, isMarkerDropped) {
        document.getElementById('location').value = currentLocation.latitude+'|'+currentLocation.longitude
    }
});
 
 
});

</script>

<div id="us2" style="height: 400px;"></div> 
    <?php
        }
    }

    ?>
</main>








<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer(); ?>
