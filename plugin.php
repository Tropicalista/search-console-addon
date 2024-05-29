<?php
/**
 * Plugin Name: Search Console JSON export addon
 * Plugin URI:  https://www.francescopepe.com/
 * Description: An addon for search console to add an REST endpoint to show JSON data.
 * Version:     0.1.0
 * Requires Plugins:  search-console
 * Author:      Tropicalista
 * Author URI:  https://www.francescopepe.com
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

namespace Search_Console_JSON;

defined( 'ABSPATH' ) || exit;

/**
 * Register route
 *
 * @return void
 */
function register_routes() {
	register_rest_route(
		'searchconsole/v1',
		'/json_data',
		array(
			'methods'             => \WP_REST_Server::CREATABLE,
			'callback'            => __NAMESPACE__ . '\get_data',
			'args'                => array(
				'site'      => array(
					'type'              => 'string',
					'required'          => true,
					'sanitize_callback' => 'sanitize_text_field',
				),
			),
			'permission_callback' => function ( $request ) {
				$headers     = $request->get_headers();
				$auth_header = $headers['authorization'][0];

				list($username, $password) = explode( ':', base64_decode( substr( $auth_header, 6 ) ) );

				$user = wp_authenticate( $username, $password );

				if ( is_wp_error( $user ) ) {
					return false;
				} else {
					return true;
				}
				return current_user_can( 'manage_options' );
			},
		)
	);
}

/**
 * Get data
 *
 * @param WP_REST_REQUEST $request The request.
 * @return mixed
 */
function get_data( $request ) {
	$site       = $request->get_param( 'site' );
	$api        = new \Search_Console\Api();
	$token      = $api->get_access_token();

	if ( ! $site || ! $token ) {
		return new \WP_REST_Response( array( 'message' => 'Something wrong..' ), 400 );
	}

	$url = "https://content-searchconsole.googleapis.com/webmasters/v3/sites/$site/searchAnalytics/query?fields=rows&alt=json";

	$data = array(
		'dimensions' => array( 'QUERY' ),
		'startDate'  => $start_date,
		'endDate'    => $end_date,
		'type'       => 'web',
	);

	$args = array(
		'method'  => 'POST',
		'headers' => array(
			'Accept'        => 'application/json',
			'Authorization' => 'Bearer ' . $token,
			'Content-Type'  => 'application/json',
		),
		'body'    => $request->get_body(),
	);

	$response = wp_remote_request( $url, $args );

	// If request was not successful, return error.
	if ( is_wp_error( $response ) ) {
		return new \WP_Error( $response->get_error_code(), $response->get_error_message() );
	}

	// Decode response body.
	$response = json_decode( $response['body'], true );

	return new \WP_REST_Response( $response, 200 );
}

add_action( 'rest_api_init', __NAMESPACE__ . '\register_routes' );
