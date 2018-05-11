<?php
if($page_type === "dashboard") {
?>
    <footer class="footer navbar-fixed-bottom">
        <div class="container-fluid">
            <p class="text-muted margin_zero">77beats.com &copy; 2017</p>
        </div>
    </footer>
    <script>
        var event_location = "";
    </script>
<?php
    } else {
?>
        <footer class="footer<?php if($page_type === "login" || $page_type === "signup") echo " navbar-fixed-bottom"; ?>">
            <p class="text-center">&copy; 2017 <a href="#">77beats.com</a></p>
        </footer>
<?php } ?>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="<?php echo $obj->base_url; ?>js/jquery-3.2.1.min.js"></script>
        <script src="<?php echo $obj->base_url; ?>js/moment.min.js"></script>
        <script src="<?php echo $obj->base_url; ?>js/daterangepicker.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="<?php echo $obj->base_url; ?>js/bootstrap.min.js"></script>
        <script src="<?php echo $obj->base_url; ?>js/script.js"></script>
<?php
if($page_type === "dashboard") {
?>
        <script type="text/javascript">
            $(function() {
                $('input[name="start_at"], input[name="end_at"]').daterangepicker({
                    timePicker: true,
                    timePickerIncrement: 30,
                    singleDatePicker: true,
                    timePickerIncrement: 1,
                    timePicker24Hour: true,
                    minDate: moment(),
                    locale: {
                        format: 'YYYY-MM-DD HH:mm:ss'
                    }
                });
            });
            var autocomplete;
            function loadAutocompleteAPI() {
                autocomplete = new google.maps.places.Autocomplete(document.getElementById("event_location"));
                autocomplete.addListener("place_changed", function() {
                    var place = autocomplete.getPlace();
                    if (place.geometry) {
                        event_location = place.formatted_address;
                    }
                });
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDx8mqpnL6NmCGjKikf9XRoO7Qx79J6uuk&libraries=places&callback=loadAutocompleteAPI" async defer></script>
<?php
}
?>
    </body>
</html>