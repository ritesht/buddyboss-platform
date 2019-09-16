<?php
/**
 * LearnDash integration group sync helpers
 *
 * @package BuddyBoss\LearnDash
 * @since BuddyBoss 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Returns LearnDash path.
 *
 * @since BuddyBoss 1.0.0
 */
function bp_learndash_path($path = '') {
    return trailingslashit( buddypress()->integrations['learndash']->path ) . trim($path, '/\\');
}

/**
 * Returns LearnDash url.
 *
 * @since BuddyBoss 1.0.0
 */
function bp_learndash_url($path = '') {
    return trailingslashit( buddypress()->integrations['learndash']->url ) . trim($path, '/\\');
}

/**
 * Return specified BuddyBoss LearnDash sync component.
 *
 * @since BuddyBoss 1.0.0
 */
function bp_ld_sync($component = null) {
	global $bp_ld_sync;
	return $component ? $bp_ld_sync->$component : $bp_ld_sync;
}

/**
 * Return array of LearnDash group courses.
 *
 * @since BuddyBoss 1.0.0
 */
function bp_learndash_get_group_courses($bpGroupId) {
	$generator = bp_ld_sync('buddypress')->sync->generator($bpGroupId);

	if (! $generator->hasLdGroup()) {
		return [];
	}

	return learndash_group_enrolled_courses($generator->getLdGroupId());
}

// forward compatibility
if (! function_exists('learndash_get_post_type_slug')) {
	/**
	 * Returns array of slugs used by LearnDash integration.
	 *
	 * @since BuddyBoss 1.0.0
	 */
	function learndash_get_post_type_slug($type) {
		$postTypes = [
			'course'       => 'sfwd-courses',
			'lesson'       => 'sfwd-lessons',
			'topic'        => 'sfwd-topic',
			'quiz'         => 'sfwd-quiz',
			'question'     => 'sfwd-question',
			'transactions' => 'sfwd-transactions',
			'group'        => 'groups',
			'assignment'   => 'sfwd-assignment',
			'essays'       => 'sfwd-essays',
			'certificates' => 'sfwd-certificates',
		];

		return $postTypes[$type];
	}
}

