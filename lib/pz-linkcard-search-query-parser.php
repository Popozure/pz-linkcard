<?php
defined('ABSPATH' ) || exit;

final class pz_LinkCard_Search_Query_Parser {

	private	$wpdb;

	private	$keys = array(
		'id'				=>	array('column' => 'id',				'type' => 'number' ),
		'title'				=>	array('column' => 'title',			'type' => 'text' ),
		'excerpt'			=>	array('column' => 'excerpt',		'type' => 'text' ),
		'charset'			=>	array('column' => 'charset',		'type' => 'text' ),
		'regist'			=>	array('column' => 'regist_time',	'type' => 'date' ),
		'update'			=>	array('column' => 'update_time',	'type' => 'date' ),
		'result'			=>	array('column' => 'update_result',	'type' => 'number' ),
		'regist_result'		=>	array('column' => 'regist_result',	'type' => 'number' ),
		'update_result'		=>	array('column' => 'update_result',	'type' => 'number' ),
		'alive_result'		=>	array('column' => 'alive_result',	'type' => 'number' ),
		'url'				=>	array('column' => 'url',			'type' => 'text' ),
		'domain'			=>	array('column' => 'domain',			'type' => 'text' ),
		'site'				=>	array('column' => 'domain',			'type' => 'text' ),
		'sitename'			=>	array('column' => 'sitename',		'type' => 'text' ),
		'click'				=>	array('column' => 'click_count',	'type' => 'number' ),
		'post'				=>	array('column' => array('use_post_id1', 'use_post_id2', 'use_post_id3', 'use_post_id4', 'use_post_id5', 'use_post_id6' ), 'type' => 'number' ),
	);

	private	$default_columns = array(
		'title',
		'excerpt',
		'url',
	);

	public	function	__construct($wpdb ) {
		$this->wpdb	=	$wpdb;
	}

	public	function	parse($query ) {
		$tokens					=	$this->tokenize((string) $query );
		$default_positive_sql	=	array();
		$default_positive_params	=	array();
		$number_or_groups		=	array();
		$and_sql				=	array();
		$and_params				=	array();

		foreach	($tokens as $token ) {
			$parsed	=	$this->parse_token($token );
			if	(null === $parsed ) {
				continue;
			}

			if	('default' === $parsed['kind'] && false === $parsed['negative'] ) {
				$default_positive_sql[]		=	$parsed['sql'];
				$default_positive_params		=	array_merge($default_positive_params, $parsed['params'] );
				continue;
			}

			if	('keyed' === $parsed['kind'] && false === $parsed['negative'] && isset($parsed['type'], $parsed['key'] ) && 'number' === $parsed['type'] ) {
				if	(!isset($number_or_groups[$parsed['key']] ) ) {
					$number_or_groups[$parsed['key']]	=	array('sql' => array(), 'params' => array() );
				}
				$number_or_groups[$parsed['key']]['sql'][]		=	$parsed['sql'];
				$number_or_groups[$parsed['key']]['params']		=	array_merge($number_or_groups[$parsed['key']]['params'], $parsed['params'] );
				continue;
			}

			$and_sql[]		=	$parsed['sql'];
			$and_params		=	array_merge($and_params, $parsed['params'] );
		}

		$where_parts	=	array();
		$params			=	array();

		if	(!empty($default_positive_sql ) ) {
			$where_parts[]	=	'( '.implode(' OR ', $default_positive_sql ).' )';
			$params			=	array_merge($params, $default_positive_params );
		}

		foreach	($number_or_groups as $group ) {
			$where_parts[]	=	(1 === count($group['sql'] ) ) ? $group['sql'][0] : '( '.implode(' OR ', $group['sql'] ).' )';
			$params			=	array_merge($params, $group['params'] );
		}

		if	(!empty($and_sql ) ) {
			$where_parts	=	array_merge($where_parts, $and_sql );
			$params			=	array_merge($params, $and_params );
		}

		return	array(
			'where'		=>	implode(' AND ', $where_parts ),
			'params'	=>	$params,
			'tokens'	=>	$tokens,
		);
	}

