<?php

/*
 * Template Name: Aurora Bank
 * Version: 1.0.3
 * Description: Aurora's sleek two-tone design, with smooth flowing edges and excellent use of white space, is perfect for businesses who want to add vibrancy to their invoices.  You have total control over the information included – from your business logo, name and address, to the labels used throughout the invoice (you might translate these to your native language). We even give you basic tax options so your invoices can meet your country's legislative requirements.
 * Author: Gravity PDF
 * Author URI: https://gravitypdf.com
 * Group: Invoices (Premium)
 * License: GPLv2
 * Required PDF Version: 4.1.0-beta1
 * Tags: invoice, basic tax, vat compatible, gst compatible
 */

/* Prevent direct access to the template */
if ( ! class_exists( 'GFForms' ) ) {
	return;
}

/*
 * All Gravity PDF 4.x templates have access to the following variables:
 *
 * $form (The current Gravity Form array)
 * $entry (The raw entry data)
 * $form_data (The processed entry data stored in an array)
 * $settings (the current PDF configuration)
 * $fields (an array of Gravity Form fields which can be accessed with their ID number)
 * $config (The initialised template config class – eg. /config/zadani.php)
 * $gfpdf (the main Gravity PDF object containing all our helper classes)
 * $args (contains an array of all variables - the ones being described right now - passed to the template)
 */

/*
 * Verify this PDF is compatible with the current form
 */
if ( ! isset( $form_data['products_totals'] ) ) {
	esc_html_e( 'This PDF template is not compatible with your Gravity Form. The template requires a Product field in your form.', 'gravity-forms-pdf-extended' );

	return;
}

/*
 * Load our template-specific apperance settings
 */
$gform = GPDFAPI::get_form_class();
$misc  = GPDFAPI::get_misc_class();

/* Prepare invoice styling info */
$logo          = ( ! empty( $settings['invoice_logo'] ) ) ? $settings['invoice_logo'] : '';
$logo_position = ( ! empty( $settings['invoice_logo_position'] ) ) ? $settings['invoice_logo_position'] : 'Right';

$primary_highlight_colour   = ( ! empty( $settings['invoice_primary_highlight_colour'] ) ) ? $settings['invoice_primary_highlight_colour'] : '#0070bd';
$secondary_highlight_colour = ( ! empty( $settings['invoice_secondary_highlight_colour'] ) ) ? $settings['invoice_secondary_highlight_colour'] : '#4f4f4f';

$primary_contrast_highlight_colour   = $misc->get_contrast( $primary_highlight_colour );
$secondary_contrast_highlight_colour = $misc->get_contrast( $secondary_highlight_colour );

/* Assign watermark and allow override through filter */
$watermark = ( ! empty( $settings['invoice_watermark'] ) ) ? $settings['invoice_watermark'] : '';
$watermark = apply_filters( 'gfpdf_invoice_watermark', $watermark, $settings, $entry, $form, $config );

/* Build the invoice number and allow override through filter */
$invoice_prefix       = ( ! empty( $settings['invoice_no_prefix'] ) ) ? $settings['invoice_no_prefix'] : '';
$invoice_suffix       = ( ! empty( $settings['invoice_no_suffix'] ) ) ? $settings['invoice_no_suffix'] : '';
$invoice_no           = ( ! empty( $settings['invoice_no'] ) ) ? $settings['invoice_no'] : '{entry_id}';
$invoice_no_formatted = $invoice_prefix . $invoice_no . $invoice_suffix;
$invoice_no_formatted = apply_filters( 'gfpdf_invoice_number', $invoice_no_formatted, $invoice_prefix, $invoice_suffix, $invoice_no, $settings, $entry, $form, $config );

/* Generate the date in the correct format and allow override of date format through filter */
$date_format  = ( ! empty( $settings['invoice_date_format'] ) ) ? $settings['invoice_date_format'] : '';
$date_format  = apply_filters( 'gfpdf_invoice_date_format', $date_format, $settings, $entry, $form, $config );
$invoice_date = date( $date_format, strtotime( $form_data['misc']['date_time'] ) );

