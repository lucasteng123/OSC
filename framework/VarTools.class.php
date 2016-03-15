<?php
class VarTools {
	public static function unsetAndUse(&$var) {
		$value = $var;
		unset($var);
		return $value;
	}
	public static function key_exists_equals($key, $search, $value) {
		if (!array_key_exists($key, $search)) return false;
		if ($search[$key] !== $value) return false;
		return true;
	}
	public static function key_exists_meets($key, $search, $condition) {
		if (!array_key_exists($key, $search)) return false;
		if (!$condition($search[$key])) return false;
		return true;
	}
	public static function key_exists_equivalent($key, $search, $value) {
		if (!array_key_exists($key, $search)) return false;
		if ($search[$key] != $value) return false;
		return true;
	}
	public static function def_and_equal($name, $value) {
		if (defined($name)) {
			if ($name === $value) return true;
		}
		return false;
	}
	public static function set_and_equal($name, $value) {
		if (isset($name)) {
			if ($name == $value) {
				return true;
			}
			return false;
		}
	}
	public static function def_and_equivalent($name, $value) {
		if (defined($name)) {
			if ($name == $value) return true;
		}
		return false;
	}
	public static function what_is($var) {
		$is = gettype($var);
		if ($is === "object") {
			$is = get_class($var);
		}
		return $is;
	}

	public static function predump($var) {
		echo "<pre>\n";
		echo var_dump($var)."\n</pre>\n";
	}

	public static function normalize_keys($oArray,$keyKey) {
		$nArray = array();
		foreach($oArray as $key => $value) {
			if (is_array($value)) {
				$value[$keyKey] = $key;
			}
			$nArray[] = $value;
		}
		return $nArray;
	}
}