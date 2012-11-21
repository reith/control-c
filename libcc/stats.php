<?php
/*
 * get number of users, courses, exercises, etc...
 */

require_once 'db.class.php';

class Stats {
	private $course_c = null;
	private $exercise_c = null;
	private $problemset_c = null;
	private $user_c = null;
	private $counts;

	const COURSE = 0;
	const EXERCISE = 1;
	const PROBLEMSET = 2;
	const USER = 3;

	public function __construct( array $categories = null) {
		$this->counts = array_fill(0, 4, null);
		if(!is_null(categories)) {
			foreach($categories as $cat) {
				switch($cat) {
					case self::COURSE: $obj = new CourseCount; break;
					case self::EXERCISE: $obj = new ExerciseCount; break;
					case self::PROBLEMSET: $obj = new ProblemsetCount; break;
					case self::USER: $obj = new UserCount; break;
					default: throw new Exception('Bad Category '.$cat);
				}
				$this->counts[$cat] = $obj;
			}
		}
	}

	public function fetch() {
		$con = DB::instance();
		foreach($this->counts as $countobj) {
			if(!is_null($countobj))
				$countobj->fetch( $con );
		}
		$con = null;
		return $this;
	}

	public function get() {
		$this->result = array();
		$con = DB::instance();

		foreach($this->counts as $countobj) {
			if(!is_null($countobj))
				$this->result[] = $countobj->get();
		}
		$con = null;
		return $this->result;
	}

	public function toJSON() {
		return json_encode($this->get());
	}

}

abstract class EntityCount {
	protected $count;
	protected $name;

	protected function query() {
		return "SELECT COUNT(*) FROM `$this->name`";
	}

	public function fetch( PDO $connection ) {
		$stmt = $connection->prepare( $this->query() );
		$stmt->execute();
		$stmt->bindColumn(1, $this->count, PDO::PARAM_INT);
		$stmt->fetch(PDO::FETCH_BOUND);
	}

	public function get() {
		return array('count' => $this->count, 'name' => $this->name);
	}
}

final class CourseCount extends EntityCount {
	protected $name = 'Course';
}

final class ExerciseCount extends EntityCount {
	protected $name = 'Exercise';
}

final class ProblemsetCount extends EntityCount {
	protected $name = 'Problemset';
}

final class UserCount extends EntityCount {
	protected $name = 'User';
}

?>