$invoice_due_date_days = ( ! empty( $settings['invoice_due_date'] ) ) ? $settings['invoice_due_date'] : 0;
$invoice_due_date      = ( (int) $invoice_due_date_days > 0 ) ? date(
	$date_format,
	strtotime(
		sprintf(
			'%s + %s days',
			$form_data['misc']['date_time'],
			$invoice_due_date_days
		)
	)
) : '';

/* Assign currency format and allow override through filter */
$currency_type = apply_filters( 'gfpdf_invoice_currency_format', $form_data['misc']['currency'], $settings, $entry, $form, $config );

/* Assign Company Information */
$business_name          = ( ! empty( $settings['invoice_business_name'] ) ) ? $settings['invoice_business_name'] : '';
$business_address1      = ( ! empty( $settings['invoice_business_address_line1'] ) ) ? $settings['invoice_business_address_line1'] : '';
$business_address2      = ( ! empty( $settings['invoice_business_address_line2'] ) ) ? $settings['invoice_business_address_line2'] : '';
$business_address3      = ( ! empty( $settings['invoice_business_address_line3'] ) ) ? $settings['invoice_business_address_line3'] : '';
$business_number        = ( ! empty( $settings['invoice_business_number'] ) ) ? $settings['invoice_business_number'] : '';
$business_contact_info1 = ( ! empty( $settings['invoice_business_contact1'] ) ) ? $settings['invoice_business_contact1'] : '';
$business_contact_info2 = ( ! empty( $settings['invoice_business_contact2'] ) ) ? $settings['invoice_business_contact2'] : '';
$business_additional1   = ( ! empty( $settings['invoice_business_additional1'] ) ) ? $settings['invoice_business_additional1'] : '';
$business_additional2   = ( ! empty( $settings['invoice_business_additional2'] ) ) ? $settings['invoice_business_additional2'] : '';

$business_address = array_filter( [
	$business_name,
	$business_address1,
	$business_address2,
	$business_address3,
] );

$business_contact_details = array_filter( [
	$business_contact_info1,
	$business_contact_info2,
] );

$business_other_info = array_filter( [
	$business_number,
	$business_additional1,
	$business_additional2,
] );

/* Prepare Buyer Information */
$buyer_name          = ( ! empty( $settings['invoice_buyer_name'] ) ) ? $gform->process_tags( $settings['invoice_buyer_name'], $form, $entry ) : '';
$buyer_address1      = ( ! empty( $settings['invoice_buyer_address_line1'] ) ) ? $settings['invoice_buyer_address_line1'] : '';
$buyer_address2      = ( ! empty( $settings['invoice_buyer_address_line2'] ) ) ? $settings['invoice_buyer_address_line2'] : '';
$buyer_address3      = ( ! empty( $settings['invoice_buyer_address_line3'] ) ) ? $settings['invoice_buyer_address_line3'] : '';
$buyer_number        = ( ! empty( $settings['invoice_buyer_business_number'] ) ) ? $settings['invoice_buyer_business_number'] : '';
$buyer_contact_info1 = ( ! empty( $settings['invoice_buyer_contact1'] ) ) ? $settings['invoice_buyer_contact1'] : '';
$buyer_contact_info2 = ( ! empty( $settings['invoice_buyer_contact2'] ) ) ? $settings['invoice_buyer_contact2'] : '';

$buyer_info = [
	$buyer_address1,
	$buyer_address2,
	$buyer_address3,
	$buyer_contact_info1,
	$buyer_contact_info2,
	$buyer_number,
];

