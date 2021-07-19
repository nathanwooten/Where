<?php

//v2.0.3

class Where
{

	function parse( array $where, $bind, bool $array = null, array $parameters = [] )
	{

		$separators = [ 'AND', 'OR', 'and', 'or', '&&', '||' ];

		$inner = empty( $array ) ? false : true;

		foreach ( $where as $index => $item ) {

			if ( is_array( $item ) ) {

				if ( $index === 0 && $inner ) {
					$item = [ 'and', $item ];
				}

				$parsed = $this->parse( $item, $bind, $array, $parameters );
				$item = $parsed[0];

				if ( $index == 0 && $inner && true === $array ) {
					$item = ' ( ' . $item;
				}

				if ( $index === count( $where ) -1 && $inner ) {
					$item .= ' ) ';
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

					$name = trim( $where[ 0 ] );

					if ( '?' === $bind ) {
						$whereBind = '?';
						$key = count( $parameters );

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

		$returnValues = [ $expressions, $parameters ];
		return $returnValues;

	}

}
