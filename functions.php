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

// Helper function to get sponsor data
function get_sponsor_data($sponsor) {
    if (is_array($sponsor) && isset($sponsor[0])) {
        $id = $sponsor[0];
        $post = get_post($id);
        return $post ? ['title' => $post->post_title, 'id' => $post->ID] : ['title' => '', 'id' => ''];
    }
    return ['title' => '', 'id' => ''];
}

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
        <?php if ( $sponsor ) : 
            $data = get_sponsor_data($sponsor);
            ?>
            <div class="meta-item">
                <div class="meta-label">Sponsor</div>
                <div class="meta-value">
                    <?php if ($data['id']) : ?>
                        <a href="<?php echo esc_url( get_permalink( $data['id'] ) ); ?>"><?php echo esc_html( $data['title'] ); ?></a>
                    <?php else : ?>
                        <?php echo esc_html( $data['title'] ); ?>
                    <?php endif; ?>
                </div>
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

// Register Project CPT
function ihbi_register_project_cpt() {
    register_post_type( 'project', [
        'label'         => 'Projects',
        'public'        => true,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-portfolio',
        'supports'      => [ 'title', 'editor', 'thumbnail' ],
        'rewrite'       => [ 'slug' => 'projects' ],
        'has_archive'   => true,
    ] );
}
add_action( 'init', 'ihbi_register_project_cpt' );

// Register Direction taxonomy
function ihbi_register_direction_taxonomy() {
    register_taxonomy( 'direction', 'project', [
        'label'        => 'Directions',
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => [ 'slug' => 'direction' ],
        'show_in_rest' => true,
    ] );
}
add_action( 'init', 'ihbi_register_direction_taxonomy' );

// Register ACF fields for Project CPT
function ihbi_register_project_fields() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group([
        'key'      => 'group_project_fields',
        'title'    => 'Project Details',
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'project',
                ],
            ],
        ],
        'fields' => [
            [
                'key'          => 'field_project_year',
                'label'        => 'Year',
                'name'         => 'project_year',
                'type'         => 'number',
                'instructions' => 'The year the project was completed or started.',
                'required'     => 0,
                'min'          => 1900,
                'max'          => 2100,
                'step'         => 1,
            ],
            [
                'key'          => 'field_project_owner',
                'label'        => 'Owner',
                'name'         => 'project_owner',
                'type'         => 'text',
                'instructions' => 'The person or team responsible for this project.',
                'required'     => 0,
            ],
            [
                'key'               => 'field_project_sponsor',
                'label'             => 'Sponsor',
                'name'              => 'project_sponsor',
                'type'              => 'relationship',
                'instructions'      => 'Link to a sponsor post if this project was externally funded.',
                'required'          => 0,
                'post_type'         => [ 'sponsor' ],
                'filters'           => [ 'search' ],
                'return_format'     => 'object',
                'min'               => 0,
                'max'               => 1,
            ],
            [
                'key'          => 'field_publication_link',
                'label'        => 'Publication Link',
                'name'         => 'publication_link',
                'type'         => 'url',
                'instructions' => 'A link to a published paper or external resource.',
                'required'     => 0,
            ],
        ],
    ]);
}
add_action( 'acf/init', 'ihbi_register_project_fields' );

// Register Sponsor CPT
function ihbi_register_sponsor_cpt() {
    register_post_type( 'sponsor', [
        'label'         => 'Sponsors',
        'public'        => true,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-awards',
        'supports'      => [ 'title' ],
        'rewrite'       => [ 'slug' => 'sponsors' ],
        'has_archive'  => true,
    ] );
}
add_action( 'init', 'ihbi_register_sponsor_cpt' );

add_shortcode( 'funding_list', 'render_funding_list' );

function render_funding_list() {
    $sponsors = get_posts([
        'post_type'      => 'sponsor',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ]);

    if ( empty( $sponsors ) ) return '';

    ob_start();
    ?>
    <div class="funding-list">
        <?php foreach ( $sponsors as $sponsor ) :
            $description = get_field( 'sponsor_description', $sponsor->ID );
            $projects    = get_field( 'related_projects', $sponsor->ID );
        ?>
        <div class="funding-item">
            <div class="funding-info">
                <span class="funding-name"><?php echo esc_html( $sponsor->post_title ); ?></span>
                <?php if ( $description ) : ?>
                    <span class="funding-description"><?php echo esc_html( $description ); ?></span>
                <?php endif; ?>
            </div>
            <?php if ( $projects ) : ?>
            <div class="funding-links">
                <?php foreach ( $projects as $project ) : ?>
                    <a href="<?php echo esc_url( get_permalink( $project->ID ) ); ?>" class="funding-link">
                        <?php echo esc_html( $project->post_title ); ?> &rarr;
                    </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <hr class="funding-divider" />
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}

// Register ACF fields for Sponsor CPT
function ihbi_register_sponsor_fields() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group([
        'key'      => 'group_sponsor_fields',
        'title'    => 'Sponsor Details',
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'sponsor',
                ],
            ],
        ],
        'fields' => [
            [
                'key'          => 'field_sponsor_description',
                'label'        => 'Description',
                'name'         => 'sponsor_description',
                'type'         => 'textarea',
                'instructions' => 'A short line describing what this sponsor funded.',
                'required'     => 0,
                'rows'         => 3,
                'new_lines'    => 'br',
            ],
            [
                'key'               => 'field_sponsor_related_projects',
                'label'             => 'Related Projects',
                'name'              => 'related_projects',
                'type'              => 'relationship',
                'instructions'      => 'Select one or more projects this sponsor helped fund.',
                'required'          => 0,
                'post_type'         => [ 'project' ],
                'filters'           => [ 'search' ],
                'return_format'     => 'object',
                'min'               => 0,
                'max'               => 0,
            ],
        ],
    ]);
}
add_action( 'acf/init', 'ihbi_register_sponsor_fields' );

add_shortcode( 'sponsor_details', 'render_sponsor_details' );

function render_sponsor_details() {
    global $post;
    if ( ! $post || get_post_type( $post->ID ) !== 'sponsor' ) return '';

    $description = get_field( 'sponsor_description' );
    $projects    = get_field( 'related_projects' );

    ob_start();
    ?>
    <div class="sponsor-details">
        <?php if ( $description ) : ?>
            <p class="sponsor-description">
                <?php echo wp_kses_post( $description ); ?>
            </p>
        <?php endif; ?>

        <?php if ( $projects ) : ?>
            <div class="sponsor-projects">
                <h2 class="sponsor-projects-title">Funded Projects</h2>
                <div class="sponsor-project-list">
                    <?php foreach ( $projects as $project ) : ?>
                        <a href="<?php echo esc_url( get_permalink( $project->ID ) ); ?>" class="sponsor-project-link">
                            <?php echo esc_html( $project->post_title ); ?> &rarr;
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}