/* Pre-process merge tags so we can strip any empty values when displayed */
array_walk( $buyer_info, function( &$field ) use ( $gform, $form, $entry ) {
	/* Convert Merge Tags / Shortcodes */
	$field = $gform->process_tags( $field, $form, $entry );
	$field = do_shortcode( $field );

	/* Set fields with just "," characters (e.g an address field with empty merge tags) to blank */
	$field = ( strlen( trim( str_replace( ',', '', $field ) ) ) === 0 ) ? '' : $field;

	/* Set fields with two ",," characters (e.g an address field with empty merge tags) to one "," */
	$field = str_replace( [ ',,', ', ,', ',  ,' ], ',', $field );

	/* Remove White Space */
	$field = trim( $field );

	/* Remove trailing "," character */
	$field = ( substr( $field, -1 ) === ',' ) ? substr( $field, 0, -1 ) : $field;
} );

/* Product Calculations */
$should_calculate_tax = ( isset( $settings['invoice_enable_tax'] ) ) ? true : false;

/* Get the raw and formatted Subtotal */
$subtotal_cost           = $form_data['products_totals']['subtotal'];
$subtotal_cost_formatted = $form_data['products_totals']['subtotal_formatted'];

/* Get the raw and formatted Shipping Cost, and the shipping name */
$shipping_selection      = $form_data['products_totals']['shipping_name'];
$shipping_cost           = $form_data['products_totals']['shipping'];
$shipping_cost_formatted = $form_data['products_totals']['shipping_formatted'];

/* Get the raw and formatted Total Cost */
$total_cost           = $form_data['products_totals']['total'];
$total_cost_formatted = $form_data['products_totals']['total_formatted'];

/* If we are applying tax calculations...*/
if ( $should_calculate_tax ) {
	$should_hide_tax_in_summary = ( isset( $settings['invoice_hide_tax_in_summary'] ) ) ? true : false;
	$should_hide_tax_in_table   = ( isset( $settings['invoice_hide_tax_in_table'] ) ) ? true : false;
	$should_tax_shipping        = ( isset( $settings['invoice_enable_tax_on_shipping'] ) ) ? true : false;

	/*
	 * Get the correct tax rate
	 *
	 * Since the tax rate the user inputs is a percentage (i.e 10 for 10%, or 20.5 for 20.5%) we simply divide by 100
	 * to convert to decimal and then add 1 (ie. 1.1 for 10% or 1.205 for 20.5%).
	 *
	 * This number is then used to calculate the original value before tax by dividing a total by our calculation tax rate
	 *
	 * ie. $subtotal_before_tax = $subtotal / 1.1
	 */
	$tax_rate             = ( ! empty( $settings['invoice_tax_rate'] ) ) ? (float) $settings['invoice_tax_rate'] : 0;
	$tax_rate_calculation = ( $tax_rate !== 0 ) ? $tax_rate / 100 + 1 : 1;

	/* Get tax label and additional business tax info */
	$tax_name   = ( ! empty( $settings['invoice_tax_name'] ) ) ? $settings['invoice_tax_name'] : '';
	$tax_number = ( ! empty( $settings['invoice_tax_number'] ) ) ? $settings['invoice_tax_number'] : '';

	/* Calculate the subtotal before tax */
	$subtotal_minus_tax           = $subtotal_cost / $tax_rate_calculation;
	$subtotal_minus_tax_formatted = GFCommon::to_money( $subtotal_minus_tax, $form_data['misc']['currency'] );

	/* Calculate the shipping before tax */
	$shipping_minus_tax           = $shipping_cost / $tax_rate_calculation;
	$shipping_minus_tax_formatted = GFCommon::to_money( $shipping_minus_tax, $form_data['misc']['currency'] );

	/* Calculate the actual tax amount */
	$tax           = $subtotal_cost - $subtotal_minus_tax;
	$tax           = ( $should_tax_shipping ) ? $tax + ( $shipping_cost - $shipping_minus_tax ) : $tax;
	$tax_formatted = GFCommon::to_money( $tax, $form_data['misc']['currency'] );
}

