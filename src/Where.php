<?php

//v2.0.4

class Where
{

//$where = [ [ 'title', '=', 'MyPage' ], [ [ 'content', '=', 'MyPage' ], 'or', [ 'content', '=', 'YourPage' ] ] ];
// first iteration: array [ 'title', '=', 'MyPage' ]
// 2: string title
// 3: string =
// 4: string MyPage
// 5: array [ [ 'content', '=', 'MyPage' ], 'or', [ 'content', '=', 'YourPage' ] ] ]
// 6: array [ 'content', '=', 'MyPage' ]
// 7: string content
// 8: string =
// 9: string MyPage
//10: string or
//11: array [ 'content', '=', 'YourPage' ]
//12: string content
//13: string =
//14: string YourPage

	function parse( array $where, $bind, bool $array = null, array $parameters = [] )
	{

		$separators = [ 'AND', 'OR', 'and', 'or', '&&', '||' ];

		$inner = empty( $array ) ? false : true;

		foreach ( $where as $index => $item ) {

			if ( is_array( $item ) ) {

				$parsed = $this->parse( $item, $bind, $array, $parameters );
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

					if ( $index == 0 && true === $array ) {
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
