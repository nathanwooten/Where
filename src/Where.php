<?php

namespace profordable\framework\database;

use profordable\framework\database\pf_database_item as database_item;

class pf_model_expressions extends database_item {

	const SEPARATORS = [
		'AND',
		'OR'
	];

	public $bindings = [];

	public function __construct($container = null) {
		$this->init($container);
	}

	public function init($container = null) {
		$set = isset($container);
		$prepared = $set ? (($p = $container->get('prepared')) ? $p : true) : true;
		$bind_type = $set ? (($b = $container->get('bind_type')) ? $b : '?') : '?';
		$container = isset($container) ? $container : new p_container;
		$container->set('prepared', $prepared);
		$container->set('bind_type', $bind_type);
		$this->container_init($container);
	}

	public function build(array $parts = null) {
		if (!isset($parts) || empty($parts) || !$parts) {
			return false;
		}
		$prepared = isset($container) && ($prepared = $container->get('prepared')) ? $prepared : true;
		$bind_type = isset($cotainer) && ($bind_type = $container->get('bind_type')) ? $bind_type : '?';
		$inner = false;
		$backtrace = debug_backtrace();
		$inner = (isset($backtrace[1]) && $backtrace[1]['function'] == 'build' && $backtrace[1]['class'] == get_class($this)) ? true : false;
		$array = [];
		foreach ($parts as $index => $part) {
			if (is_array($part)) {
				$array[$index] = true;
				$part = $this->build($part);
				$previous = $index - 1;
				if (isset($parts[$previous]) && $array[$previous] == true) {
					$part = ' AND ' . $part;
				}
				if ($index == 0 && $inner) {
					$part = '(' . $part;
				}
				if ($index == count($parts) - 1 && $inner) {
					$part .= ')';
				}
				$parts[$index] = $part;
			} else {
				$array[$index] = false;
				if (in_array($part, self::SEPARATORS)) {
					$parts[$index] = ' ' . $part . ' ';
				} elseif ($index == 0) {
					$parts[$index] = '`' . trim($part, '`') . '`';
				} elseif ($index == 1) {
					$parts[$index] = ' ' . $part . ' ';
				} elseif ($index == 2) {
					if ($bind_type == '?') {
						if (empty($this->bindings)) {
							$this->bindings[1] = $part;
						}
					} else {
						$this->bindings[$parts[0]] = $part;
					}
					if ($prepared) {
						if (!isset($bind_type)) {
							$bind_type = '?';
						}
						$parts[$index] = ($bind_type === '?') ? '?' : ':' . trim($parts[0], '`');
					} else {
						if (!is_numeric($part)) {
							$parts[$index] = "'" . $part . "'";
						} else {
							$parts[$index] = $part;
						}
					}
				}
			}
		}
		$expressions = implode('', $parts);
		return $expressions;
	}

	public function get_bindings() {
		return $this->bindings;
	}

}

?>