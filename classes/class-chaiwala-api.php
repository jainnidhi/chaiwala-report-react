<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Chaiwala_API {
	public static $instance;

	public $namespace;

	public function __construct() {
		$this->namespace = 'chaiwala/v2';

		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		register_rest_route( $this->namespace, '/get/', array(
			'methods'	=> 'GET',
			'callback'	=> array( $this, 'get_data_by_time' ),
			'args'		=> array(
				'month'		=> array(
					'validate_callback'	=> function( $param, $request, $key ) {
						return is_numeric( $param );
					}
				),
				'year'		=> array(
					'validate_callback'	=> function( $param, $request, $key ) {
						return is_numeric( $param );
					}
				)
			)
		) );

		register_rest_route( $this->namespace, '/set/', array(
			'methods'	=> 'GET',
			'callback'	=> array( $this, 'set' )
		) );
	}

	public function get_data_by_time( $request ) {
		$month = $request['month'];
		$year = $request['year'];

		$data = Chaiwala_DB::get_data_by_time( $month, $year );

		return $data;
	}

	public function set( $request ) {
		$response = array(
			'data'	=> '',
			'error'	=> false,
		);

		if ( ! isset( $request['tea_count'] ) || ! isset( $request['coffee_count'] ) ) {
			$response['error'] = __('Error: Count is not provided.', 'chaiwala');
		} else if ( ! isset( $request['tea_shift'] ) ) {
			$response['error'] = __('Error: Shift is not provided.', 'chaiwala');
		}

		if ( ! $response['error'] ) {
			$response['data'] = array(
				'date'		=> $request['date'],
				'tea_count' => $request['tea_count'],
				'tea_shift' => $request['tea_shift'],
				'coffee_count'	=> $request['coffee_count'],
			);

			if ( ! Chaiwala_DB::set_data( $response['data'] ) ) {
				$response['error'] = __('Data cannot be updated at this time, please try again.', 'chaiwala');
			}
		}

		return $response;
	}

	public static function get_instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Chaiwala_API ) ) {
			self::$instance = new Chaiwala_API();
		}

		return self::$instance;
	}
}

$chaiwala_api = Chaiwala_API::get_instance();