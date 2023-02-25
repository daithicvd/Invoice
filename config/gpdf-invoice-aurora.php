<?php

namespace GFPDF\Templates\Config;

use GFPDF\Helper\Helper_Interface_Config;
use GFPDF\Helper\Helper_Interface_Setup_TearDown;

use GPDFAPI;

/**
 * Invoice Aurora configuration file
 *
 * @package     Gravity PDF
 * @copyright   Copyright (c) 2016, Blue Liquid Designs
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
    This file is part of Gravity PDF.

    Gravity PDF – Copyright (C) 2016, Blue Liquid Designs

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

class Gpdf_Invoice_Aurora implements Helper_Interface_Config, Helper_Interface_Setup_TearDown {

	/**
	 * Runs once when the template is initially installed
	 *
	 * Install the Google Font Lato if it doesn't already exist for use with this PDF template
	 *
	 * @since 1.0
	 */
	public function setUp() {

		$font_data =  [
			'font_name'   => 'Quicksand',
			'regular'     => __DIR__ . '/../install/gpdf-invoice-aurora/font-quicksand/Quicksand-Regular.ttf',
			'italics'     => __DIR__ . '/../install/gpdf-invoice-aurora/font-quicksand/Quicksand-Italic.ttf',
			'bold'        => __DIR__ . '/../install/gpdf-invoice-aurora/font-quicksand/Quicksand-Bold.ttf',
			'bolditalics' => __DIR__ . '/../install/gpdf-invoice-aurora/font-quicksand/Quicksand-BoldItalic.ttf',
		];

		GPDFAPI::add_pdf_font( $font_data );
	}

	/**
	 * Runs once when the template is deleted
	 *
	 * Clean up additional directories
	 *
	 * @since 1.0
	 */
	public function tearDown() {
		$misc = GPDFAPI::get_misc_class();

		/* Cleanup files */
		$misc->rmdir( __DIR__ . '/../install/gpdf-invoice-aurora' );
	}

	/**
	 * Return the templates configuration structure which control what extra fields will be shown in the "Template" tab when configuring a form's PDF.
	 *
	 * The fields key is based on our \GFPDF\Helper\Helper_Abstract_Options Settings API
	 *
	 * See the Helper_Options_Fields::register_settings() method for the exact fields that can be passed in
	 *
	 * @return array The array, split into core components and custom fields
	 *
	 * @since 1.0
	 */
	public function configuration() {

		$options = GPDFAPI::get_options_class();
		$data    = GPDFAPI::get_data_class();

		return [

			/* Create custom fields to control the look and feel of a template */
			'fields' => [

				'invoice_desc1' => [
					'id'   => 'invoice_desc1',
					'type' => 'descriptive_text',
					'desc' => '<h4 class="section-title">' . esc_html__( 'Company Information', 'gravity-forms-pdf-extended' ) . '</h4>',
					'class' => 'gfpdf-no-padding',
				],

				/*
				 * Company Information
				 */
				'invoice_logo'  => [
					'id'   => 'invoice_logo',
					'name' => esc_html__( 'Logo', 'gravity-forms-pdf-extended' ),
					'type' => 'upload',
					'desc' => esc_html__( 'The logo to include in the invoice. An image 500px wide is suitable in most cases.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_logo_position' => [
					'id'   => 'invoice_logo_position',
					'name' => esc_html__( 'Logo Position', 'gravity-forms-pdf-extended' ),
					'type' => 'radio',
					'options' => [
						'Left'  => esc_html__( 'Left', 'gravity-forms-pdf-extended' ),
						'Right' => esc_html__( 'Right', 'gravity-forms-pdf-extended' ),
					],
					'std'     => 'Left',
					'tooltip'    => '<h6>' . esc_html__( 'Logo Position', 'gravity-forms-pdf-extended' ) . '</h6>' . esc_html__( 'Easily change the logo to the left or right hand side of the invoice.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_business_name' => [
					'id'   => 'invoice_business_name',
					'name' => esc_html__( 'Business Name', 'gravity-forms-pdf-extended' ),
					'type' => 'text',
					'desc' => esc_html__( 'Enter your Business or Trading Name', 'gravity-forms-pdf-extended' ),
				],

				'invoice_business_address_line1' => [
					'id'   => 'invoice_business_address_line1',
					'name' => esc_html__( 'Business Address Line 1', 'gravity-forms-pdf-extended' ),
					'type' => 'text',
				],

				'invoice_business_address_line2' => [
					'id'   => 'invoice_business_address_line2',
					'name' => esc_html__( 'Address Line 2', 'gravity-forms-pdf-extended' ),
					'type' => 'text',
				],

				'invoice_business_address_line3' => [
					'id'   => 'invoice_business_address_line3',
					'name' => esc_html__( 'Address Line 3', 'gravity-forms-pdf-extended' ),
					'type' => 'text',
				],

				'invoice_business_number' => [
					'id'   => 'invoice_business_number',
					'name' => esc_html__( 'Registration Number', 'gravity-forms-pdf-extended' ),
					'type' => 'text',
					'desc' => esc_html__( 'In this US this would be your EIN, in Australia your ABN, in the UK your CRN.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_business_contact1' => [
					'id'   => 'invoice_business_contact1',
					'name' => esc_html__( 'Contact Information', 'gravity-forms-pdf-extended' ),
					'type' => 'text',
					'desc' => esc_html__( 'Include a phone number, email or fax.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_business_contact2' => [
					'id'   => 'invoice_business_contact2',
					'name' => esc_html__( 'Alternate Contact Information', 'gravity-forms-pdf-extended' ),
					'type' => 'text',
					'desc' => esc_html__( 'Include a phone number, email or fax.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_business_additional1' => [
					'id'   => 'invoice_business_additional1',
					'name' => esc_html__( 'Additional Information', 'gravity-forms-pdf-extended' ),
					'type' => 'text',
					'desc' => esc_html__( 'Enter any additional details to include below the contact details.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_business_additional2' => [
					'id'   => 'invoice_business_additional2',
					'type' => 'text',
					'desc' => esc_html__( 'Enter any additional details to include below the contact details.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_break1'    => [
					'id'   => 'invoice_break1',
					'type' => 'descriptive_text',
					'desc' => '<h4 class="section-title">'. esc_html__( 'Invoice Configuration', 'gravity-forms-pdf-extended' ) . '</h4>',
					'class' => 'gfpdf-no-padding',
				],

				/*
				 * Invoice Information
				 */
				'invoice_no_prefix' => [
					'id'   => 'invoice_no_prefix',
					'name' => esc_html__( 'Invoice Number Prefix', 'gravity-forms-pdf-extended' ),
					'type' => 'text',
					'desc' => esc_html__( 'This field is prepended to the invoice number.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_no' => [
					'id'         => 'invoice_no',
					'name'       => esc_html__( 'Invoice Number', 'gravity-forms-pdf-extended' ),
					'type'       => 'text',
					'std'        => '{entry_id}',
					'desc'       => esc_html__( 'The entry ID is used by default, but you can use any merge tags available.', 'gravity-forms-pdf-extended' ),
					'tooltip'    => '<h6>' . esc_html__( 'Invoice Number', 'gravity-forms-pdf-extended' ) . '</h6>' . sprintf( esc_html__( 'If you need more control over the invoice number (like making it sequential) we recommend the %sGravity Perks Unique ID add-on%s.', 'gravity-forms-pdf-extended' ), '<a href="https://gravitywiz.com/documentation/gp-unique-id/?ref=78">', '</a>' ),
					'inputClass' => 'merge-tag-support mt-hide_all_fields',
				],

				'invoice_no_suffix' => [
					'id'   => 'invoice_no_suffix',
					'name' => esc_html__( 'Invoice Number Suffix', 'gravity-forms-pdf-extended' ),
					'type' => 'text',
					'desc' => esc_html__( 'This field is appended to the invoice number.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_date_format' => [
					'id'         => 'invoice_date_format',
					'name'       => esc_html__( 'Date Format', 'gravity-forms-pdf-extended' ),
					'desc'       => esc_html__( 'Select the date format to show in the invoice.', 'gravity-forms-pdf-extended' ),
					'type'       => 'select',
					'options'    => [
						'd/m/Y'  => '29/11/2016',
						'm/d/Y'  => '11/29/2016',
						'Y-m-d'  => '2016-11-29',
						'F j, Y' => 'November 29, 2016',
					],
					'inputClass' => 'large',
					'chosen'     => true,
				],

				'invoice_due_date' => [
					'id'   => 'invoice_due_date',
					'name' => esc_html__( 'Invoice Due Date', 'gravity-forms-pdf-extended' ),
					'desc'  => esc_html__( 'Enter the number of days the buyer has to pay. Set to 0 to exclude from invoice.', 'gravity-forms-pdf-extended' ),
					'desc2' => ' days',
					'type'  => 'number',
					'size'  => 'small',
					'std'   => 0,
				],

				'invoice_break2'      => [
					'id'   => 'invoice_break2',
					'type' => 'descriptive_text',
					'desc' => '<h4 class="section-title">'. esc_html__( 'Buyer Information', 'gravity-forms-pdf-extended' ) . '</h4>',
					'class' => 'gfpdf-no-padding',
				],

				/*
				 * Buyer Information
				 */
				'invoice_buyer_name' => [
					'id'         => 'invoice_buyer_name',
					'name'       => esc_html__( 'Buyer Name', 'gravity-forms-pdf-extended' ),
					'type'       => 'text',
					'desc'       => esc_html__( "Include the form mergetags for the buyer's name field.", 'gravity-forms-pdf-extended' ),
					'inputClass' => 'merge-tag-support mt-hide_all_fields',
				],

				'invoice_buyer_address_line1' => [
					'id'         => 'invoice_buyer_address_line1',
					'name'       => esc_html__( 'Buyer Address Line 1', 'gravity-forms-pdf-extended' ),
					'desc'       => esc_html__( "Include the form mergetags for the buyer's address field.", 'gravity-forms-pdf-extended' ),
					'type'       => 'text',
					'inputClass' => 'merge-tag-support mt-hide_all_fields',
				],

				'invoice_buyer_address_line2' => [
					'id'         => 'invoice_buyer_address_line2',
					'name'       => esc_html__( 'Address Line 2', 'gravity-forms-pdf-extended' ),
					'type'       => 'text',
					'inputClass' => 'merge-tag-support mt-hide_all_fields',
				],

				'invoice_buyer_address_line3' => [
					'id'         => 'invoice_buyer_address_line3',
					'name'       => esc_html__( 'Address Line 3', 'gravity-forms-pdf-extended' ),
					'type'       => 'text',
					'inputClass' => 'merge-tag-support mt-hide_all_fields',
				],

				'invoice_buyer_business_number' => [
					'id'         => 'invoice_buyer_business_number',
					'name'       => esc_html__( 'Buyer Registration Number', 'gravity-forms-pdf-extended' ),
					'type'       => 'text',
					'desc'       => esc_html__( "If applicable, include the form mergetag for the buyer's Business Registration Number field.", 'gravity-forms-pdf-extended' ),
					'inputClass' => 'merge-tag-support mt-hide_all_fields',
				],

				'invoice_buyer_contact1' => [
					'id'   => 'invoice_buyer_contact1',
					'name' => esc_html__( 'Buyer Contact Info', 'gravity-forms-pdf-extended' ),
					'type' => 'text',
					'desc' => esc_html__( "If applicable, include the form mergetag for the buyer's contact details (phone, mobile, email).", 'gravity-forms-pdf-extended' ),
					'inputClass' => 'merge-tag-support mt-hide_all_fields',
				],

				'invoice_buyer_contact2' => [
					'id'   => 'invoice_buyer_contact2',
					'name' => esc_html__( 'Buyer Alternate Contact Info', 'gravity-forms-pdf-extended' ),
					'type' => 'text',
					'desc' => esc_html__( "If applicable, include the form mergetag for the buyer's contact details (phone, mobile, email).", 'gravity-forms-pdf-extended' ),
					'inputClass' => 'merge-tag-support mt-hide_all_fields',
				],


				'invoice_break3'     => [
					'id'   => 'invoice_break3',
					'type' => 'descriptive_text',
					'desc' => '<h4 class="section-title">' . esc_html__( 'Tax', 'gravity-forms-pdf-extended' ) . '</h4>',
					'class' => 'gfpdf-no-padding',
				],

				/*
				 * Basic Tax
				 */
				'invoice_enable_tax' => [
					'id'   => 'invoice_enable_tax',
					'name' => esc_html__( 'Tax', 'gravity-forms-pdf-extended' ),
					'type' => 'checkbox',
					'desc' => esc_html__( 'Enable Basic Tax Calculations', 'gravity-forms-pdf-extended' ) . '<br><br>' .
					          esc_html__( 'When enabled, tax will be automatically calculated and included in the PDF. Restrictions include:', 'gravity-forms-pdf-extended' ) . '
					             <ol>
					                <li>' . esc_html__( 'All products will be classified as taxable', 'gravity-forms-pdf-extended' ) . '</li>
					                <li>' . esc_html__( 'All products will use the same tax rate', 'gravity-forms-pdf-extended' ) . '</li>
					                <li>' . esc_html__( 'All prices in Gravity Forms are to be tax inclusive', 'gravity-forms-pdf-extended' ) . '</li>
					             </ol>',
				],

				'invoice_tax_name' => [
					'id'   => 'invoice_tax_name',
					'name' => esc_html__( 'Tax Name', 'gravity-forms-pdf-extended' ),
					'type' => 'text',
					'std'  => 'Tax',
					'desc' => esc_html__( 'Enter the name of the tax being applied (GST, VAT, Sales Tax)', 'gravity-forms-pdf-extended' ),
				],

				'invoice_tax_rate' => [
					'id'    => 'invoice_tax_rate',
					'name'  => esc_html__( 'Tax Rate', 'gravity-forms-pdf-extended' ),
					'desc'  => esc_html__( 'The tax rate as a percentage.', 'gravity-forms-pdf-extended' ),
					'desc2' => '%',
					'type'  => 'number',
					'size'  => 'small',
					'std'   => 0,
				],

				'invoice_tax_rate_label' => [
					'id'   => 'invoice_tax_rate_label',
					'name' => esc_html__( 'Tax Rate Label', 'gravity-forms-pdf-extended' ),
					'type' => 'text',
					'std'  => esc_html__( 'Tax Rate:', 'gravity-forms-pdf-extended' ),
					'desc'    => esc_html__( 'Your Tax Rate label. This will be shown next to the percentage entered above.', 'gravity-forms-pdf-extended' ),
					'tooltip' => '<h6>' . esc_html__( 'Tax Rate Label', 'gravity-forms-pdf-extended' ) . '</h6> ' . esc_html__( 'If the label is blank the tax rate will not be shown in the PDF.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_enable_tax_on_shipping' => [
					'id'   => 'invoice_enable_tax_on_shipping',
					'name' => esc_html__( 'Tax Shipping', 'gravity-forms-pdf-extended' ),
					'type' => 'checkbox',
					'desc' => esc_html__( 'Enable Tax on Shipping Fee', 'gravity-forms-pdf-extended' ),
				],

				'invoice_hide_tax_in_table' => [
					'id'   => 'invoice_hide_tax_in_table',
					'name' => esc_html__( 'Tax Column', 'gravity-forms-pdf-extended' ),
					'type' => 'checkbox',
					'desc' => esc_html__( 'Hide Tax Column in Product Table?', 'gravity-forms-pdf-extended' ),
				],

				'invoice_tax_number' => [
					'id'      => 'invoice_tax_number',
					'name'    => esc_html__( 'Tax Number', 'gravity-forms-pdf-extended' ),
					'type'    => 'text',
					'desc'    => esc_html__( 'Enter your business tax number, if applicable.', 'gravity-forms-pdf-extended' ),
					'tooltip' => '<h6>'. esc_html__( 'Tax Number', 'gravity-forms-pdf-extended' ) . '</h6> ' . esc_html__( 'Companies that pay VAT are given a Tax Identification Number which should be included.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_tax_number_label' => [
					'id'   => 'invoice_tax_number_label',
					'name' => esc_html__( 'Tax Number Label', 'gravity-forms-pdf-extended' ),
					'type' => 'text',
					'std'  => esc_html__( 'Tax Number:', 'gravity-forms-pdf-extended' ),
					'desc'    => esc_html__( 'Your Tax Number label. This will not be shown if the above field is empty.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_break4'    => [
					'id'   => 'invoice_break4',
					'type' => 'descriptive_text',
					'desc' => '<h4 class="section-title">'. esc_html__( 'Extras', 'gravity-forms-pdf-extended' ) . '</h4>',
					'class' => 'gfpdf-no-padding',
				],

				/*
				 * Extra fields
				 */
				'invoice_watermark' => [
					'id'   => 'invoice_watermark',
					'name' => esc_html__( 'Watermark', 'gravity-forms-pdf-extended' ),
					'type' => 'text',
					'desc' => esc_html__( 'Includes a watermark across the PDF. Leave blank to disable.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_primary_highlight_colour' => [
					'id'   => 'invoice_primary_highlight_colour',
					'name' => esc_html__( 'Primary Color', 'gravity-forms-pdf-extended' ),
					'type' => 'color',
					'std'  => '#0070bd',
					'desc' => esc_html__( 'This is used as the background color for the invoice name and total.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_secondary_highlight_colour' => [
					'id'   => 'invoice_secondary_highlight_colour',
					'name' => esc_html__( 'Secondary Color', 'gravity-forms-pdf-extended' ),
					'type' => 'color',
					'std'  => '#4f4f4f',
					'desc' => esc_html__( 'This is used as the background color for the header and headings in the product table.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_additional_info' => [
					'id'         => 'invoice_additional_info',
					'name'       => esc_html__( 'Additional Information', 'gravity-forms-pdf-extended' ),
					'type'       => 'rich_editor',
					'size'       => 12,
					'desc'       => esc_html__( 'Enter any additional information you want to include for the buyer. This gets displayed below the product table.', 'gravity-forms-pdf-extended' ),
					'tooltip' => '<h6>'. esc_html__( 'Additional Info', 'gravity-forms-pdf-extended' ) . '</h6> ' . esc_html__( 'If relevant, you should include your payment terms. For example: when the payment is due or if there are any late fees. If offering your customers direct debit make sure you include your bank details.', 'gravity-forms-pdf-extended' ),
					'inputClass' => 'merge-tag-support mt-wp_editor mt-manual_position mt-position-right mt-hide_all_fields',
				],

				'invoice_footer' => [
					'id'         => 'invoice_footer',
					'name'       => esc_html__( 'Footer', 'gravity-forms-pdf-extended' ),
					'type'       => 'text',
					'desc'       => esc_html__( 'The footer is center-aligned at the bottom of the invoice.', 'gravity-forms-pdf-extended' ),
					'inputClass' => 'merge-tag-support mt-hide_all_fields',
				],

				'invoice_break5' => [
					'id'   => 'invoice_break5',
					'type' => 'descriptive_text',
					'desc' => '<h4 class="section-title">'. esc_html__( 'Labels', 'gravity-forms-pdf-extended' ) . '</h4>',
					'class' => 'gfpdf-no-padding',
				],

				/*
				 * PDF Labels
				 */
				'invoice_title_label'  => [
					'id'      => 'invoice_title_label',
					'name'    => esc_html__( 'Title Label', 'gravity-forms-pdf-extended' ),
					'type'    => 'text',
					'std'     => 'Invoice',
					'tooltip' => '<h6>' . esc_html__( 'Invoice Label', 'gravity-forms-pdf-extended' ) . '</h6> ' . esc_html__( 'If the Tax option is enabled and your an Australian company this should be changed to "Tax Invoice".', 'gravity-forms-pdf-extended' ),
					'desc'    => esc_html__( 'The document title included in the top right corner of the PDF.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_summary_label'  => [
					'id'      => 'invoice_summary_label',
					'name'    => esc_html__( 'Summary Label', 'gravity-forms-pdf-extended' ),
					'type'    => 'text',
					'std'     => 'Invoice Details',
					'desc'    => esc_html__( 'The heading included above the invoice summary section.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_date_label'  => [
					'id'      => 'invoice_date_label',
					'name'    => esc_html__( 'Invoice Date Label', 'gravity-forms-pdf-extended' ),
					'type'    => 'text',
					'std'     => esc_html__( 'Invoice Date:', 'gravity-forms-pdf-extended' ),
					'tooltip' => '<h6>' . esc_html__( 'Invoice Date Label', 'gravity-forms-pdf-extended' ) . '</h6> ' . esc_html__( 'If the label is blank the invoice date will not be shown in the PDF.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_due_date_label'  => [
					'id'      => 'invoice_due_date_label',
					'name'    => esc_html__( 'Invoice Due Date Label', 'gravity-forms-pdf-extended' ),
					'type'    => 'text',
					'std'     => esc_html__( 'Due Date:', 'gravity-forms-pdf-extended' ),
				],

				'invoice_number_label'  => [
					'id'      => 'invoice_number_label',
					'name'    => esc_html__( 'Invoice Number Label', 'gravity-forms-pdf-extended' ),
					'type'    => 'text',
					'std'     => esc_html__( 'Invoice #:', 'gravity-forms-pdf-extended' ),
					'tooltip' => '<h6>' . esc_html__( 'Invoice Number Label', 'gravity-forms-pdf-extended' ) . '</h6> ' . esc_html__( 'If the label is blank the invoice number will not be shown in the PDF.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_currency_label'  => [
					'id'      => 'invoice_currency_label',
					'name'    => esc_html__( 'Currency Type Label', 'gravity-forms-pdf-extended' ),
					'type'    => 'text',
					'std'     => esc_html__( 'Currency Type:', 'gravity-forms-pdf-extended' ),
					'tooltip' => '<h6>' . esc_html__( 'Currency Type Label', 'gravity-forms-pdf-extended' ) . '</h6> ' . esc_html__( 'If the label is blank the currency type will not be shown in the PDF.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_buyer_details_label'  => [
					'id'      => 'invoice_buyer_details_label',
					'name'    => esc_html__( 'Buyer Details Label', 'gravity-forms-pdf-extended' ),
					'type'    => 'text',
					'std'     => esc_html__( 'Invoice To:', 'gravity-forms-pdf-extended' ),
				],

				'invoice_qty_col_label'  => [
					'id'      => 'invoice_qty_col_label',
					'name'    => esc_html__( 'Quantity Column Label', 'gravity-forms-pdf-extended' ),
					'type'    => 'text',
					'std'     => esc_html__( 'Quantity', 'gravity-forms-pdf-extended' ),
				],

				'invoice_description_col_label'  => [
					'id'      => 'invoice_description_col_label',
					'name'    => esc_html__( 'Description Column Label', 'gravity-forms-pdf-extended' ),
					'type'    => 'text',
					'std'     => esc_html__( 'Description', 'gravity-forms-pdf-extended' ),
				],

				'invoice_unit_price_col_label'  => [
					'id'      => 'invoice_unit_price_col_label',
					'name'    => esc_html__( 'Unit Price Column Label', 'gravity-forms-pdf-extended' ),
					'type'    => 'text',
					'std'     => esc_html__( 'Unit Price', 'gravity-forms-pdf-extended' ),
				],

				'invoice_total_col_label'  => [
					'id'      => 'invoice_total_col_label',
					'name'    => esc_html__( 'Total Column Label', 'gravity-forms-pdf-extended' ),
					'type'    => 'text',
					'std'     => esc_html__( 'Total', 'gravity-forms-pdf-extended' ),
				],

				'invoice_subtotal_label'  => [
					'id'      => 'invoice_subtotal_label',
					'name'    => esc_html__( 'Subtotal Label', 'gravity-forms-pdf-extended' ),
					'type'    => 'text',
					'std'     => esc_html__( 'Subtotal', 'gravity-forms-pdf-extended' ),
					'tooltip' => '<h6>' . esc_html__( 'Subtotal Label', 'gravity-forms-pdf-extended' ) . '</h6> ' . esc_html__( 'If the label is blank the subtotal row will not be shown in the PDF.', 'gravity-forms-pdf-extended' ),
				],

				'invoice_overall_total_label'  => [
					'id'      => 'invoice_overall_total_label',
					'name'    => esc_html__( 'Overall Total Label', 'gravity-forms-pdf-extended' ),
					'type'    => 'text',
					'std'     => esc_html__( 'Total', 'gravity-forms-pdf-extended' ),
				],
			],
		];
	}
}