/* Additional Information */
$additional_info = ( ! empty( $settings['invoice_additional_info'] ) ) ? wpautop( wp_kses_post( $settings['invoice_additional_info'] ) ) : '';
$footer_info     = ( ! empty( $settings['invoice_footer'] ) ) ? wp_kses_post( $settings['invoice_footer'] ) : '';

/**
 * Mpdf Negative Margin hack to put the additional info in the correct location
 * based on the number of items in our tfoot section.
 *
 * @param int $i
 *
 * @return string
 *
 * @since 1.0
 */
$calculate_negative_margin = function( $i ) {
	switch ( $i ) {
		case '1':
			return '20';
		break;

		case '2':
			return '34.5';
		break;

		case '3':
			return '47';
		break;

		case '4':
			return '59.5';
		break;
	}
};

/*
 * Get Invoice Labels
 *
 * We've allowed the user to change all labels through the UI to account for different languages
 * If the default label doesn't work for them they can easily change it
 */
$title_label          = ( isset( $settings['invoice_title_label'] ) ) ? $settings['invoice_title_label'] : 'Invoice';
$summary_label        = ( isset( $settings['invoice_summary_label'] ) ) ? $settings['invoice_summary_label'] : 'Invoice Details';
$date_label           = ( isset( $settings['invoice_date_label'] ) ) ? $settings['invoice_date_label'] : 'Invoice Date:';
$due_date_label       = ( isset( $settings['invoice_due_date_label'] ) ) ? $settings['invoice_due_date_label'] : 'Due Date:';
$invoice_number_label = ( isset( $settings['invoice_number_label'] ) ) ? $settings['invoice_number_label'] : 'Invoice #:';
$currency_label       = ( isset( $settings['invoice_currency_label'] ) ) ? $settings['invoice_currency_label'] : 'Currency Type:';
$buyer_details_label  = ( isset( $settings['invoice_buyer_details_label'] ) ) ? $settings['invoice_buyer_details_label'] : 'Invoice To:';

if ( $should_calculate_tax ) {
	$tax_rate_label   = ( isset( $settings['invoice_tax_rate_label'] ) ) ? $settings['invoice_tax_rate_label'] : 'Tax Rate:';
	$tax_number_label = ( isset( $settings['invoice_tax_number_label'] ) ) ? $settings['invoice_tax_number_label'] : 'Tax Number:';
}

$qty_col_label        = ( isset( $settings['invoice_qty_col_label'] ) ) ? $settings['invoice_qty_col_label'] : 'Quantity';
$desc_col_label       = ( isset( $settings['invoice_description_col_label'] ) ) ? $settings['invoice_description_col_label'] : 'Description';
$unit_price_col_label = ( isset( $settings['invoice_unit_price_col_label'] ) ) ? $settings['invoice_unit_price_col_label'] : 'Unit Price';
$total_col_label      = ( isset( $settings['invoice_total_col_label'] ) ) ? $settings['invoice_total_col_label'] : 'Total';

$subtotal_label = ( isset( $settings['invoice_subtotal_label'] ) ) ? $settings['invoice_subtotal_label'] : 'Subtotal';
$total_label    = ( isset( $settings['invoice_overall_total_label'] ) ) ? $settings['invoice_overall_total_label'] : 'Total';

?>