	private	function	tokenize($query ) {
		$tokens			=	array();
		$buffer			=	'';
		$in_quote		=	false;
		$escaped		=	false;
		$group_depth	=	0;
		$length			=	strlen($query );

		for	($i = 0; $i < $length; $i++ ) {
			$char	=	$query[$i];

			if	($escaped ) {
				$buffer		.=	$char;
				$escaped	=	false;
				continue;
			}

			if	($in_quote && '\\' === $char ) {
				$escaped	=	true;
				continue;
			}

			if	('"' === $char ) {
				$in_quote	=	!$in_quote;
				continue;
			}

			if	(!$in_quote && '(' === $char ) {
				$group_depth++;
				$buffer	.=	$char;
				continue;
			}

			if	(!$in_quote && ')' === $char && $group_depth > 0 ) {
				$group_depth--;
				$buffer	.=	$char;
				continue;
			}

			if	(!$in_quote && 0 === $group_depth && preg_match('/\s/u', $char ) ) {
				if	('' !== $buffer ) {
					$tokens[]	=	$buffer;
					$buffer		=	'';
				}
				continue;
			}

			$buffer	.=	$char;
		}

		if	($escaped ) {
			$buffer	.=	'\\';
		}
		if	('' !== $buffer ) {
			$tokens[]	=	$buffer;
		}

		return	$tokens;
	}

	private	function	parse_token($token ) {
		$negative	=	false;
		if	(isset($token[0] ) && '-' === $token[0] ) {
			$negative	=	true;
			$token		=	substr($token, 1 );
		}
		if	('' === $token ) {
			return	null;
		}

		$or_group	=	$this->build_or_group_condition($token, $negative );
		if	(null !== $or_group ) {
			return	$or_group;
		}

		$column_compare	=	$this->build_column_compare_condition($token, $negative );
		if	(null !== $column_compare ) {
			return	$column_compare;
		}

		$separator	=	$this->find_key_separator($token );
		if	(null === $separator ) {
			if	($this->looks_like_invalid_key_expression($token ) ) {
				return	null;
			}
			return	$this->build_default_condition($token, $negative );
		}

		$key		=	strtolower(substr($token, 0, $separator['position'] ) );
		$value		=	substr($token, $separator['position'] + 1 );
		$operator	=	$separator['operator'];
		if	('' === $key || '' === $value || !isset($this->keys[$key] ) ) {
			return	null;
		}

		$definition	=	$this->keys[$key];
		$columns	=	is_array($definition['column'] ) ? $definition['column'] : array($definition['column'] );
		$condition	=	$this->build_multi_column_condition($columns, $definition['type'], $value, $negative, $operator );
		if	(null === $condition ) {
			return	null;
		}

		$condition['key']	=	$key;
		$condition['type']	=	$definition['type'];

		return	$condition;
	}

	private	function	build_multi_column_condition($columns, $type, $value, $negative, $operator = ':' ) {
		$sql	=	array();
		$params	=	array();

		foreach	($columns as $column ) {
			switch	($type ) {
			case	'text':
				$condition	=	$this->build_text_condition($column, $value, false, $operator );
				break;
			case	'date':
				$condition	=	$this->build_date_condition($column, $value, false );
				break;
			case	'number':
				$condition	=	$this->build_number_condition($column, $value, false );
				break;
			default:
				return	null;
			}

			if	(null === $condition ) {
				return	null;
			}
			$sql[]	=	$condition['sql'];
			$params	=	array_merge($params, $condition['params'] );
		}

		if	(empty($sql ) ) {
			return	null;
		}

		$condition_sql	=	'( '.implode(' OR ', $sql ).' )';
		if	($negative ) {
			$condition_sql	=	'NOT '.$condition_sql;
		}

		return	array(
			'kind'		=>	'keyed',
			'negative'	=>	$negative,
			'sql'		=>	$condition_sql,
			'params'	=>	$params,
		);
	}

