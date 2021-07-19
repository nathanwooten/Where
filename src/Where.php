<?php

//v2.0.9

function where( array $where, $bind = '?', bool $internalArray = null, array $internalParameters = [] )
{

	$internalSeparators = [ 'AND', 'OR', 'and', 'or', '&&', '||' ];
	$internalComparison = [ '=', '!=', '>', '<', '>=', '<=' ];
	$internalInner = empty( $internalArray ) ? false : true;

	foreach ( $where as $index => $item ) {

		if ( is_array( $item ) ) {

			$parsed = where( $item, $bind, true, $internalParameters );
			$item = $parsed[0];

			$internalPrevious = 0 > $index -1 ? -1 : $index -1;
			if ( $internalPrevious >= 0 ) {
				$internalPrevious = trim( $where[ $internalPrevious ] );

				if ( is_string( $internalPrevious ) && ! in_array( $internalPrevious, $internalSeparators ) ) {
					$item = ' and ' . $item;
				}

			}

			if ( $index == 0 && ( true === $internalArray || ! in_array( $item, $internalSeparators ) ) ) {
				$item = ' ( ' . $item;
			}

			if ( $index === count( $where ) -1 ) {
				$item .= ' ) ';
			}

			$where[ $index ] = $item;

			$internalArray = true;

		} else {

			if ( in_array ( $item, $internalSeparators ) ) {
				$where[ $index ] = ' ' . $item . ' ';

			} else {

				if ( $index == 0 ) {
					$where[ $index ] = $item;

				} elseif ( $index == 1 ) {
					$where[ $index ] = $item;

				} elseif ( $index == 2 ) {

					$name = $where[ 0 ];

					if ( '?' === $bind ) {
						$whereBind = '?';
						$key = count( $internalParameters );

					} else {
						$whereBind = ':' . $name;
						$key = $name;

					}

					$where[ $index ] = $whereBind;
					$internalParameters[ $key ] = $item;
				}
			}

			$internalArray = false;

		}
	}

	$expressions = implode( '', $where );

	if ( false === $internalInner ) {
		$expressions = trim( $expressions );
	}

	$parameters = $internalParameters;

	$returnValues = [ $expressions, $parameters ];
	return $returnValues;

}
