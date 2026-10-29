<?php
defined('ABSPATH' ) || wp_die;

// 投稿日時は UNIX 時間、それ以外はサイトのローカル時刻を数値化した値。
function pz_lkc_csv_datetime_columns() {
	return array('post_date', 'post_modified', 'alive_time', 'alive_nexttime', 'sns_time', 'sns_nexttime', 'regist_time', 'update_time' );
}

function pz_lkc_csv_datetime_timezone($column ) {
	return in_array($column, array('post_date', 'post_modified' ), true ) ? wp_timezone() : new DateTimeZone('UTC' );
}

function pz_lkc_csv_datetime_export($column, $value ) {
	if ($value === null || $value === '' || !is_numeric($value ) || intval($value ) === 0 ) {
		return $value;
	}
	return (new DateTimeImmutable('@'.intval($value ) ))->setTimezone(pz_lkc_csv_datetime_timezone($column ) )->format('Y-m-d H:i:s' );
}

function pz_lkc_csv_datetime_import($column, $value ) {
	$value = trim((string) $value );
	if ($value === '' ) return $column === 'regist_time' ? null : 0;
	if (ctype_digit($value ) ) return intval($value );
	$date = DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $value, pz_lkc_csv_datetime_timezone($column ) );
	$errors = DateTimeImmutable::getLastErrors();
	if (!$date || ($errors && ($errors['warning_count'] || $errors['error_count'] ) ) || $date->format('Y-m-d H:i:s' ) !== $value ) {
		return false;
	}
	return $date->getTimestamp();
}