	private	function	build_or_group_condition($token, $negative ) {
		if	(strlen($token ) < 3 || '(' !== $token[0] || ')' !== substr($token, -1 ) ) {
			return	null;
		}

		$sql	=	array();
		$params	=	array();
		foreach	($this->tokenize(substr($token, 1, -1 ) ) as $part ) {
			$parsed	=	$this->parse_token($part );
			if	(null === $parsed ) {
				continue;
			}
			$sql[]	=	$parsed['sql'];
			$params	=	array_merge($params, $parsed['params'] );
		}

		if	(empty($sql ) ) {
			return	null;
		}

		$condition	=	'( '.implode(' OR ', $sql ).' )';
		if	($negative ) {
			$condition	=	'NOT '.$condition;
		}

		return	array(
			'kind'		=>	'keyed',
			'negative'	=>	$negative,
			'sql'		=>	$condition,
			'params'	=>	$params,
		);
	}

	private	function	build_column_compare_condition($token, $negative ) {
		if	(!preg_match('/^([a-z_][a-z0-9_]*)(<>|!=|<=|>=|=|<|>)([a-z_][a-z0-9_]*)$/i', $token, $matches ) ) {
			return	null;
		}

		$left		=	strtolower($matches[1] );
		$operator	=	('!=' === $matches[2] ) ? '<>' : $matches[2];
		$right		=	strtolower($matches[3] );
		if	(!isset($this->keys[$left], $this->keys[$right] ) || is_array($this->keys[$left]['column'] ) || is_array($this->keys[$right]['column'] ) ) {
			return	null;
		}

		$sql	=	$this->keys[$left]['column'].' '.$operator.' '.$this->keys[$right]['column'];
		if	($negative ) {
			$sql	=	'NOT ( '.$sql.' )';
		}

		return	array(
			'kind'		=>	'keyed',
			'negative'	=>	$negative,
			'sql'		=>	$sql,
			'params'	=>	array(),
			'key'		=>	$left,
			'type'		=>	'column_compare',
		);
	}

	private	function	build_default_condition($value, $negative ) {
		return	$this->build_multi_column_condition($this->default_columns, 'text', $value, $negative );
	}

	private	function	build_text_condition($column, $value, $negative, $operator = ':' ) {
		if	('' === $value ) {
			return	null;
		}

		if	('=' === $operator ) {
			$sql	=	$column.($negative ? ' <> %s' : ' = %s');
			$params	=	array($value );
		} else {
			$sql	=	$column.($negative ? ' NOT LIKE %s' : ' LIKE %s');
			$params	=	array('%'.$this->wpdb->esc_like($value ).'%' );
		}

		return	array('kind' => 'keyed', 'negative' => $negative, 'sql' => $sql, 'params' => $params );
	}

	private	function	build_date_condition($column, $value, $negative ) {
		if	(false !== strpos($value, '..' ) ) {
			if	(1 !== substr_count($value, '..' ) ) {
				return	null;
			}
			list($from_text, $to_text )	=	explode('..', $value, 2 );
			$from	=	$this->parse_date($from_text, 'start' );
			$to		=	$this->parse_date($to_text, 'end' );
			if	(null === $from || null === $to || $from > $to ) {
				return	null;
			}
			$sql	=	$column.' BETWEEN %d AND %d';
			if	($negative ) {
				$sql	=	'NOT ( '.$sql.' )';
			}
			return	array('kind' => 'keyed', 'negative' => $negative, 'sql' => $sql, 'params' => array($from, $to ) );
		}

		if	(!preg_match('/^(<=|>=|=|<|>)?(.*)$/s', $value, $matches ) ) {
			return	null;
		}

		$operator	=	isset($matches[1] ) && '' !== $matches[1] ? $matches[1] : '=';
		$date		=	$this->parse_date($matches[2], 'start' );
		if	(null === $date ) {
			return	null;
		}

		switch	($operator ) {
		case	'=':
			$start	=	$this->parse_date($matches[2], 'start' );
			$end	=	$this->parse_date($matches[2], 'end' );
			$sql	=	$column.' BETWEEN %d AND %d';
			$params	=	array($start, $end );
			break;
		case	'>':
			$sql	=	$column.' > %d';
			$params	=	array($this->parse_date($matches[2], 'end' ) );
			break;
		case	'>=':
			$sql	=	$column.' >= %d';
			$params	=	array($date );
			break;
		case	'<':
			$sql	=	$column.' < %d';
			$params	=	array($date );
			break;
		case	'<=':
			$sql	=	$column.' <= %d';
			$params	=	array($this->parse_date($matches[2], 'end' ) );
			break;
		default:
			return	null;
		}

		if	($negative ) {
			$sql	=	'NOT ( '.$sql.' )';
		}

		return	array('kind' => 'keyed', 'negative' => $negative, 'sql' => $sql, 'params' => $params );
	}

