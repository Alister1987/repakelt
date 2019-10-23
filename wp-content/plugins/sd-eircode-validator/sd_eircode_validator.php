<?php
    /*
    Plugin Name: Eircode Validator
    Description: A plugin to validate the routing keys for Eircode
    Version: 1.0.0
    Author: Rob Gleeson
    Author URI: http://www.softwaredesign.ie
    */

    add_filter( 'wpcf7_validate_text', 'sd_eircode_validation', 20, 2 );

    function sd_eircode_validation( $result, $tag ) {
        $tag = new WPCF7_Shortcode( $tag );
        $name = $tag->name;

        if ($name == 'eircode' || $name == 'farm-eircode') {
            $value = isset( $_POST['eircode'] ) ? trim( $_POST['eircode'] ) : '';

            if ( isEircode( $value ) || empty($value)) {
                $result['valid'] = true;
            } else {
                $result->invalidate($tag, 'Eircode is not valid');
            }
        }

        return $result;
    }

    function isEircode( $value ) {
        $eircodes = array('A41', 'A42', 'A45', 'A63', 'A67', 'A75', 'A81', 'A82', 'A83', 'A84', 'A85', 'A86', 'A91', 'A92', 'A94', 'A96', 'A98', 'C15', 'D01', 'D02', 'D03', 'D04', 'D05', 'D06', 'D07', 'D08', 'D09', 'D10', 'D11', 'D12', 'D13', 'D14', 'D15', 'D16', 'D17', 'D18', 'D20', 'D22', 'D24', 'D6W', 'E21', 'E25', 'E32', 'E34', 'E41', 'E45', 'E53', 'E91', 'F12', 'F23', 'F26', 'F28', 'F31', 'F35', 'F42', 'F45', 'F52', 'F56', 'F91', 'F92', 'F93', 'F94', 'H12', 'H14', 'H16', 'H18', 'H23', 'H53', 'H54', 'H62', 'H65', 'H71', 'H91', 'K32', 'K34', 'K36', 'K45', 'K56', 'K67', 'K78', 'N37', 'N39', 'N41', 'N91', 'P12', 'P14', 'P17', 'P24', 'P25', 'P31', 'P32', 'P36', 'P43', 'P47', 'P51', 'P56', 'P61', 'P67', 'P72', 'P75', 'P81', 'P85', 'R14', 'R21', 'R32', 'R35', 'R42', 'R45', 'R51', 'R56', 'R93', 'R95', 'T12', 'T23', 'T34', 'T45', 'T56', 'V14', 'V15', 'V23', 'V31', 'V35', 'V42', 'V92', 'V93', 'V94', 'V95', 'W12', 'W23', 'W34', 'W91', 'X35', 'X42', 'X91', 'Y14', 'Y21', 'Y25', 'Y34', 'Y35');
        $regex = '/^[0-9AC-FHKNPRTV-Y]{4}$/';
        $eircode = str_replace(' ', '', $value);
        $routing_key = substr($eircode, 0, 3);
        $unique_identifier = substr($eircode, 3, 7);

        if (in_array($routing_key, $eircodes) && preg_match($regex, $unique_identifier)) {
            return true;
        } else {
            return false;
        }
    }