function learndash_integration_prepare_price_str( $price ) {
	if ( ! empty( $price ) ) {
		$currency_symbols = array(
			'AED' => '&#1583;.&#1573;', // ?
			'AFN' => '&#65;&#102;',
			'ALL' => '&#76;&#101;&#107;',
			'AMD' => '',
			'ANG' => '&#402;',
			'AOA' => '&#75;&#122;', // ?
			'ARS' => '&#36;',
			'AUD' => '&#36;',
			'AWG' => '&#402;',
			'AZN' => '&#1084;&#1072;&#1085;',
			'BAM' => '&#75;&#77;',
			'BBD' => '&#36;',
			'BDT' => '&#2547;', // ?
			'BGN' => '&#1083;&#1074;',
			'BHD' => '.&#1583;.&#1576;', // ?
			'BIF' => '&#70;&#66;&#117;', // ?
			'BMD' => '&#36;',
			'BND' => '&#36;',
			'BOB' => '&#36;&#98;',
			'BRL' => '&#82;&#36;',
			'BSD' => '&#36;',
			'BTN' => '&#78;&#117;&#46;', // ?
			'BWP' => '&#80;',
			'BYR' => '&#112;&#46;',
			'BZD' => '&#66;&#90;&#36;',
			'CAD' => '&#36;',
			'CDF' => '&#70;&#67;',
			'CHF' => '&#67;&#72;&#70;',
			'CLF' => '', // ?
			'CLP' => '&#36;',
			'CNY' => '&#165;',
			'COP' => '&#36;',
			'CRC' => '&#8353;',
			'CUP' => '&#8396;',
			'CVE' => '&#36;', // ?
			'CZK' => '&#75;&#269;',
			'DJF' => '&#70;&#100;&#106;', // ?
			'DKK' => '&#107;&#114;',
			'DOP' => '&#82;&#68;&#36;',
			'DZD' => '&#1583;&#1580;', // ?
			'EGP' => '&#163;',
			'ETB' => '&#66;&#114;',
			'EUR' => '&#8364;',
			'FJD' => '&#36;',
			'FKP' => '&#163;',
			'GBP' => '&#163;',
			'GEL' => '&#4314;', // ?
			'GHS' => '&#162;',
			'GIP' => '&#163;',
			'GMD' => '&#68;', // ?
			'GNF' => '&#70;&#71;', // ?
			'GTQ' => '&#81;',
			'GYD' => '&#36;',
			'HKD' => '&#36;',
			'HNL' => '&#76;',
			'HRK' => '&#107;&#110;',
			'HTG' => '&#71;', // ?
			'HUF' => '&#70;&#116;',
			'IDR' => '&#82;&#112;',
			'ILS' => '&#8362;',
			'INR' => '&#8377;',
			'IQD' => '&#1593;.&#1583;', // ?
			'IRR' => '&#65020;',
			'ISK' => '&#107;&#114;',
			'JEP' => '&#163;',
			'JMD' => '&#74;&#36;',
			'JOD' => '&#74;&#68;', // ?
			'JPY' => '&#165;',
			'KES' => '&#75;&#83;&#104;', // ?
			'KGS' => '&#1083;&#1074;',
			'KHR' => '&#6107;',
			'KMF' => '&#67;&#70;', // ?
			'KPW' => '&#8361;',
			'KRW' => '&#8361;',
			'KWD' => '&#1583;.&#1603;', // ?
			'KYD' => '&#36;',
			'KZT' => '&#1083;&#1074;',
			'LAK' => '&#8365;',
			'LBP' => '&#163;',
			'LKR' => '&#8360;',
			'LRD' => '&#36;',
			'LSL' => '&#76;', // ?
			'LTL' => '&#76;&#116;',
			'LVL' => '&#76;&#115;',
			'LYD' => '&#1604;.&#1583;', // ?
			'MAD' => '&#1583;.&#1605;.', //?
			'MDL' => '&#76;',
			'MGA' => '&#65;&#114;', // ?
			'MKD' => '&#1076;&#1077;&#1085;',
			'MMK' => '&#75;',
			'MNT' => '&#8366;',
			'MOP' => '&#77;&#79;&#80;&#36;', // ?
			'MRO' => '&#85;&#77;', // ?
			'MUR' => '&#8360;', // ?
			'MVR' => '.&#1923;', // ?
			'MWK' => '&#77;&#75;',
			'MXN' => '&#36;',
			'MYR' => '&#82;&#77;',
			'MZN' => '&#77;&#84;',
			'NAD' => '&#36;',
			'NGN' => '&#8358;',
			'NIO' => '&#67;&#36;',
			'NOK' => '&#107;&#114;',
			'NPR' => '&#8360;',
			'NZD' => '&#36;',
			'OMR' => '&#65020;',
			'PAB' => '&#66;&#47;&#46;',
			'PEN' => '&#83;&#47;&#46;',
			'PGK' => '&#75;', // ?
			'PHP' => '&#8369;',
			'PKR' => '&#8360;',
			'PLN' => '&#122;&#322;',
			'PYG' => '&#71;&#115;',
			'QAR' => '&#65020;',
			'RON' => '&#108;&#101;&#105;',
			'RSD' => '&#1044;&#1080;&#1085;&#46;',
			'RUB' => '&#1088;&#1091;&#1073;',
			'RWF' => '&#1585;.&#1587;',
			'SAR' => '&#65020;',
			'SBD' => '&#36;',
			'SCR' => '&#8360;',
			'SDG' => '&#163;', // ?
			'SEK' => '&#107;&#114;',
			'SGD' => '&#36;',
			'SHP' => '&#163;',
			'SLL' => '&#76;&#101;', // ?
			'SOS' => '&#83;',
			'SRD' => '&#36;',
			'STD' => '&#68;&#98;', // ?
			'SVC' => '&#36;',
			'SYP' => '&#163;',
			'SZL' => '&#76;', // ?
			'THB' => '&#3647;',
			'TJS' => '&#84;&#74;&#83;', // ? TJS (guess)
			'TMT' => '&#109;',
			'TND' => '&#1583;.&#1578;',
			'TOP' => '&#84;&#36;',
			'TRY' => '&#8356;', // New Turkey Lira (old symbol used)
			'TTD' => '&#36;',
			'TWD' => '&#78;&#84;&#36;',
			'TZS' => '',
			'UAH' => '&#8372;',
			'UGX' => '&#85;&#83;&#104;',
			'USD' => '&#36;',
			'UYU' => '&#36;&#85;',
			'UZS' => '&#1083;&#1074;',
			'VEF' => '&#66;&#115;',
			'VND' => '&#8363;',
			'VUV' => '&#86;&#84;',
			'WST' => '&#87;&#83;&#36;',
			'XAF' => '&#70;&#67;&#70;&#65;',
			'XCD' => '&#36;',
			'XDR' => '',
			'XOF' => '',
			'XPF' => '&#70;',
			'YER' => '&#65020;',
			'ZAR' => '&#82;',
			'ZMK' => '&#90;&#75;', // ?
			'ZWL' => '&#90;&#36;',
		);

		return html_entity_decode( $currency_symbols[ $price['code'] ] ) . $price['value'];
	}

	return '';
}

