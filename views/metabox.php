<table class="form-table">
<?php 
// 
$date_value = get_post_meta( $post->ID, 'date', true );
$url_value = get_post_meta( $post->ID, 'url', true );
$location_value = get_post_meta( $post->ID, 'location', true );


wp_nonce_field(EVENTLISTING.'_nonce_action', EVENTLISTING.'_nonce'); 

?>


<tr>
    <th>
    	<label for="url" class="">URL</label>
    </th>
    <td>
    	<input type="text" id="url" name="url" class="regular-text ltr" placeholder="" value="<?=esc_attr__( $url_value )?>">
    </td>
  </tr>

<tr>
    <th>
    	<label for="location" class="">Location</label>
    </th>
    <td>
    	<input type="text" id="location" name="location" class="regular-text ltr" placeholder="" value="<?=esc_attr__( $location_value )?>">
    </td>
  </tr>

<tr>
    <th>
    	<label for="date" class="">Date</label>
    </th>
    <td>
    	<input type="date" id="date" name="date" class="regular-text ltr" placeholder="" value="<?=esc_attr__( $date_value )?>">
    </td>
  </tr>

 


</table>
