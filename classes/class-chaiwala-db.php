<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Chaiwala_DB {
	private static $key = '_chaiwala_data';

	public static function get_data_by_time( $month, $year ) {
		$data = self::get_data();
		$tea_data = array();
		$tea_total = array(
			'morning'	=> 0,
			'evening'	=> 0,
		);
		$coffee_total = array(
			'morning'	=> 0,
			'evening'	=> 0,
		);

		foreach ( $data as $timestamp => $tea ) {
			$_year = date('Y', $timestamp);
			$_month = date('m', $timestamp);

			if ( $_year != $year ) {
				continue;
			}
			if ( $_month != $month ) {
				continue;
			}

			$date = date( 'd-m-Y', $timestamp );
			
			$tea_data[ $date ]['tea'][ $tea['tea_shift'] ] = $tea['tea_count'];
			$tea_total[ $tea['tea_shift'] ] += intval($tea['tea_count']);

			$tea_data[ $date ]['coffee'][ $tea['tea_shift'] ] = isset( $tea['coffee_count'] ) ? $tea['coffee_count'] : 0;
			$coffee_total[ $tea['tea_shift'] ] += isset( $tea['coffee_count'] ) ? intval($tea['coffee_count']) : 0;
		}

		return array(
			'data' => $tea_data,
			'total'	=> array(
				'tea'	=> $tea_total,
				'coffee'	=> $coffee_total
			)
		);
	}

	public static function get_data() {
		$data = get_option( self::$key );

		if ( ! is_array( $data ) ) {
			$data = array();
		}

		return $data;
	}

	public static function set_data( $raw_data = array() ) {
		if ( ! is_array( $raw_data ) ) {
			return;
		}

		$data = self::get_data();

		if (  isset( $raw_data['date'] ) && ! empty( $raw_data['date'] ) ) {
			$timestamp = strtotime( $raw_data['date'] );
		} else {
			$timestamp = current_time( 'timestamp' );
		}

		$data[ $timestamp ] = $raw_data;
		
		return update_option( self::$key, $data );
	}
}