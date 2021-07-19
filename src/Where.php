<?php

//v.2.0.1

class Where
{

	function parse( array $where, $bind, bool $array = null, array $parameters = [] )
	{

		$inner = empty( $array ) ? false : true;

		foreach ( $where as $index => $item ) {

			if ( is_array( $item ) ) {

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