<!-- Include styles needed for the PDF -->
<style>
    @page {
        margin: 10mm 0 20mm;
        footer: html_footer;
        margin-footer: 0mm;
    }

    @page :first {
        margin-top: 0;
    }

    .primary {
        background: <?php echo $primary_highlight_colour; ?>;
    }

    .secondary {
        background: <?php echo $secondary_highlight_colour; ?>;
    }

    /*
	 * Footer
	 */
    .footer thead td {
        border-top: 2px solid <?php echo $primary_highlight_colour; ?>;
    }

    .footer thead td:nth-child(2) {
        width: 0.5%;
        border-top: 2px solid <?php echo $primary_highlight_colour; ?>;
    }

    .footer thead td:nth-child(3) {
        border-top: 2px solid <?php echo $primary_highlight_colour; ?>;

        width: 25%;
    }

    .footer tbody td {
        color: <?php echo $primary_highlight_colour; ?>;

        text-align: center;
        font-size: 150%;
        line-height: 150%;
        font-weight: bold;
    }

    /*
     * Header
     */
    header,
    header td {
        height: 42mm;
    }

    header td {
        padding: 0 0 0 8mm;
    }

    #invoice-title {
        position: absolute;
        right: 0;
        top: 38mm;
        padding: 2.5mm 10mm;
        text-align: center;

        border-top: 5px solid #FFF;
        border-bottom: 5px solid #FFF;
        border-left: 5px solid #FFF;

        border-top-left-radius: 20px;
        border-bottom-left-radius: 20px;

        text-transform: uppercase;
        letter-spacing: 1.75mm;
        font-size: 175%;

        color: <?php echo $primary_contrast_highlight_colour; ?>;
    }

    #contact-details {
        position: absolute;
        top: 2mm;
        right: 2mm;
        width: 28%;
        height: 36mm;
        overflow: auto;
    }

    .contact-info {
        vertical-align: top;
        font-size: 90%;
        padding: 5px 0;

        color: <?php echo $secondary_contrast_highlight_colour; ?>;
    }

    .icon {
        text-align: center;
        width: 16%;
        vertical-align: top;
    }

    <?php if ( $logo_position === 'Right' ): ?>
    header table {
        margin-left: 40%;
        width: 95%;
    }

    header table td {
        text-align: right;
        height: 38mm;
    }

    #contact-details {
        left: 5mm;
        right: auto;
    }

    <?php endif; ?>

    /*
     * Client Details
     */
    #client-details {
        position: absolute;
        top: 45mm;
        left: 4mm;

        width: 40%;
        height: 57mm;

        overflow: auto;
    }

    #client-details-wrapper {
        height: 54mm;
        vertical-align: middle;
    }

    #client-details-title {
        font-size: 140%;
        text-decoration: underline;
    }

    .buyer-name {
        font-weight: bold;
        font-size: 125%;
    }

    /* Invoice Details */
    #invoice-details {
        padding-top: 10mm;

        margin-left: 43%;
        margin-right: 2mm;

        height: 35mm;
    }

    #invoice-details h2 {
        margin: 10mm 0 3mm;
        padding: 0;

        color: <?php echo $misc->change_brightness( $settings['font_colour'], 125 ); ?>;
        text-transform: uppercase;
        font-weight: normal;
        letter-spacing: 2mm;
        font-size: 200%;
    }

    #invoice-details .item {
        float: left;
        width: 48%;
        padding-right: 2%;

        line-height: 150%;
    }

    /*
     * Line Items Layout
     */
    #line-items {
        margin: 15mm 0 10mm;
    }

    #line-items .row {
        clear: both;
        height: 12mm;
    }

    #line-items .col {
        float: left;
        width: 18%;
        text-align: center;

        padding: 3mm 0;
    }

    #line-items .item {
        width: 40%;
        margin-left: 0;
    }

    #line-items .item {
        text-align: left;
        padding-left: 2%;
    }

    <?php if( $should_calculate_tax && ! $should_hide_tax_in_table ): ?>
    #line-items .col {
        width: 13.5%;
    }

    <?php endif; ?>

    /*
     * Line Item Header
     */
    #line-items .thead .col {
        color: <?php echo $secondary_contrast_highlight_colour; ?>;

        border-top: 5px solid #FFF;
        border-bottom: 5px solid #FFF;
        border-right: 5px solid #FFF;

        border-top-right-radius: 20px;
        border-bottom-right-radius: 20px;

        padding-left: 20px;
        margin-left: -20px;

        font-weight: bold;
    }

    #line-items .thead .item {
        color: <?php echo $primary_contrast_highlight_colour; ?>;
    }

    /*
     * Line Item Body
     */
    #line-items .tbody .first {
        margin-top: -5px;
    }

    #line-items .tbody .row,
    #line-items .tfoot .row {
        border-bottom: 1px solid <?php echo $secondary_highlight_colour; ?>;
    }

    #line-items .tbody .col {
        padding-left: 6px;
    }

    /*
     * Line Item Footer
     */
    #line-items .tfoot {
        margin-left: 55%;
    }

    #line-items .tfoot .col {
        width: 50%;
    }

    #line-items .tfoot .label {
        font-weight: bold;
    }

    #line-items .tfoot .last,
    #line-items .tfoot #grand-total {
        border-bottom: none;
    }

    #line-items .tfoot #grand-total {
        border-top-left-radius: 20px;
        border-bottom-left-radius: 20px;

        color: <?php echo $primary_contrast_highlight_colour; ?>;
        font-size: 150%;
        font-weight: bold;

        margin-top: 1mm;
    }

    #line-items .tfoot #grand-total .col {
        padding-top: 2mm;
    }

    /*
     * Additional Info
     */
    #additional-info {
        float: left;
        padding-left: 2%;
        width: 45%;
    }

    #additional-info p {
        margin: 0 0 4mm;
        padding: 0;
    }
    
    .footer {
       background-color: #1c1265; 
       border: none;
   margin-bottom: 0px;
    }
    
