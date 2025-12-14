<?php
namespace StructuraUI\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * License Manager Class
 * Handles communication with the authorization server.
 */
class LicenseManager {

    const API_ENDPOINT = 'https://api.orbitcore.id/v1/verify';
    const OPTION_KEY   = 'structura_license_key';
    const STATUS_KEY   = 'structura_license_status';

    /**
     * Activate License
     * @param string $purchase_code
     * @return array
     */
    public function activate_license( $purchase_code ) {
        // 1. Prepare Data
        $domain = get_site_url();
        $body   = [
            'purchase_code' => sanitize_text_field( $purchase_code ),
            'domain'        => $domain,
            'action'        => 'activate'
        ];

        // 2. Call Your Middleware Server (Not Envato Directly!)
        $response = wp_remote_post( self::API_ENDPOINT, [
            'body'    => $body,
            'timeout' => 15,
        ] );

        // 3. Handle Errors
        if ( is_wp_error( $response ) ) {
            return ['success' => false, 'message' => $response->get_error_message()];
        }

        $code = wp_remote_retrieve_response_code( $response );
        $data = json_decode( wp_remote_retrieve_body( $response ), true );

        // 4. Save Status if Valid
        if ( $code === 200 && isset( $data['status'] ) && $data['status'] === 'valid' ) {
            update_option( self::OPTION_KEY, $purchase_code );
            update_option( self::STATUS_KEY, 'valid' );
            return ['success' => true, 'message' => 'License activated!'];
        }

        return ['success' => false, 'message' => $data['message'] ?? 'Invalid license'];
    }

    /**
     * Check Logic (To lock features)
     */
    public static function is_active() {
        return get_option( self::STATUS_KEY ) === 'valid';
    }

    /**
     * Periodic Check (Cron Job)
     * To ensure the user didn't ask for a refund after activating.
     */
    public function check_license_validity() {
        $key = get_option( self::OPTION_KEY );
        if ( ! $key ) return;

        // Call API 'check' endpoint...
        // If invalid -> delete option STATUS_KEY
    }
}