<?php
get_header();
?>
<div class="destinations-wrapper">
    <form action="">
        <div class="destination-form-inner">
            <div class="destination-locations">
                <select name="destination-locations" id="destination-locations" class="destination-locations">
                    <option>Destinations</option>
                    <option value="paris">Paris</option>
                    <option value="new-york">New York</option>
                    <option value="tokyo">Tokyo</option>
                    <option value="sydney">Sydney</option>
                    <option value="rome">Rome</option>
                    <option value="london">London</option>
                    <option value="barcelona">Barcelona</option>
                </select>
            </div>
            <div class="destination-available-btn">
                <button type="submit">View available trips</button>
            </div>
        </div>
    </form>

<?php
get_footer();