.footer .td {
  
}
</style>

<!-- Include a watermark if present -->
<?php if ( strlen( $watermark ) > 0 ): ?>
    <watermarktext
            content="<?php echo htmlspecialchars( $watermark, ENT_QUOTES ); ?>"
            alpha="0.1"/>
<?php endif; ?>

<!-- Footer -->
<htmlpagefooter name="footer">
    <div class="footer">
        <table autosize="1">
            <thead>
            <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td colspan="3" style="font-size: 10px !important; color: #ffffff; padding:10px; margin:10px; font-weight: 400;" >
					<?php echo $footer_info; ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</htmlpagefooter>

<?php
/*
 * @todo write docs
 */
do_action( 'gfpdf_pre_invoice_html', $args );
?>

<!-- Logo -->
<header class="secondary">
    <table autosize="1">
        <tr>
            <td width="60%">
				<?php echo ( strlen( $logo ) > 0 ) ? '<img src="' . $logo . '" style="max-height: 28mm;" />' : ''; ?>
            </td>
        </tr>
    </table>
</header>

<!-- Business Contact Details -->
<?php $icon = ( $secondary_contrast_highlight_colour === '#FFF' ) ? 'white' : 'black'; ?>
<div id="contact-details">
    <table>
        <tr>
            <td height="36mm" valign="center">
                <table autosize="1">

					<?php if ( count( $business_address ) > 0 ): ?>
                        <tr>
                            <td class="icon">
                                <img src="<?php echo __DIR__; ?>/install/gpdf-invoice-aurora/icons/location-<?php echo $icon; ?>.png"
                                     height="6mm"/>
                            </td>

                            <td class="contact-info">
								<?php echo implode( '<br>', $business_address ); ?>
                            </td>
                        </tr>
					<?php endif; ?>

					<?php if ( count( $business_contact_details ) > 0 ): ?>
                        <tr>
                            <td class="icon">
                                <img src="<?php echo __DIR__; ?>/install/gpdf-invoice-aurora/icons/mobile-<?php echo $icon; ?>.png"
                                     height="5.5mm"/>
                            </td>

                            <td class="contact-info">
								<?php echo implode( '<br>', $business_contact_details ); ?>
                            </td>
                        </tr>
					<?php endif; ?>

					<?php if ( count( $business_other_info ) > 0 ): ?>
                        <tr>
                            <td class="icon">
                                <img src="<?php echo __DIR__; ?>/install/gpdf-invoice-aurora/icons/Worldwide_Web-icon-<?php echo $icon; ?>.png"
                                     height="6.5mm"/>
                            </td>

                            <td class="contact-info">
								<?php echo implode( '<br>', $business_other_info ); ?>
                            </td>
                        </tr>
					<?php endif; ?>
                </table>
            </td>
        </tr>
    </table>

