<?php

//v2.0.0

class Where
{

	function parse( array $where, $bind, array $array = [], array $parameters = [] )
	{

		$inner = empty( $array ) ? false : true;

		$array = [];
		foreach ( $where as $index => $item ) {

			if ( is_array( $item ) ) {

				$array[ $index ] = true;

				$parsed = $this->parse( $item, $bind, $array, $parameters );
				$item = $parsed[0];

				$previous = $index - 1;
				if ( $previous > -1 ) {
					if ( isset( $where[ $previous ] ) /*&& true === $array[ $previous ] *//* ) {
						$item = ' and ' . $item;
					}
				}

				if ( $index == 0 && $inner ) {
					$item = ' ( ' . $item;
				}

				if ( $index === count( $where ) -1 && $inner ) {
					$item .= ' ) ';
				}

				$where[ $index ] = $item;

			} else {

				$array[ $index ] = false;

				if ( in_array ( $item, [ 'AND', 'OR', 'and', 'or', '&&', '||' ] ) ) {
					$where[ $index ] = ' ' . $item . ' ';

				} elseif ( $index == 0 ) {
					$where[ $index ] = '`' . trim( $item, '`') . '`';

				} elseif ( $index == 1 ) {
					$where[$index] = ' ' . $item . ' ';

				} elseif ( $index == 2 ) {

					$where[ $index ] = '?';
					$parameters[] = $item;

				}
			}
		}

		$expressions = implode( '', $where );

		$returnValues = [ $expressions, $parameters ];
		return $returnValues;

	}

}
