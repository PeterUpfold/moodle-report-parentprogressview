<?php
/*
Parent Progress View, a module for Moodle to allow the viewing of documents and pupil data by authorised parents.
    Copyright (C) 2016-17 Test Valley School.

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License,
    or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/



/**
 * Represents a single API request to The Hub.
 *
 * @package report_parentprogressview
 * @author Test Valley School
 */


namespace report_parentprogressview\local;

// NB sync this with tvs-wp-rest-api-request and update @version below

/**
 * A wrapper class to make querying The Hub REST microservices from any PHP page nice and easy.
 * @version 0c351a
 */
class WP_REST_API_Request {

	/**
	 * The HTTP status code of the request.
	 */
	public $status = 000;

	/**
	 * How long in seconds to wait for the request to complete. Remember that
	 * since this is likely to be used in the generation of another web page,
	 * we shouldn't tolerate many seconds of delay!
	 */
	public $timeout = 3;

	/**
	 * How long in seconds to wait for the connection to establish. Remember that
	 * since this is likely to be used in the generation of another web page,
	 * we shouldn't tolerate many seconds of delay!
	 */
	public $connect_timeout = 3;


	/** 
	 * A handle to a curl request that we use throughout the lifetime of this object.
	 */
	protected $request = null;

	/**
	 * The base URI from which API endpoints are accessed. Can be set in the constructor.
	 * 
	 * e.g. https://myhub.mysite.com/wp-json
	 */
	protected $base = ''; 

	/**
	 * The namespace of the API endpoint being called.
	 */
	protected $namespace = '/wp/v2/';

	/**
	 * The route of the API endpoint being called.
	 */
	protected $route = '';

	/**
	 * The username to use for basic auth on this request, or null.
	 */
	protected $username = null;

	/**
	 * The password to use for basic auth on this request, or null.
	 */
	protected $password = null;

	/**
	 * The arguments that will be sent in the query string, in an associative
	 * array structure. Values should be raw in this array, as urlencoding
	 * takes place later.
	 */
	protected $query_string_arguments = array();

	/**
	 * How many meta_query triplets (key, value, compare) we have added to this
	 * request's query string arguments.
	 */
	protected $meta_query_count = 0;

	/**
	 * The raw URI which we will be passing to cURL to make the request.
	 */
	protected $uri = null;

	/**
	 * A user agent string we will send.
	 */
	private $user_agent = 'TVS WP_REST_API_Request/1.0';

	/**
	 * Whether to spit out full error output when JSON parsing fails.
	 */
	private $debug = false;

	/**
	 * Set up the object. Pass in the username and password if needed.
	 */
	public function __construct( $base = 'https://localhost/wp-json', $namespace = '/wp/v2/', $route = 'posts', $username = null, $password = null ) {
		if ( ! function_exists( 'curl_init' ) ) {
			throw new \Exception( 'cURL is not available. Cannot create the WP_REST_API_Request object' );
		}

		$this->request = curl_init();

		if ( ! $this->request ) {
			throw new \Exception( 'Failed to create a cURL handle' );
		}

		$this->username = $username;
		$this->password = $password;

		if ( $this->username != null && $this->password != null ) {
			curl_setopt( $this->request, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
			curl_setopt( $this->request, CURLOPT_USERPWD, $this->username . ':' . $this->password );
		}

		$this->base = $base;
		$this->namespace = $namespace;
		$this->route = $route;


	}
	
	/**
	 * Add an additional meta query to this request. $relation is only set once and takes
	 * effect over all meta queries.
	 */
	public function add_meta_query( $key, $value, $compare, $relation = 'AND' ) {
		
		if ( $relation != 'AND' && $relation != 'OR' ) {
			throw new \Exception( 'relation must be AND, or OR exactly' );
		}

		if ( ! isset( $key ) || ! isset( $value ) || ! isset( $compare ) ) {
			throw new \Exception( 'You must pass a valid key, value and compare to add_meta_query' );
		}

		// if the relation is not set yet, set it 
		if ( $this->meta_query_count < 1 ) {
			$this->query_string_arguments[ 'meta_query[relation]' ] = $relation;
		}
		
		$this->query_string_arguments[ "meta_query[{$this->meta_query_count}][key]" ] = $key;
		
		$this->query_string_arguments[ "meta_query[{$this->meta_query_count}][value]" ] = $value;

		$this->query_string_arguments[ "meta_query[{$this->meta_query_count}][compare]" ] = $compare;

		++$this->meta_query_count;
	}

	/**
	 * Pass a query string argument key and value pair, with the value unencoded, and have it added to the
	 * request.
	 */
	public function add_query_argument( $key, $value ) {
		$this->query_string_arguments[ $key ] = $value;
	}

	/**
	 * Take the base, namespace and route and add any arguments to build a URI that we will pass to cURL
	 * when it makes the request.
	 *
	 * @returns bool
	 */
	protected function prepare_uri() {
		
		if ( $this->uri != null ) {
			// don't run twice
			throw new \Exception( 'Cannot prepare the URI for the API call a second time.' );
		}

		$this->uri = $this->base . $this->namespace . $this->route;

		// add arguments
		if ( count( $this->query_string_arguments ) > 0 ) {

			$this->uri .= '?';

			foreach( $this->query_string_arguments as $key => $value ) {

				// we don't want to url encode the key name, as we need the square brackets to have their meaning
					//TODO do we need to sanitise or control content in some other way?
				$this->uri .= $key . '=';

				$this->uri .= urlencode( $value ) . '&';

			}

			// trim the final & off the end
			$this->uri = rtrim( $this->uri, '&' );

		}

		return true;

	}

	/**
	 * Make the request, and return a PHP object of the JSON results. We set the status of the request
	 * in the $status variable for the caller's benefit.
	 */
	public function request() {
		if ( ! $this->request ) {
			throw new \Exception( 'cURL was not ready at the time of making a request.' );
		}

		if ( ! is_int( $this->timeout ) ) {
			throw new \Exception( 'timeout was specified as an invalid value. It must be an integer.' );
		}
		
		if ( ! is_int( $this->connect_timeout ) ) {
			throw new \Exception( 'connect_timeout was specified as an invalid value. It must be an integer.' );
		}

		if ( ! $this->uri ) {
			$this->prepare_uri();
		}

		curl_setopt( $this->request, CURLOPT_USERAGENT, $this->user_agent );
		curl_setopt( $this->request, CURLOPT_SSL_VERIFYPEER, true ); // never disable verification of certificate -- fix the cert bundle instead
		curl_setopt( $this->request, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $this->request, CURLOPT_TIMEOUT, $this->timeout );
		curl_setopt( $this->request, CURLOPT_CONNECTTIMEOUT, $this->connect_timeout );

		curl_setopt( $this->request, CURLOPT_URL, $this->uri );
		curl_setopt( $this->request, CURLOPT_HEADER, false );

		$response = curl_exec( $this->request ); 
		
		$this->status = curl_getinfo( $this->request, CURLINFO_HTTP_CODE );

		$json = json_decode( $response );

		if ( $json === null ) {
			$this->status = 593; // magic special error code
			$result = new \stdClass();
			$result->_error = 'Failed to decode JSON';

			if ( $this->debug ) {
				$result->_output = $response;
			}

			return $result;
		}
		else {
			return $json;
		}
	}

}

