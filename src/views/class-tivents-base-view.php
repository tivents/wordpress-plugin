<?php

class Tivents_Base_View {

	public static function tivents_set_footer() {
		$div  = '<div class="col-12">';
		$div .= '<div id="tiv-footer" class="row text-right">';
		$div .= '<p class="text-right"><small><figcaption class="blockquote-footer">
        ' . esc_html( __( 'build with', 'tivents_products_feed' ) ) . ' <a href="https://tiv.li/GmOz" target="_blank" style="color: var(--tiv-prime-color)">TIVENTS WP Plugin</a> v<cite title="Source Title">' . constant( 'TIVENTPRO_CURRENT_VERSION' ) . '</cite>
  </figcaption>
</small></p>';

		$div .= '</div>';
		$div .= '</div>';
		return $div;
	}
}
