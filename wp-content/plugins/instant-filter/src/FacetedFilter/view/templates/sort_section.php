<?php
/**
 * instant-filter
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */
/** @var array $current_sort_options */
?>
<!--Sort Section-->
<li class="filter-sort-wrapper accordion-item" data-accordion-item id="sort-results-<?=$shortcode_id?>">
    <a class="accordion-title">Sort By</a>
    <ul class="menu vertical nested filter--menu-wrap-menu accordion-content"
        data-tab-content
    >
        <div class="form-container">
            <form action="">
                <div class="radio-container">
                    <p><span>Price:</span></p>
                    <div class="button-box">
                        <input type="radio" name="radio" value="highToLow" class="price-radio" id="SortPriceHighToLow"
                               data-sort-type="price" data-sort-direction="desc"
                            <?php if('price'===$current_sort_options['type'] && 'desc'===$current_sort_options['direction']):?>
                                checked="checked"
                            <?php endif;?>
                        >
                        <label for="SortPriceHighToLow" class="label-price-radio">High to Low</label>
                    </div>

                    <div class="button-box">
                        <input type="radio" name="radio" value="lowToHigh" class="price-radio" id="SortPriceLowToHigh"
                               data-sort-type="price" data-sort-direction="asc"
                            <?php if('price'===$current_sort_options['type'] && 'asc'===$current_sort_options['direction']):?>
                                checked="checked"
                            <?php endif;?>
                        >
                        <label for="SortPriceLowToHigh" class="label-price-radio">Low to High</label>
                    </div>
                </div> <!-- end check-box-container -->

                <div class="radio-container">
                    <p><span>Arrival Date:</span></p>
                    <div class="button-box">
                        <input type="radio" name="radio" value="arrivalDate" class="arrival-radio" id="SortArrivalDate"
                               data-sort-type="date_arrival" data-sort-direction="desc"
                            <?php if('date_arrival'===$current_sort_options['type'] && 'desc'===$current_sort_options['direction']):?>
                                checked="checked"
                            <?php endif;?>
                        >
                        <label for="SortArrivalDate" class="label-arrival-checkbox">Latest Arrivals</label>
                    </div>
                </div> <!-- end check-box-container -->
            </form> <!-- end form -->
        </div>
    </ul>
</li>

