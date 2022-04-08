<table class="form-table">
<?php 
// 
$date_value = get_post_meta( $post->ID, 'date', true );
$url_value = get_post_meta( $post->ID, 'url', true );
$location_value = get_post_meta( $post->ID, 'location', true );
$lat = 46.15242437752303;
$lng = 2.7470703125;
if($location_value){
	$location_value = explode('|', $location_value);
	$lat = $location_value[0];
	$lng = $location_value[1];
}


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
    	<label for="location" class="">Location  </label>
    </th>
    <td>
    	<input type="hidden" id="location" name="location" >
    	<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBkmFAzmTPiOnVTRmfkaQACABtYcFCcjrI"></script>
<script type="text/javascript" src="https://rawgit.com/Logicify/jquery-locationpicker-plugin/master/dist/locationpicker.jquery.js"></script>



<script type="text/javascript">
	$(function() {
 
$('#us2').locationpicker({
   location: {latitude:  <?=$lat?>, longitude: <?=$lat?>},   
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


 Location: <input type="text" id="location" style="width: 200px"/>
Lat <input type="text" id="lat" style="width: 200px"/>
Long: <input type="text" id="lng" style="width: 200px"/>
<div id="us2" style="height: 400px;"></div> 





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