</div>

<div id="invoice-title" class="primary">
	<?php echo $title_label; ?>
</div>

<!-- Client Details -->
<?php if ( strlen( $buyer_name ) > 0 || count( array_filter( $buyer_info ) ) > 0 ): ?>
    <div id="client-details">
        <table autosize="1">
            <tr>
                <td id="client-details-wrapper">
                    <div id="client-details-title"><?php echo $buyer_details_label; ?></div>

					<?php if ( strlen( $buyer_name ) > 0 ): ?>
                        <div class="buyer-name"><?php echo $buyer_name; ?></div>
					<?php endif; ?>

					<?php echo implode( '<br>', array_filter( array_slice( $buyer_info, 0, 3 ) ) ); ?>

					<?php
					$buyer_info_extras = array_filter( array_slice( $buyer_info, 3 ) );
					if ( count( $buyer_info_extras ) > 0 ): ?>
                        <div>
                            —<br>
							<?php echo implode( '<br>', array_filter( array_slice( $buyer_info, 3 ) ) ); ?>
                        </div>
					<?php endif; ?>
                </td>
            </tr>
        </table>
    </div>
<?php endif; ?>

<!-- Invoice Details -->
<div id="invoice-details">
    <h2><?php echo $summary_label; ?></h2>

	<?php if ( strlen( $date_label ) > 0 ): ?>
        <div class="item">
            <span><?php echo $date_label; ?></span>
            <span><?php echo $invoice_date; ?></span>
        </div>
	<?php endif; ?>

	<?php if ( strlen( $invoice_due_date ) > 0 ): ?>
        <div class="item">
            <span><strong><?php echo $due_date_label; ?></strong></span>
            <span>
                <strong><?php echo $invoice_due_date; ?></strong>
            </span>
        </div>
	<?php endif; ?>

	<?php if ( strlen( $invoice_number_label ) > 0 ): ?>
        <div class="item">
            <span><?php echo $invoice_number_label; ?></span>
            <span><?php echo $invoice_no_formatted; ?></span>
        </div>
	<?php endif; ?>

	<?php if ( $should_calculate_tax ): ?>
		<?php if ( strlen( $tax_rate_label ) > 0 ): ?>
            <div class="item">
                <span><?php echo $tax_rate_label; ?></span>
                <span><?php echo $tax_rate; ?>%</span>
            </div>
		<?php endif; ?>

		<?php if ( strlen( $tax_number ) > 0 ): ?>
            <div class="item">
                <span><?php echo $tax_number_label; ?></span>
                <span><?php echo $tax_number; ?></span>
            </div>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( strlen( $currency_label ) > 0 ): ?>
        <div class="item">
            <span><?php echo $currency_label; ?></span>
            <span><?php echo $currency_type; ?></span>
        </div>
	<?php endif; ?>
</div>

<?php
/*
 * @todo write docs
 */
do_action( 'gfpdf_pre_invoice_table', $args );
?>