function bp_get_user_course_lesson_data( $couser_id, $user_id ) {
	// Get Lessons
	$lessons_list           = learndash_get_course_lessons_list( $couser_id, $user_id, [ 'num' => - 1 ] );
	$lesson_order           = 0;
	$topic_order            = 0;
	$lessons                = [];
	$status                 = [];
	$status['completed']    = 1;
	$status['notcompleted'] = 0;
	$course_id              = $couser_id;
	$data                   = [];
	foreach ( $lessons_list as $lesson ) {
		$lessons[ $lesson_order ] = [
			'name'   => $lesson['post']->post_title,
			'id'   => $lesson['post']->ID,
			'status' => $status[ $lesson['status'] ],
		];

		$course_quiz_list[] = learndash_get_lesson_quiz_list( $lesson['post']->ID, $user_id, $course_id );
		$lesson_topics      = learndash_get_topic_list( $lesson['post']->ID, $course_id );

		foreach ( $lesson_topics as $topic ) {

			$course_quiz_list[] = learndash_get_lesson_quiz_list( $topic->ID, $user_id, $course_id );

			$topic_progress = learndash_get_course_progress( $user_id, $topic->ID, $course_id );

			$topics[ $topic_order ] = [
				'name'              => $topic->post_title,
				'status'            => $status['notcompleted'],
				'id'                => $topic->ID,
				'associated_lesson' => $lesson['post']->post_title,
			];

			if ( ( isset( $topic_progress['posts'] ) ) && ( ! empty( $topic_progress['posts'] ) ) ) {
				foreach ( $topic_progress['posts'] as $topic_progress ) {

					if ( $topic->ID !== $topic_progress->ID ) {
						continue;
					}

					if ( 1 === $topic_progress->completed ) {
						$topics[ $topic_order ]['status'] = $status['completed'];
					}
				}
			}
			$topic_order ++;
		}
		$lesson_order ++;
	}
	$total_lesson     = count( $lessons );
	$completed_lesson = count( wp_list_filter( $lessons, array( 'status' => 1 ) ) );
	$pending_lesson   = count( wp_list_filter( $lessons, array( 'status' => 0 ) ) );
	if ( $total_lesson > 0 ) {
		$percentage = intval( $completed_lesson * 100 / $total_lesson );
		$percentage = ( $percentage > 100 ) ? 100 : $percentage;
	} else {
		$percentage = 0;
	}

	$total_topics     = count( $topics );
	$completed_topics = count( wp_list_filter( $topics, array( 'status' => 1 ) ) );
	$pending_topics   = count( wp_list_filter( $topics, array( 'status' => 0 ) ) );
	if ( $total_topics > 0 ) {
		$topics_percentage = intval( $completed_topics * 100 / $total_topics );
		$topics_percentage = ( $topics_percentage > 100 ) ? 100 : $topics_percentage;
	} else {
		$topics_percentage = 0;
	}

	$data['all_lesson'] = $lessons;
	$data['total']      = $total_lesson;
	$data['complete']   = $completed_lesson;
	$data['pending']    = $pending_lesson;
	$data['percentage'] = $percentage;
	$data['topics']     = array(
		'all_topics' => $topics,
		'total'      => $total_topics,
		'complete'   => $completed_topics,
		'pending'    => $pending_topics,
		'percentage' => $topics_percentage,

	);

	return $data;
}
function bp_get_user_course_assignment_data( $course_id, $user_id ) {
	global $wpdb;
	// Assignments
	$assignments            = [];
	$sql_string             = "
		SELECT post.ID, post.post_title, post.post_date, postmeta.meta_key, postmeta.meta_value 
		FROM $wpdb->posts post 
		JOIN $wpdb->postmeta postmeta ON post.ID = postmeta.post_id 
		WHERE post.post_status = 'publish' AND post.post_type = 'sfwd-assignment' 
		AND post.post_author = $user_id
		AND ( postmeta.meta_key = 'approval_status' OR postmeta.meta_key = 'course_id' OR postmeta.meta_key LIKE 'ld_course_%' )";
	$assignment_data_object = $wpdb->get_results( $sql_string );

	foreach ( $assignment_data_object as $assignment ) {

		// Assignment List
		$data               = [];
		$data['ID']         = $assignment->ID;
		$data['post_title'] = $assignment->post_title;

		$assignment_id                                = (int) $assignment->ID;
		$rearranged_assignment_list[ $assignment_id ] = $data;

		// User Assignment Data
		$assignment_id = (int) $assignment->ID;
		$meta_key      = $assignment->meta_key;
		$meta_value    = (int) $assignment->meta_value;

		$date = learndash_adjust_date_time_display( strtotime( $assignment->post_date ) );

		$assignments[ $assignment_id ]['name']           = $assignment->post_title;
		$assignments[ $assignment_id ]['completed_date'] = $date;
		$assignments[ $assignment_id ][ $meta_key ]      = $meta_value;

	}

	foreach ( $assignments as $assignment_id => &$assignment ) {
		if ( isset( $assignment['course_id'] ) && $course_id !== (int) $assignment['course_id'] ) {
			unset( $assignments[ $assignment_id ] );
		} else {
			if ( isset( $assignment['approval_status'] ) && 1 == $assignment['approval_status'] ) {
				$assignment['approval_status'] = 1;
			} else {
				$assignment['approval_status'] = 0;
			}
		}
	}

	$total_assignments     = count( $assignments );
	$completed_assignments = count( wp_list_filter( $assignments, array( 'approval_status' => 1 ) ) );
	$pending_assignments   = count( wp_list_filter( $assignments, array( 'approval_status' => 0 ) ) );
	if ( $total_assignments > 0 ) {
		$percentage = intval( $completed_assignments * 100 / $total_assignments );
		$percentage = ( $percentage > 100 ) ? 100 : $percentage;
	} else {
		$percentage = 0;
	}

	$data['all_lesson'] = $assignments;
	$data['total']      = $total_assignments;
	$data['complete']   = $completed_assignments;
	$data['pending']    = $pending_assignments;
	$data['percentage'] = $percentage;

	return $data;
}
function bp_get_user_course_quiz_data( $course_id, $user_id ) {
	global $wpdb;
	$course_quiz_list   = [];
	$quizzes            = [];
	$course_quiz_list[] = learndash_get_course_quiz_list( $course_id );

	$q = "
			SELECT a.activity_id, a.course_id, a.post_id, a.activity_status, a.activity_completed, m.activity_meta_value as activity_percentage
			FROM {$wpdb->prefix}learndash_user_activity a
			LEFT JOIN {$wpdb->prefix}learndash_user_activity_meta m ON a.activity_id = m.activity_id
			WHERE a.user_id = {$user_id}
			AND a.course_id = {$course_id}
			AND a.activity_type = 'quiz'
			AND m.activity_meta_key = 'percentage'
		";

	$user_activities = $wpdb->get_results( $q );

	foreach ( $course_quiz_list as $module_quiz_list ) {
		if ( empty( $module_quiz_list ) ) {
			continue;
		}

		foreach ( $module_quiz_list as $quiz ) {
			if ( isset( $quiz['post'] ) ) {
				foreach ( $user_activities as $activity ) {
					if ( $activity->post_id == $quiz['post']->ID ) {
						$quizzes[] = [
							'name'             => $quiz['post']->post_title,
							'id'                => $quiz['post']->ID,
							'score'            => $activity->activity_percentage,
							'status'   => 1,
						];
					} else {
						$quizzes[] = [
							'name'             => $quiz['post']->post_title,
							'id'                => $quiz['post']->ID,
							'score'            => $activity->activity_percentage,
							'status'   => 0,
						];
					}
				}
			}
		}
	}

	$total_quizzes     = count( $quizzes );
	$completed_quizzes = count( wp_list_filter( $quizzes, array( 'status' => 1 ) ) );
	$pending_quizzes   = count( wp_list_filter( $quizzes, array( 'status' => 0 ) ) );
	if ( $total_quizzes > 0 ) {
		$percentage = intval( $completed_quizzes * 100 / $total_quizzes );
		$percentage = ( $percentage > 100 ) ? 100 : $percentage;
	} else {
		$percentage = 0;
	}

	$data['all_quizzes'] = $quizzes;
	$data['total']      = $total_quizzes;
	$data['complete']   = $completed_quizzes;
	$data['pending']    = $pending_quizzes;
	$data['percentage'] = $percentage;

	return $data;
}
