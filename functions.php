<?php
function ihbi_theme_styles() {
    wp_enqueue_style(
        'ihbi-theme-style',
        get_stylesheet_uri(),
        [],
        wp_get_theme()->get( 'Version' )
    );
}
add_action( 'wp_enqueue_scripts', 'ihbi_theme_styles' );
// Create a shortcode to output Project Meta Data
add_shortcode('project_details_bar', 'render_project_details_shortcode');

function render_project_details_shortcode() {
    // Only run this on single project posts
    if ( get_post_type() !== 'project' || !function_exists('get_field') ) {
        return '';
    }

    // Fetch the ACF fields
    $year = get_field('project_year');
    $owner = get_field('project_owner');
    $sponsor = get_field('project_sponsor');
    $link = get_field('publication_link');
    
    // Fetch the Direction tags
    $directions = get_the_terms( get_the_ID(), 'direction' );

    // Start building the HTML output
    ob_start();
    ?>
    <div class="project-metadata-bar">
        <div class="meta-item">
            <div class="meta-label">Year</div>
            <div class="meta-value"><?php echo esc_html($year); ?></div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Owner</div>
            <div class="meta-value"><?php echo esc_html($owner); ?></div>
        </div>
        <?php if ( $sponsor ) : ?>
            <div class="meta-item">
                <div class="meta-label">Sponsor</div>
                <div class="meta-value"><?php echo esc_html($sponsor); ?></div>
            </div>
        <?php endif; ?>
        <?php if ( $link ) : ?>
            <div class="meta-item">
                <div class="meta-label">Publication</div>
                <div class="meta-value">
                    <a href="<?php echo esc_url($link); ?>" target="_blank">View Link &rarr;</a>
                </div>
            </div>
        <?php endif; ?>
        <?php if ( !empty($directions) && !is_wp_error($directions) ) : ?>
			<div class="meta-item">
				<div class="meta-label">Direction</div>
                <div class="meta-directions">
                    <?php foreach ( $directions as $direction ) : ?><a class="meta-tag" href="<?php echo esc_url( get_term_link( $direction ) ); ?>"><?php echo esc_html( $direction->name ); ?></a><?php endforeach; ?>
                </div>
			</div>
		<?php endif; ?>
    </div>
    <?php
    // Return the HTML buffer
    return ob_get_clean();
}