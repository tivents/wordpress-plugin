<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

$block_content = '';

if (get_option('tivents_partner_id')) {
    if (isset( $attributes['activated'] ) &&  $attributes['activated']) {

        if (!empty($attributes['productId'])) {
            $apiURL = 'https://products.tivents.net/public/v2/'.$attributes['productId'];
            $product = tivents_call_api($apiURL);

            if (is_null($product)) {
                $block_content = '<p ' . get_block_wrapper_attributes() . '>Product not found</p>';
            } else {
                if (isset( $attributes['showChildren'] ) &&  $attributes['showChildren'] && $product['product_group_id']) {
                    $apiURL = 'https://products.tivents.net/public/v2?_sortField=start&_sortDir=ASC';
                    $filter['product_group_id'] = $product['product_group_id'];
                    $filter['hosts_globalid'] = get_option('tivents_partner_id');
                    $apiURL .= '&_filters=' . json_encode( $filter );
                    $children = tivents_call_api($apiURL);
                }
                $block_content = '<div class="tivents-product-details">';

                if (isset( $attributes['showTitle'] ) &&  $attributes['showTitle']) {
                    $block_content .= '<div class="row tivents-product-details-row">'.$product['name'].'</div>';
                }

                if (isset( $attributes['showImage'] ) &&  $attributes['showImage']) {
                    $block_content .= '<div class="row tivents-product-details-row"><div class="col-md-3"><img src="'.$product['image_url'].'"/></div><div class="col-md-9">'.$product['date'].'</div></div>';
                } else {
                    $block_content .= '<div class="row tivents-product-details-row"><div class="col-md-9">'.$product['date'].'</div></div>';
                }

                if (isset( $attributes['showDescription'] ) &&  $attributes['showDescription']) {
                    $block_content .= '<div class="row tivents-product-details-row">'.$product['info'].'</div>';
                }

                if (isset( $attributes['showChildren'] ) &&  $attributes['showChildren']) {
                    if (isset($children) && $children['total'] > 0) {
                        $block_content .= '<div class="row tivents-product-details-row">';
                        $block_content .= '<div class="tivents-product-details-row tivents-children-wrapper"><p>Weitere Termine</p>';
                        foreach ($children['items'] as $child) {
                            if($child['status'] >= 400 && $child['status'] <= 499) {
                                $block_content .= '<div class="tivents-children-item tivents-product-details-row"><div class="col-md-6">' . $child['date'] . '</div><div class="col-md-6 float-end"><a href="' . $child['short_url'] . '" class="btn btn-sm btn-success">Jetzt buchen</a></div></div>';
                            }
                        }
                        $block_content .= '</div></div>';
                    }
                }
                else {
                    if($product['status'] >= 400 && $product['status'] <= 499) {
                        $block_content .= '<a href="'.$product['short_url'].'" class="btn btn-sm btn-success">Jetzt buchen</a>';
                    }
                }
                $block_content .= '</div>';
            }
        }
    }
} else {
    $block_content = '<p ' . get_block_wrapper_attributes() . '>No vendor provided</p>';
}


echo wp_kses_post( $block_content );