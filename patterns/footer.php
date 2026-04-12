<?php
/**
 * Title: Footer IHBI
 * Slug: ihbi/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Area: footer
 * Description: Footer with logo, address, navigation and social links.
 *
 * @package IHBI_Lab_Theme
 */
?>

<!-- wp:group {"metadata":{"patternName":"twentytwentyfive/footer","name":"Footer","description":"Footer columns with logo, title, tagline and links.","categories":["footer"]},"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|50"}},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"backgroundColor":"accent-3","textColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-base-color has-accent-3-background-color has-text-color has-background has-link-color" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--50)">
    <!-- wp:group {"align":"wide","className":"is-style-default","layout":{"type":"default"}} -->
    <div class="wp-block-group alignwide is-style-default">
        <!-- wp:group {"align":"full","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center","verticalAlignment":"top","orientation":"horizontal"}} -->
        <div class="wp-block-group alignfull">
            <!-- wp:columns {"style":{"layout":{"selfStretch":"fit","flexSize":null}}} -->
            <div class="wp-block-columns">
                <!-- wp:column {"width":"50%"} -->
                <div class="wp-block-column" style="flex-basis:50%">
                    <!-- wp:site-title {"level":2} /-->

                    <!-- wp:image {"id":2190,"sizeSlug":"full","linkDestination":"none","className":"is-style-default","style":{"spacing":{"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}}} -->
                    <figure class="wp-block-image size-full is-style-default" style="margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--50)"><img src="http://localhost:8888/wp-content/uploads/2026/04/Logo_Umea.png" alt="IHBI Logo" class="wp-image-2190"/></figure>
                    <!-- /wp:image -->

                    <!-- wp:heading {"className":"is-style-default","style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base"} -->
                    <h2 class="wp-block-heading is-style-default has-base-color has-text-color has-link-color">
                        Department of Applied Physics and Electronics 901 87 Umeå
                    </h2>
                    <!-- /wp:heading -->
                </div>
                <!-- /wp:column -->

                <!-- wp:column {"width":"50%"} -->
                <div class="wp-block-column" style="flex-basis:50%">
                    <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|80"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top","justifyContent":"center"}} -->
                    <div class="wp-block-group">
                        <!-- wp:navigation {"ref":2179,"overlayMenu":"never","layout":{"type":"flex","orientation":"vertical"}} /-->

                        <!-- wp:social-links {"iconColor":"base","iconColorValue":"#FFFFFF","openInNewTab":true,"showLabels":true,"className":"is-style-logos-only","layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
                        <ul class="wp-block-social-links has-visible-labels has-icon-color is-style-logos-only">
                            <!-- wp:social-link {"url":"test","service":"facebook","label":"Facebook"} /-->

                            <!-- wp:social-link {"url":"test","service":"linkedin","label":""} /-->

                            <!-- wp:social-link {"url":"test","service":"instagram"} /-->

                            <!-- wp:social-link {"url":"test","service":"youtube"} /-->

                            <!-- wp:social-link {"url":"test","service":"github"} /-->
                        </ul>
                        <!-- /wp:social-links -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:spacer {"height":"var:preset|spacing|40","width":"0px"} -->
                    <div style="height:var(--wp--preset--spacing--40);width:0px" aria-hidden="true" class="wp-block-spacer"></div>
                    <!-- /wp:spacer -->
                </div>
                <!-- /wp:column -->
            </div>
            <!-- /wp:columns -->
        </div>
        <!-- /wp:group -->

        <!-- wp:spacer {"height":"var:preset|spacing|70"} -->
        <div style="height:var(--wp--preset--spacing--70)" aria-hidden="true" class="wp-block-spacer"></div>
        <!-- /wp:spacer -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->