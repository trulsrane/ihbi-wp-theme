<?php
/**
 * Title: Header IHBI
 * Slug: ihbi/header
 * Categories: header
 * Block Types: core/template-part/header
 * Area: header
 * Description: Site header with site title and navigation.
 *
 * @package IHBI_Lab_Theme
 */
?>

<!-- wp:group {"metadata":{"patternName":"ihbi/header","name":"Header IHBI","description":"Site header with site title and navigation.","categories":["header"]},"align":"full","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull">
    <!-- wp:group {"className":"is-style-default","layout":{"type":"constrained"}} -->
    <div class="wp-block-group is-style-default">
        <!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","orientation":"horizontal"}} -->
        <div class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)">
            <!-- wp:site-title {"level":0} /-->

            <!-- wp:navigation {"ref":5,"overlayBackgroundColor":"base","overlayTextColor":"contrast","layout":{"type":"flex","justifyContent":"center","flexWrap":"wrap"}} /-->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->
