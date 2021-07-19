<?php

//2.0.7

function where( array $where, $bind = '?', bool $internalArray = null, array $internalParameters = [] )
{

	$separators = [ 'AND', 'OR', 'and', 'or', '&&', '||' ];
	$internalInner = empty( $internalArray ) ? false : true;

	foreach ( $where as $index => $item ) {

		if ( is_array( $item ) ) {

			$parsed = where( $item, $bind, true, $internalParameters );
			$item = $parsed[0];

			$internalPrevious = 0 > $index -1 ? -1 : $index -1;
			if ( $internalPrevious >= 0 ) {
				$internalPrevious = trim( $where[ $internalPrevious ] );

				if ( is_string( $internalPrevious ) && ! in_array( $internalPrevious, $separators ) ) {
					$item = ' and ' . $item;
				}

			}

// 			if ( ! $internalInner ) {
//			} else {

				if ( $index == 0 && ( true === $internalArray || ! in_array( $item, $separators ) ) ) {
					$item = ' ( ' . $item;
				}

				if ( $index === count( $where ) -1 ) {
					$item .= ' ) ';
				}
//			}

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
					$key = count( $internalParameters );

				} else {
					$whereBind = $name;
					$key = $name;

				}

				$where[ $index ] = $whereBind;
				$parameters[ $key ] = $item;
			}
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
