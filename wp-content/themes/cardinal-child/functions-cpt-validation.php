

<?php

  add_filter( 'wpcf7_validate_text*', 'custom_bic_confirmation_validation_filter', 20, 2 );

  function custom_bic_confirmation_validation_filter( $result, $tag ) {
      $tag = new WPCF7_Shortcode( $tag );

      if ( 'your-bic-confirm' == $tag->name ) {
          $your_bic_confirm = isset( $_POST['your-bic-confirm'] ) ? trim( $_POST['your-bic-confirm'] ) : '';

          if(!preg_match("/^[a-z]{6}[0-9a-z]{2}([0-9a-z]{3})?\z/i", $your_bic_confirm))  {
             $result->invalidate( $tag, "This is not a vaild Irish BIC" );
          }
      }

      return $result;
  }

  add_filter( 'wpcf7_validate_text', 'custom_iban_confirmation_validation_filter', 20, 2 );

  function custom_iban_confirmation_validation_filter( $result, $tag ) {
      $tag = new WPCF7_Shortcode( $tag );

      if ( 'your-iban-confirm' == $tag->name ) {
          $your_iban_confirm = isset( $_POST['your-iban-confirm'] ) ? trim( $_POST['your-iban-confirm'] ) : '';

          if(strlen($your_iban_confirm) > 0 && (!preg_match("(^IE\d{2}[A-Z]{4}\d{14})", $your_iban_confirm) || strlen($your_iban_confirm) != 22))  {
              $result->invalidate( $tag, "This is not a vaild Irish IBAN. IBAN must be uppercase, start with 'IE', and contain 22 characters." );
          }
      }

      return $result;
  }

  add_filter( 'wpcf7_validate_text', 'custom_date_validation_filter', 20, 2 );

  function custom_date_validation_filter( $result, $tag ) {
      $tag = new WPCF7_Shortcode( $tag );

      if ( 'DateOfSignature' == $tag->name ) {
          $DateOfSignature = isset( $_POST['DateOfSignature'] ) ? trim( $_POST['DateOfSignature'] ) : '';

          if($DateOfSignature != '' && !preg_match("/^(\d{2})\/(\d{2})\/(\d{4})$/", $DateOfSignature))  {
              $result->invalidate( $tag, "Date must be in the format dd/mm/yyyy" );
          }
      }

      return $result;
  }

 ?>