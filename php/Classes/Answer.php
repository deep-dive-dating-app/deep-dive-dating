<?php
namespace DeepDiveDatingApp\DeepDiveDating;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
use Ramsey\Uuid\Uuid;

/**
 * Answer class is where the answers to users questions based on Dan's interests appear. They will be graded on their
 * answers to those questions.
 *
 * @author Natalie Woodard
 * @version 1
 **/

class Answer implements \JsonSerializable {
	use ValidateUuid;

	/**Id for answers user will be graded by, this is the primary key
	 * @var string|Uuid $answerUserId
	 **/

	private $answerUserId;
	/**
	 * Id to link answer to user, this is a foreign key
	 * @var string|Uuid $answerQuestionId
	 **/
	private $answerQuestionId;
	/**
	 * Space where the answer to the question appears
	 * @var string $answerResult
	 **/
	private $answerResult;
	/**
	 * Score for user based on answers
	 * @var int $answerScore
	 **/
	private $answerScore;

	/**
	 *
	 *
	 * /*******Constructor for answer class************
	 *
	 * @param string|Uuid $newAnswerUserId id for new answers linked to user
	 * @param string|Uuid $newAnswerQuestionId id for new answers linked to questions
	 * @param string $newAnswerResult id for result of answer from user
	 * @param int $newAnswerScore value that gets calculated from answers to questions
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds
	 * @throws \Exception for when an exception is thrown
	 * @throws \TypeError if data types violate type hints
	 **/
	public function __construct(string $newAnswerUserId, string $newAnswerQuestionId, string $newAnswerResult, int $newAnswerScore) {
		try {
			$this->setAnswerUserId($newAnswerUserId);
			$this->setAnswerQuestionId($newAnswerQuestionId);
			$this->setAnswerResult($newAnswerResult);
			$this->setAnswerScore($newAnswerScore);
			//determine what exception type was thrown
		} catch(\InvalidArgumentException | \RangeException |\Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType ($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for answer user id
	 *
	 * @return Uuid value of answer id (null if new user)
	 **/
	public function getAnswerUserId(): uuid {
		return ($this->answerUserId);
	}

	/**
	 * mutator method for answer user id
	 *
	 * @param Uuid|string $newAnswerUserId is not positive
	 * @throws \InvalidArgumentException if the id is not a string or is insecure
	 * @throws \RangeException if $newAnswerUserId is not positive
	 * @throws \TypeError if $newAnswerUserId is not a uuid or string
	 * @throws \Exception for when an exception is thrown
	 **/
	public function setAnswerUserId($newAnswerUserId): void {
		try {
			$uuid = self::validateUuid($newAnswerUserId);
		} catch( \InvalidArgumentException | \RangeException |\Exception |\TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType ($exception->getMessage(), 0, $exception));
		}

		//convert and store answer user id
		$this->answerUserId = $uuid;
	}

	/**
	 * accessor method for answer question id
	 *
	 * @return uuid for answer question id
	 **/

	public function getAnswerQuestionId(): Uuid {
		return ($this->answerQuestionId);
	}

	/**
	 * mutator method for answer question id
	 *
	 * @param Uuid $newAnswerQuestionId new value answer question id
	 * @throws \InvalidArgumentException if the answer question id is empty
	 * @throws \RangeException if the answer question id is longer than 16 characters
	 * @throws \Exception for when an exception is thrown
	 * @throws \TypeError if data types violate type hints
	 **/

	public function setAnswerQuestionId($newAnswerQuestionId) {
		// sanitize the answerQuestionId before searching
		try {
			$uuid = self::validateUuid($newAnswerQuestionId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		//Store the answer question id
		$this->answerQuestionId = $uuid;
	}

	/**
	 * accessor method for answer result
	 *
	 * @return int value of answer result
	 **/

	public function getAnswerResult(): int {
		return ($this->answerResult);
	}

	/**
	 * mutator method for answer result
	 *
	 * @param string $newAnswerResult new value answer result
	 * @throws \InvalidArgumentException if the answer result is empty
	 * @throws \RangeException if the answer result is longer than one integer
	 * @throws \Exception for when an exception is thrown
	 * @throws \TypeError if data types violate type hints
	 **/

	public function setAnswerResult(string $newAnswerResult) {
		if(empty($newAnswerResult) == true) {
			throw(new \InvalidArgumentException("This result is empty."));
		}
		//verify the answer score is correct or incorrect
		if(($newAnswerResult !== "i") && ($newAnswerResult !== "c")) {
			throw(new \RangeException("This answer is invalid."));
		}
		//Store the answer score
		$this->answerResult = $newAnswerResult;
	}

	/**
	 * accessor method for answer score
	 *
	 * @return int value of answer score
	 **/

	public function getAnswerScore(): int {
		return ($this->answerScore);
	}

	/**
	 * mutator method for answer score
	 *
	 * @param string $newAnswerScore new value answer score
	 * @throws \InvalidArgumentException if the answer score is empty
	 * @throws \RangeException if the answer score is longer than 1
	 * @throws \Exception for when an exception is thrown
	 * @throws \TypeError if data types violate type hints
	 **/

	public function setAnswerScore(string $newAnswerScore) {
		if(empty($newAnswerScore) == true) {
			throw(new \InvalidArgumentException("This answer score is empty."));
		}
		//verify the answer result is no longer than 1 integer.
		if(($newAnswerScore) > 1) {
			throw(new \RangeException("This answer score is too long."));
		}
		//Store the answer result
		$this->answerScore = $newAnswerScore;
	}

	/**
	 * insert this answer in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds
	 * @throws \Exception for when an exception is thrown
	 **/

	public function insert(\PDO $pdo): void {
		// create query template
		$query = "INSERT INTO answer(answerUserId, answerQuestionId, answerResult, answerScore) VALUES(:answerUserId, :answerQuestionId, :answerResult, :answerScore)";
		$statement = $pdo->prepare($query);

		//bind the member variables to the place holders in the template
		$parameters = ["answerUserId" => $this->answerUserId->getBytes(), "answerQuestionId" => $this->answerQuestionId->getBytes(), "answerResult" => $this->answerResult, "answerScore" => $this->answerScore];
		$statement->execute($parameters);
	}

	/**
	 * deletes this Answer from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds
	 * @throws \Exception for when an exception is thrown
	 **/
	public function delete(\PDO $pdo): void {

		// create query template
		$query = "DELETE FROM answer WHERE answerUserId = :answerUserId AND answerQuestionId = :answerQuestionId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["answerUserId" => $this->answerUserId->getBytes()];
		$statement->execute($parameters);
	}
	/**
	 * gets the Answer by answerUserId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $answerUserId answer user id
	 * @return Answer|null Answer found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 * @throws \RangeException if answer user id is not positive
	 **/
	public static function getAnswerByAnswerUserId(\PDO $pdo, $answerUserId): ?Answer {
		// sanitize the answerUserId before searching
		try {
			$answerUserId = self::validateUuid($answerUserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT answerUserId, answerQuestionId, answerResult, answerScore FROM answer WHERE answerUserId = :answerUserId";
		$statement = $pdo->prepare($query);

		// bind the answer user id to the place holder in the template
		$parameters = ["answerUserId" => $answerUserId->getBytes()];
		$statement->execute($parameters);

		// grab the Answer from mySQL
		try {
			$answer = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$answer = new Answer($row["answerUSerId"], $row["answerQuestionId"], $row["answerResult"], $row["answerScore"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($answer);
	}
	/**
	 * gets the Answer by answerQuestionId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $answerUserId answer user id
	 * @return Answer|null Answer found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 * @throws \RangeException if answer user id is not positive
	 **/
	public static function getAnswerByAnswerQuestionId(\PDO $pdo, $answerQuestionId): ?Answer {
		// sanitize the answerQuestionId before searching
		try {
			$answerQuestionId = self::validateUuid($answerQuestionId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT answerUserId, answerQuestionId, answerResult, answerScore FROM answer WHERE answerQuestionId = :answerQuestionId";
		$statement = $pdo->prepare($query);

		// bind the answer user id to the place holder in the template
		$parameters = ["answerQuestionId" => $answerQuestionId->getBytes()];
		$statement->execute($parameters);

		// grab the Answer from mySQL
		try {
			$answer = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$answer = new Answer($row["answerUSerId"], $row["answerQuestionId"], $row["answerResult"], $row["answerScore"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($answer);
	}
	/**
	 * gets the Answer by answerQuestionIdAndUserId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $getAnswerByAnswerQuestionIdAndUserId answer
	 * @return Answer|null Answer found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 * @throws \RangeException if answer user id is not positive
	 **/
	public static function getAnswerByAnswerQuestionIdAndUserId(\PDO $pdo, $answerQuestionId, $answerByUserId): ?Answer {
		// sanitize the getAnswerByAnswerQuestionIdAndUserId before searching
		try {
			$getAnswerByAnswerQuestionIdAndUserId = self::validateUuid($answerQuestionId);
			$getAnswerByAnswerQuestionIdAndUserId = self::validateUuid($answerByUserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT answerUserId, answerQuestionId, answerResult, answerScore FROM answer WHERE getAnswerByAnswerQuestionIdAndUserId = :getAnswerByAnswerQuestionIdAndUserId";
		$statement = $pdo->prepare($query);

		// bind the getAnswerByAnswerQuestionIdAndUserId to the place holder in the template
		$parameters = ["getAnswerByAnswerQuestionIdAndUserId" => $getAnswerByAnswerQuestionIdAndUserId->getBytes()];
		$statement->execute($parameters);

		// grab the Answer from mySQL
		try {
			$answer = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$answer = new Answer($row["answerUSerId"], $row["answerQuestionId"], $row["answerResult"], $row["answerScore"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($answer);
	}
	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize(): array {
		$fields = get_object_vars($this);

		$fields["answerUserId"] = $this->answerUserId->toString();
		$fields["answerQuestionId"] = $this->answerQuestionId->toString();

		return ($fields);
	}
}