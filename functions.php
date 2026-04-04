<?php
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
        <?php if ( !empty($directions) && !is_wp_error($directions) ) : ?>
			<div class="meta-item">
				<span class="meta-label">Direction</span>
				<div class="meta-directions">
					<?php foreach ( $directions as $direction ) : ?>
						<div class="meta-tag">
							<?php echo esc_html( $direction->name ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
        <div class="meta-item">
            <span class="meta-label">Year</span>
            <?php echo esc_html($year); ?>
        </div>
        
        <div class="meta-item">
            <span class="meta-label">Owner</span>
            <?php echo esc_html($owner); ?>
        </div>

        <?php if ( $sponsor ) : ?>
            <div class="meta-item">
                <span class="meta-label">Sponsor</span>
                <?php echo esc_html($sponsor); ?>
            </div>
        <?php endif; ?>

        <?php if ( $link ) : ?>
            <div class="meta-item">
                <span class="meta-label">Publication</span>
                <a href="<?php echo esc_url($link); ?>" target="_blank">View Link &rarr;</a>
            </div>
        <?php endif; ?>

    </div>
    <?php
    // Return the HTML buffer
    return ob_get_clean();
}