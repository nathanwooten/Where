<?php

namespace nathanwooten\Where;

//v2.0.12

class Where
{

	const AND = [ 'AND', 'and', '&&' ];

	const OR = [ 'OR', 'or', '||' ];

	public function process( array $where, $bind = '?', bool $internalArray = null, array $internalParameters = [] )
	{

		$originalWhere = $where;

		foreach ( $where as $index => $item ) {

			if ( is_array( $item ) ) {

				$parsed = $this->process( $item, $bind, true, $internalParameters );

				$item = $parsed[ 0 ];
				$internalParameters = $parsed[ 1 ];

				$internalPrevious = 0 > $index -1 ? -1 : $index -1;
				if ( $internalPrevious >= 0 ) {
					$internalPrevious = trim( $where[ $internalPrevious ] );

					if ( is_string( $internalPrevious ) && ! in_array( $internalPrevious, self::AND ) && ! in_array( $internalPrevious, self::OR ) ) {
						$item = ' and ' . $item;
					}

				}

				if ( $index == 0 && ( true === $internalArray || ( ! in_array( $item, self::AND ) && ! in_array( $item, self::OR ) ) ) ) {
					$item = '( ' . $item;

				}

				if ( $index === count( $where ) -1 ) {
					$item .= ' )';
				}

				$where[ $index ] = $item;

				$internalArray = true;

			} else {

				if ( in_array ( $item, self::AND ) || in_array( $item, self::OR ) ) {
					$where[ $index ] = ' ' . $item . ' ';

				} else {

					if ( $index == 0 ) {
						$where[ $index ] = $item;

					} elseif ( $index == 1 ) {
						$where[ $index ] = $item;

					} elseif ( $index == 2 ) {

						$name = $where[ 0 ];

						$whereBind = '?';
						$key = count( $internalParameters );

/*
						} else {
							$id = '';
							if ( in_array( $name, $internalParameters ) ) {

								$last = substr( $name, strlen( $name ) -1, 1 );
								if ( is_numeric( $last ) ) {

									foreach ( str_split( $string ) as $char ) {

										$isNumeric = is_numeric( $char );
										$isDot = ( '.' === $char );

										if ( ! $isNumeric && ! $isDot ) {
											if ( ! empty( $id ) ) {
												throw new Exception( 'Letters in the version exception' );
											}
										} elseif ( $isNumeric || $isDot ) {
											$id .= $char;
										}
									}

									$previousVersion = $name . $id;
									$internalParameters[ $previousVersion ] = $internalParameters[ $name ];


									$key = $name . ++$id;

								} else {
									$previous = $name;
									$previousVersion = $name . '1';
									$internalParameters[ $previousVersion ] = $internalParameters[ $previous ];

									$version = '2';
									$whereBind = ':' . $name . $version;
									$key = $name . $version;

									unset( $internalParameters[ $name ] );
								}
							} else {

								$whereBind = ':' . $name . $id;
								$key = $name;
							}
						}

						$where[ $index ] = $whereBind;
						$internalParameters[ $key ] = $item;
					}
				}

				$internalArray = false;

			}
		}

		$expressions = implode( '', $where );
		$parameters = $internalParameters;
		$original = $originalWhere;

		$returnValues = [ $expressions, $parameters, $original ];
		return $returnValues;

	}
*/
}