<!-- Invoice Table -->
<div id="line-items">
	<?php
	/* Hide the header if all the labels are empty */
	if ( strlen( $qty_col_label ) !== 0 ||
	     strlen( $desc_col_label ) !== 0 ||
	     strlen( $unit_price_col_label ) !== 0 ||
	     ( $should_calculate_tax && ! $should_hide_tax_in_table && strlen( $tax_name ) !== 0 ) ||
	     strlen( $total_col_label ) !== 0
	):
		?>
        <div class="thead">
            <div class="col primary item"><?php echo $desc_col_label; ?></div>
            <div class="col secondary unit-price"><?php echo $unit_price_col_label; ?></div>
            <div class="col secondary qty"><?php echo $qty_col_label; ?></div>

			<?php if ( $should_calculate_tax && ! $should_hide_tax_in_table ): ?>
                <div class="col secondary tax">
					<?php echo $tax_name; ?>
                </div>
			<?php endif; ?>

            <div class="col secondary total"><?php echo $total_col_label; ?></div>
        </div>
	<?php endif; ?>

    <div class="tbody">
		<?php
		reset( $form_data['products'] );
		$first = key( $form_data['products'] );
		foreach ( $form_data['products'] as $id => $prod ): ?>
            <div class="row <?php echo ( $prod === $first ) ? 'first' : ''; ?>">
                <div class="col item">
					<?php echo wp_kses_post( wp_specialchars_decode( $prod['name'], ENT_QUOTES ) ); ?>

					<?php if ( count( $prod['options'] ) > 0 ): ?>
                        <br>
                        <small>
							<?php foreach ( $prod['options'] as $op ): ?>
                                &nbsp; &bull; <?php echo wp_kses_post( wp_specialchars_decode( $op['option_label'], ENT_QUOTES ) ); ?>
                                <br>
							<?php endforeach; ?>
                        </small>
					<?php endif; ?>
                </div>

                <div class="col unit-price">
					<?php
					$unit_price = $prod['subtotal'] / $prod['quantity'];

					echo GFCommon::to_money(
						( $should_calculate_tax ) ? $unit_price / $tax_rate_calculation : $unit_price,
						$form_data['misc']['currency']
					); ?>
                </div>

                <div class="col qty"><?php echo $prod['quantity']; ?></div>

				<?php if ( $should_calculate_tax && ! $should_hide_tax_in_table ): ?>
                    <div class="col tax">
						<?php echo GFCommon::to_money(
							$unit_price - $unit_price / $tax_rate_calculation,
							$form_data['misc']['currency']
						); ?>
                    </div>
				<?php endif; ?>

                <div class="col total">
					<?php echo GFCommon::to_money( $prod['subtotal'], $form_data['misc']['currency'] ); ?>
                </div>
            </div>
		<?php endforeach; ?>

        <div class="row">
            &nbsp;
        </div>
    </div>

    <div class="tfoot" >

		<?php $footer_count = 1; ?>

		<?php if ( strlen( $subtotal_label ) > 0 ): ?>
            <div class="row">
                <div class="col label"><?php echo $subtotal_label; ?></div>
                <div class="col value"><?php echo ( $should_calculate_tax ) ? $subtotal_minus_tax_formatted : $subtotal_cost_formatted; ?></div>
            </div>
			<?php $footer_count++; ?>
		<?php endif; ?>

		<?php if ( $shipping_cost > 0 ): ?>
            <div class="row">
                <div class="col label"><?php echo $shipping_selection; ?></div>
                <div class="col value"><?php echo ( $should_calculate_tax && $should_tax_shipping ) ? $shipping_minus_tax_formatted : $shipping_cost_formatted; ?></div>
            </div>
			<?php $footer_count++; ?>
		<?php endif; ?>

	    <?php if ( $should_calculate_tax ): ?>
            <div class="row">
                <div class="col label"><strong><?php echo $tax_name; ?></strong></div>
                <div class="col value"><strong><?php echo $tax_formatted; ?></strong></div>
            </div>
			<?php $footer_count++; ?>
		<?php endif; ?>

        <div class="row primary" id="grand-total">
            <div class="col label"><strong><?php echo $total_label; ?></strong></div>
            <div class="col value"><strong><?php echo $total_cost_formatted; ?></strong></div>
        </div>

    </div>
</div>

<?php
/*
 * @todo write docs
 */
do_action( 'gfpdf_post_invoice_table', $args );
?>

<?php if ( strlen( $additional_info ) > 0 ): ?>
    <div id="additional-info" style="margin-top: -<?php echo $calculate_negative_margin( $footer_count ); ?>mm">
		<?php echo $additional_info; ?>
    </div>
<?php endif; ?>

<?php
/*
 * @todo write docs
 */
do_action( 'gfpdf_post_invoice_html', $args );
?>

