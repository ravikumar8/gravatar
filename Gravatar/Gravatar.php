<?php

/**
 * Gravatar Class
 * 
 * @package 	Gravatar
 * @author 		Ravi Kumar
 * @version 	0.1.0    
 * @copyright 	Copyright (c) 2014, Ravi Kumar
 * @license 	https://github.com/ravikumar8/Gravatar/blob/master/LICENSE MIT
 **/

namespace Gravatar;

use Gravatar\Exception,
	Gravatar\Exception\EmailRequiredException,
	Gravatar\Exception\InvalidEmailException,
	Gravatar\Exception\InvalidArgumentsException;

class Gravatar	{

	private $base_url			=	'http://www.gravatar.com/';
	private $base_url_secure	=	'https://secure.gravatar.com/';
	private $options			=	array();

	protected	$email_hash		=	null;

	/**
	 * Constructor
	 *
	 * @param string email
	 **/
	public function __construct( $email = null )	{

		if( is_null( $email ) )
			throw new EmailRequiredException();

		$email 	=	strtolower( trim( $email ) );

		if( ! filter_var( $email, FILTER_VALIDATE_EMAIL )	 )
			throw new InvalidEmailException();

		$this->email_hash	=	hash( 'md5', $email );	
	}

	/**
	 * url
	 *
	 * @param boolean true if secure else false
	 * @param string extension for image file
	 * @return string url of the gravatar
	 **/
	public function url( $secure = false, $extension = null )	{

		$result	=	'';
		if( $secure )	{
			$result =	$this->base_url_secure; 
		}	else 	{
			$result =	$this->base_url;
		}

		if( ! is_null( $extension ) && in_array( $extension, array( 'jpg', 'png' ) ) )
			$result	.= 'avatar/' . $this->email_hash . '.' . $extension;
		else
			$result	.= 'avatar/' . $this->email_hash;

		if( ! empty( $this->options ) )
			$result .=	'?' . http_build_query($this->options);

		return $result;
	}

	/**
	 * img
	 *
	 * @param array image attributes in key/value
	 * @param boolean true if secure else false
	 * @return string url of the gravatar with html img tag 
	 **/
	public function img( $atts = array(), $secure = false )	{

		$result	=	'<img src="' . $this->url( $secure ) . '"';

		if( is_array( $atts ) )	{
			foreach ( $atts as $key => $val )
				$result .= ' ' . $key . '="' . $val . '"';
		}
		$result .= ' />';

		return $result;
	}

	/**
	 * profile
	 *
	 * @param string format supported php, json, qr, vcf, and xml
	 * @return mixed
	 **/
	public function profile( $format = 'php' )	{

		$format 		=	strtolower( trim( $format ) );
		$default_format	=	array( 'json', 'xml', 'php', 'vcf', 'qr' );
		if( ! in_array( $format, $default_format ) )
			throw new InvalidFormatException();
			
		switch ( $format ) {
			case 'json':

				$ch 	=	curl_init();
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_URL, $this->base_url . $this->email_hash . '.' . $format);
				$data 	=	curl_exec($ch);
				curl_close($ch);

				return $data;
				break;

			case 'xml':

				return simplexml_load_string( file_get_contents( $this->base_url . $this->email_hash . '.' . $format ) );
				break;

			case 'php':

				return unserialize( file_get_contents( $this->base_url . $this->email_hash . '.' . $format ) );
				break;

			case 'vcf':
			
				return $this->base_url . $this->email_hash . '.' . $format;
				break;

			case 'qr':

				$result 	=	$this->base_url . $this->email_hash . '.' . $format;
				if( ! empty( $this->options ) )
					$result .=	'?' . http_build_query($this->options);
				return $result;
				break;

		}
		
	}

	/**
	 * __call
	 *
	 * @param string method name
	 * @param mixed parameters
	 **/
	public function __call( $method, $arguments )	{

		$default_methods	=	array( 'setSize', 'setRating', 'setDefault', 'setForceDefault' );
		$default_ratings	=	array( 'g', 'pg', 'r', 'x' );
		$default_images		=	array( '404', 'mm', 'identicon', 'monsterid', 'wavatar', 'retro', 'blank' );

		if( in_array( $method, $default_methods ) )	{

			switch( $method )	{

				case 'setSize':

				$this->options['s']	=	$arguments[0];
				break;

				case 'setRating':

				$this->options['r']	=	$arguments[0];
				break;

				case 'setForceDefault':

				$this->options['f']	=	$arguments[0];

				case 'setDefault':

				$this->options['d']	=	urlencode( $arguments[0] );
				break;
			}
		}

		return $this;
	}

	public function __toString() {
		return $this->base_url.'avatar/'.$this->email_hash ;
	}

}  // END class Gravatar