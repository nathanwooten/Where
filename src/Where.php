<?php

//v2.0.6

class Where
{

	public $input = null;
	public $output = [];

	public function __construct()
	{

		$input = func_get_args();
		if ( ! empty( $input ) ) {
			$this( $input );
		}

	}

	public function __invoke()
	{

		$output = $this->create( ...func_get_args() );
		return $ouput;

	}

	function create( array $where, $bind = '?', bool $internalArray = null, array $internalParameters = [] )
	{

		$separators = [ 'AND', 'OR', 'and', 'or', '&&', '||' ];

		if ( empty( $array ) ) {
			$original = $where;

			$inner = false;
		} else {
			$inner = true;
		}

		$internalInner = empty( $internalArray ) ? false : true;

		foreach ( $where as $index => $item ) {

			if ( is_array( $item ) ) {

				$parsed = $this->create( $item, $bind, $array, $internalParameters );
				$item = $parsed[0];

				$previous = 0 > $index -1 ? null : $index -1;
				if ( $previous || 0 === $previous ) {
					$previous = trim( $where[ $previous ] );

					if ( is_string( $previous ) && ! in_array( $previous, $separators ) ) {
						$item = ' and' . $item;
					}

				}

				if ( ! $inner ) {
				} else {

					if ( $index == 0 && ( true === $array || ! in_array( $item, $separators ) ) ) {
						$item = ' ( ' . $item;
					}

					if ( $index === count( $where ) -1 ) {
						$item .= ' ) ';
					}
				}

				$where[ $index ] = $item;

				$array = true;

			} else {

				$array = false;

				if ( in_array ( $item, $separators ) ) {
					$where[ $index ] = ' ' . $item . ' ';

				} elseif ( $index == 0 ) {
					$where[ $index ] = '`' . trim( $item, '`') . '`';

				} elseif ( $index == 1 ) {
					$where[$index] = ' ' . $item . ' ';

				} elseif ( $index == 2 ) {

					$name = trim( $where[ 0 ], '`' );

					if ( '?' === $bind ) {
						$whereBind = '?';
						$key = count( $parameters );

					} else {
/*
						if ( 'add' === $bind ) {
							if ( array_key_exists( $key, $parameters ) ) {
								if ( 
							}
						}
*//*
						$whereBind = $name;
						$key = $name;

					}

					$where[ $index ] = $whereBind;
					$parameters[ $key ] = $item;
				}
			}
		}

		$expressions = implode( '', $where );

		if ( false === $inner ) {
			$expressions = trim( $expressions );
		}

		$parameters = $internalParameters;

		$returnValues = [ $expressions, $parameters ];

		if ( false === $internaliInner ) {
			$this->result = $returnValues;
			$this->orginal = $original;
		}

		return $returnValues;

	}

	public function getInput()
	{

		return $this->input;

	}

	public function getOutput()
	{

		return $this->output;

	}

}
