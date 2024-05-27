/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * Imports the InspectorControls component, which is used to wrap
 * the block's custom controls that will appear in in the Settings
 * Sidebar when the block is selected.
 *
 * Also imports the React hook that is used to mark the block wrapper
 * element. It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#inspectorcontrols
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { InspectorControls, useBlockProps } from '@wordpress/block-editor';

/**
 * Imports the necessary components that will be used to create
 * the user interface for the block's settings.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/components/panel/#panelbody
 * @see https://developer.wordpress.org/block-editor/reference-guides/components/text-control/
 * @see https://developer.wordpress.org/block-editor/reference-guides/components/toggle-control/
 */
import { PanelBody, TextControl, ToggleControl, SelectControl, Button  } from '@wordpress/components';

/**
 * Imports the useEffect React Hook. This is used to set an attribute when the
 * block is loaded in the Editor.
 *
 * @see https://react.dev/reference/react/useEffect
 */
import { useEffect } from 'react';
import apiFetch from "@wordpress/api-fetch";

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @param {Object}   props               Properties passed to the function.
 * @param {Object}   props.attributes    Available block attributes.
 * @param {Function} props.setAttributes Function that updates individual attributes.
 *
 * @return {Element} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
	const { activated, productId, showTitle, showChildren, displayType, showImage, showDescription, productName = 'Product not found' } = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'TIVENTS Product ID', 'tivents_products_feed' ) }>
					<ToggleControl
						checked={ activated }
						label={ __(
							'Add product id',
							'tivents_products_feed'
						) }
						onChange={ () =>
							setAttributes( {
								activated: ! activated,
							} )
						}
					/>
					{ activated && (
						<TextControl
							label={ __(
								'Product ID',
								'tivents_products_feed'
							) }
							value={ productId }
							onChange={function ( value ) {
								setAttributes( { productId: value } )

								if(value === '')  {
									setAttributes({productName: 'Product not found'})
								} else {
									apiFetch( { path: 'tivents/api/v1/products?id='+value } ).then( ( product ) => {
										if (product.hasOwnProperty('id')) {
											setAttributes({productName: product.name})
										}
										else {
											setAttributes({productName: 'Product not found'})
										}
									});
								}
							}}
						/>
					) }

					{ activated && productId && (
						<Button
							variant="secondary"
							label={ __(
								'Set title',
								'tivents_products_feed'
							) }
							onClick={function () {
								apiFetch( { path: 'tivents/api/v1/products?id='+productId } ).then( ( product ) => {
									wp.data.dispatch( 'core/editor' ).editPost({ title: product.name });
									setAttributes( { productName: product.name } )
								} );
							}}
						>Set Title</Button>
					) }
				</PanelBody>
				{ activated && productId && (
					<PanelBody title={ __( 'TIVENTS Design Settings', 'tivents_products_feed' ) }>
						<ToggleControl
							checked={ showTitle }
							label={ __(
								'Show Title',
								'tivents_products_feed'
							) }
							value={ showTitle }
							onChange={ ( value ) =>
								setAttributes( { showTitle: value } )
							}
						/>
						<ToggleControl
								checked={ showImage }
								label={ __(
									'Show image',
									'tivents_products_feed'
								) }
								onChange={ () =>
									setAttributes( {
										showImage: ! showImage,
									} )
								}
							/>
							<ToggleControl
								checked={ showDescription }
								label={ __(
									'Show description',
									'tivents_products_feed'
								) }
								onChange={ () =>
									setAttributes( {
										showDescription: ! showDescription,
									} )
								}
							/>
							<ToggleControl
								checked={ showChildren }
								label={ __(
									'Show children',
									'tivents_products_feed'
								) }
								onChange={ () =>
									setAttributes( {
										showChildren: ! showChildren,
									} )
								}
							/>
						{showChildren && (
							<SelectControl
								label={ __(
									'Display type',
									'tivents_products_feed'
								) }
								value={ displayType }
								options={ [
									{ label: 'List', value: 'list' },
									{ label: 'Grid', value: 'grid' },
									{ label: 'Calendar', value: 'calendar' },
								] }
								onChange={ ( displayType ) =>setAttributes( { displayType: value } ) }
								__nextHasNoMarginBottom
							/>
						)}
					</PanelBody>
				)}
			</InspectorControls>
			<p { ...useBlockProps() }>{ productName }</p>
		</>
	);
}
