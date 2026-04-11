<?php
function ihbi_theme_styles() {
    wp_enqueue_style(
        'ihbi-theme-style',
        get_stylesheet_uri(),
        [],
        wp_get_theme()->get( 'Version' ) . '.' . time()
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
        'supports'      => [ 'title', 'editor', 'thumbnail', 'comments' ],
        'rewrite'       => [ 'slug' => 'projects' ],
        'has_archive'   => true,
    ] );
}
add_action( 'init', 'ihbi_register_project_cpt' );

// Register Direction taxonomy
function ihbi_register_direction_taxonomy() {
    register_taxonomy( 'direction', ['project', 'publication'], [
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
                <div class="funding-name"><?php echo esc_html( $sponsor->post_title ); ?></div>
                <?php if ( $description ) : ?>
                    <div class="funding-description"><?php echo esc_html( $description ); ?></div>
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

function ihbi_register_block_styles() {
    register_block_style( 'core/group', [
        'name'  => 'card',
        'label' => 'Card',
    ]);
}
add_action( 'init', 'ihbi_register_block_styles' );

// Register Publication CPT
function ihbi_register_publication_cpt() {
    register_post_type( 'publication', [
        'label'         => 'Publications',
        'public'        => true,
        'show_in_rest'  => true,
        'menu_icon'     => 'dashicons-book-alt',
        'supports'      => [ 'title' ],
        'rewrite'       => [ 'slug' => 'publications' ],
        'has_archive'   => true,
    ] );
}
add_action( 'init', 'ihbi_register_publication_cpt' );

// Register Publication Type taxonomy
function ihbi_register_publication_type_taxonomy() {
    register_taxonomy( 'publication_type', 'publication', [
        'label'        => 'Publication Type',
        'public'       => true,
        'hierarchical' => false,
        'rewrite'      => [ 'slug' => 'publication-type' ],
        'show_in_rest' => true,
    ] );
}
add_action( 'init', 'ihbi_register_publication_type_taxonomy' );

// Register ACF fields for Publication CPT
function ihbi_register_publication_fields() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group([
        'key'      => 'group_publication_fields',
        'title'    => 'Publication Details',
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'publication',
                ],
            ],
        ],
        'fields' => [
            [
                'key'          => 'field_publication_authors',
                'label'        => 'Authors',
                'name'         => 'publication_authors',
                'type'         => 'textarea',
                'instructions' => 'List all authors, e.g. "Smith J, Jones A, Eriksson B".',
                'required'     => 1,
                'rows'         => 2,
                'new_lines'    => '',
            ],
            [
                'key'          => 'field_publication_year',
                'label'        => 'Year',
                'name'         => 'publication_year',
                'type'         => 'number',
                'instructions' => 'Year of publication.',
                'required'     => 1,
                'min'          => 1900,
                'max'          => 2100,
                'step'         => 1,
            ],
            [
                'key'          => 'field_publication_doi',
                'label'        => 'DOI',
                'name'         => 'publication_doi',
                'type'         => 'text',
                'instructions' => 'Full link, e.g. "https://doi.org/10.1016/j.enbuild.2026.117260" or just the DOI suffix "10.1016/j.enbuild.2026.117260".',
                'required'     => 0,
                'placeholder'  => '10.1016/j.enbuild.2026.117260 or https://doi.org/10.1016/j.enbuild.2026.117260',
            ],
            [
                'key'               => 'field_publication_related_projects',
                'label'             => 'Related Projects',
                'name'              => 'publication_related_projects',
                'type'              => 'relationship',
                'instructions'      => 'Link this publication to one or more projects.',
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
add_action( 'acf/init', 'ihbi_register_publication_fields' );

// Publications list shortcode
add_shortcode( 'publication_list', 'render_publication_list' );

function render_publication_list() {
    $args = [
        'post_type'      => 'publication',
        'posts_per_page' => -1,
        'orderby'        => 'meta_value_num',
        'meta_key'       => 'publication_year',
        'order'          => 'DESC',
    ];

    $publications = get_posts( $args );

    if ( empty( $publications ) ) return '<p>No publications found.</p>';

    ob_start();
    ?>
    <div class="publication-list">
        <?php
        // Group publications by year
        $grouped = [];
        foreach ( $publications as $pub ) {
            $year = get_field( 'publication_year', $pub->ID ) ?: 'Unknown';
            $grouped[ $year ][] = $pub;
        }
        krsort( $grouped );

        foreach ( $grouped as $year => $pubs ) : ?>
            <div class="publication-year-group">
                <h2 class="publication-year-heading"><?php echo esc_html( $year ); ?></h2>
                <?php foreach ( $pubs as $pub ) :
                    $authors  = get_field( 'publication_authors', $pub->ID );
                    $doi_input = get_field( 'publication_doi', $pub->ID );
                    // Handle both full URLs and DOI-only inputs
                    if ( $doi_input ) {
                        $doi_url = ( strpos( $doi_input, 'http' ) === 0 ) ? $doi_input : 'https://doi.org/' . $doi_input;
                        $doi_display = ( strpos( $doi_input, 'http' ) === 0 ) ? $doi_input : $doi_input;
                    } else {
                        $doi_url = '';
                        $doi_display = '';
                    }
                    $pub_types = get_the_terms( $pub->ID, 'publication_type' );
                    $directions = get_the_terms( $pub->ID, 'direction' );
                    $projects = get_field( 'publication_related_projects', $pub->ID );
                ?>
                <div class="publication-item"><div class="publication-meta-top">
                        <?php if ( $pub_types && ! is_wp_error( $pub_types ) ) : ?>
                            <div class="publication-type-tag">
                                <?php echo esc_html( $pub_types[0]->name ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <p class="publication-title">
                        <?php if ( $doi_url ) : ?>
                            <a href="<?php echo esc_url( $doi_url ); ?>" target="_blank"><?php echo esc_html( $pub->post_title ); ?></a>
                        <?php else : ?>
                            <?php echo esc_html( $pub->post_title ); ?>
                        <?php endif; ?>
                    </p>
                    <?php if ( $authors ) : ?>
                        <p class="publication-authors"><?php echo esc_html( $authors ); ?></p>
                    <?php else : ?>
                        <p class="publication-authors" style="color: red;">No authors set</p>
                    <?php endif; ?>
                    <div class="publication-footer">
                        <?php if ( $doi_url ) : ?>
                            <a href="<?php echo esc_url( $doi_url ); ?>" class="publication-doi" target="_blank">DOI: <?php echo esc_html( $doi_display ); ?></a>
                        <?php endif; ?>
                        <?php if ( $projects ) : ?>
                            <div class="publication-projects">
                                <?php foreach ( $projects as $project ) : ?>
                                    <p>Related project: </p><a href="<?php echo esc_url( get_permalink( $project->ID ) ); ?>" class="publication-project-link"><?php echo esc_html( $project->post_title ); ?> &rarr;</a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ( $directions && ! is_wp_error( $directions ) ) : ?>
                        <?php foreach ( $directions as $direction ) : ?>
                            <div class="publication-direction-tag"><?php echo esc_html( $direction->name ); ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?></div>
                <hr class="publication-divider" />
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return shortcode_unautop( trim( ob_get_clean() ) );
}