	private	function	build_number_condition($column, $value, $negative ) {
		if	(false !== strpos($value, '..' ) ) {
			if	(1 !== substr_count($value, '..' ) ) {
				return	null;
			}
			list($from_text, $to_text )	=	explode('..', $value, 2 );
			if	(!$this->is_unsigned_integer($from_text ) || !$this->is_unsigned_integer($to_text ) ) {
				return	null;
			}
			$from	=	(int) $from_text;
			$to		=	(int) $to_text;
			if	($from > $to ) {
				return	null;
			}
			$sql	=	$column.' BETWEEN %d AND %d';
			if	($negative ) {
				$sql	=	'NOT ( '.$sql.' )';
			}
			return	array('kind' => 'keyed', 'negative' => $negative, 'sql' => $sql, 'params' => array($from, $to ) );
		}

		if	(!preg_match('/^(<=|>=|=|<|>)?([0-9]+)$/', $value, $matches ) ) {
			return	null;
		}
		$operator	=	isset($matches[1] ) && '' !== $matches[1] ? $matches[1] : '=';
		$sql		=	$column.' '.$operator.' %d';
		if	($negative ) {
			$sql	=	'NOT ( '.$sql.' )';
		}

		return	array('kind' => 'keyed', 'negative' => $negative, 'sql' => $sql, 'params' => array((int) $matches[2] ) );
	}

	private	function	find_key_separator($token ) {
		$colon_position		=	strpos($token, ':' );
		$equals_position	=	strpos($token, '=' );
		if	(false === $colon_position && false === $equals_position ) {
			return	null;
		}
		if	(false === $equals_position || (false !== $colon_position && $colon_position < $equals_position ) ) {
			return	array('position' => $colon_position, 'operator' => ':' );
		}
		return	array('position' => $equals_position, 'operator' => '=' );
	}

	private	function	looks_like_invalid_key_expression($token ) {
		foreach	($this->keys as $key => $definition ) {
			if	(preg_match('/^'.preg_quote($key, '/' ).'\s*(?:<=|>=|=|<|>)/i', $token ) ) {
				return	true;
			}
		}
		return	false;
	}

	private	function	parse_date($value, $edge ) {
		if	(!preg_match('/^([0-9]{4})([-\/])([0-9]{1,2})\2([0-9]{1,2})$/', $value, $matches ) ) {
			return	null;
		}
		$year	=	(int) $matches[1];
		$month	=	(int) $matches[3];
		$day	=	(int) $matches[4];
		if	(!checkdate($month, $day, $year ) ) {
			return	null;
		}
		$time	=	('end' === $edge ) ? '23:59:59' : '00:00:00';
		return	strtotime(sprintf('%04d-%02d-%02d %s', $year, $month, $day, $time ) );
	}

	private	function	is_unsigned_integer($value ) {
		return	1 === preg_match('/^[0-9]+$/', $value );
	}
}
