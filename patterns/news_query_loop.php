<?php
/**
 * Title: List of news posts, 3 columns
 * Slug: ihbi/news-query-loop
 * Categories: query
 * Block Types: core/query
 * Description: A 3-column grid of news posts with featured image, excerpt and categories.
 *
 * @package IHBI_Lab_Theme
 */
?>
<!-- wp:query {"query":{"perPage":9,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[]},"align":"full","layout":{"type":"default"}} -->
<div class="wp-block-query alignwide">
    <!-- wp:post-template {"align":"full","layout":{"type":"grid","columnCount":3}} -->
        <!-- wp:group {"className":"is-style-card","layout":{"type":"constrained"}} -->
        <div class="wp-block-group is-style-card">
            <!-- wp:post-featured-image {"isLink":true,"aspectRatio":"3/2","style":{"border":{"radius":"8px"}}} /-->
            <!-- wp:post-title {"isLink":true,"fontSize":"large","style":{"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|20"}}}} /-->
            <!-- wp:post-excerpt {"moreText":"Read more","excerptLength":10,"fontSize":"medium"} /-->
            <!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}}} -->
            <div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--30)">
                <!-- wp:post-terms {"term":"category","separator":" ","className":"is-style-post-terms-1"} /-->
                <!-- wp:post-date {"isLink":false,"fontSize":"small"} /-->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:group -->
    <!-- /wp:post-template -->

	<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
		<!-- wp:query-no-results -->
		<!-- wp:paragraph -->
		<p><?php esc_html_e( 'No news posts found.', 'ihbi-wp-theme' ); ?></p>
		<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:query-pagination {"paginationArrow":"arrow","align":"wide","layout":{"type":"flex","justifyContent":"space-between"}} -->
			<!-- wp:query-pagination-previous /-->
			<!-- wp:query-pagination-numbers /-->
			<!-- wp:query-pagination-next /-->
		<!-- /wp:query-pagination -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:query -->
