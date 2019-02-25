<?php
namespace DeepDiveDatingApp\DeepDiveDating;
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
use Ramsey\Uuid\Uuid;

/**
 * Question class is where users answer questions based on Dan's interests. They will be graded on their answers to
 * those questions.
 **/

class Question implements \JsonSerializable {
	use ValidateUuid;

	/**Question Id user will be graded by, this is the primary key
	 * @var string|Uuid $questionId
	 **/
	private $questionId;
	/**
	 * Space where Question content appears
	 * @var string $questionContent
	 **/
	private $questionContent;
	/**
	 *Value assigned to each user by Question
	 * @var int $questionValue
	 **/
	private $questionValue;
	/**
	 *
	 * /**Constructor for Question class
	 *
	 * @param string|Uuid $newQuestionId id for question set
	 * @param string $newQuestionContent space where question appears
	 * @param int $newQuestionValue value that gets calculated from answers to questions
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds
	 * @throws \Exception for when an exception is thrown
	 * @throws \TypeError if data types violate type hints
	 *
	 **/
	public function __construct($newQuestionId, string $newQuestionContent,  $newQuestionValue) {
		try {
			$this->setQuestionId($newQuestionId);
			$this->setQuestionContent($newQuestionContent);
			$this->setQuestionValue($newQuestionValue);
			//determine what exception type was thrown
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType ($exception->getMessage(), 0, $exception, $exception->getLine()));
		}
	}

	/**
	 * accessor method for Question id
	 *
	 * @return string value of question id (null if it is a new question id)
	 **/
	public function getQuestionId(): string {
		return ($this->questionId);
	}

	/**
	 * mutator method for Question id
	 *
	 * @param Uuid|string $newQuestionId is not positive
	 * @throws \InvalidArgumentException if the id is not a string or is insecure
	 * @throws \RangeException if $newQuestionId is not positive
	 * @throws \Exception for when an exception is thrown
	 * @throws \TypeError if $newQuestionId is not a uuid or string
	 **/
	public function setQuestionId($newQuestionId): void {
		try {
			$uuid = self::validateUuid($newQuestionId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType ($exception->getMessage(), 0, $exception));
		}

		//convert and store Question id
		$this->questionId = $uuid;
	}

	/**
	 * accessor method for Question content
	 *
	 * @return  Question content
	 **/

	public function getQuestionContent(): string {
		return ($this->questionContent);
	}

	/**
	 * mutator method for question content
	 *
	 * @param string $newQuestionContent new value question content
	 * @throws \InvalidArgumentException if the question content is empty
	 * @throws \RangeException if the question content is longer than one integer
	 * @throws \Exception for when an exception is thrown
	 * @throws \TypeError if data types violate type hints
	 **/

	public function setQuestionContent(string $newQuestionContent) {
		if(empty($newQuestionContent) == true) {
			throw(new \InvalidArgumentException("This question content is empty."));
		}
		//verify the Question content is no longer than 128 characters
		if(strlen($newQuestionContent) > 128) {
			throw(new \RangeException("This question content is too long. It must be no longer than 128 characters."));
		}
		//Store the Question
		$this->questionContent = $newQuestionContent;
	}

	 /** accessor method for Question value
	 *
	 * @return string for Question value
	 **/

	public function getQuestionValue(): int {
		return ($this->questionValue);
	}
	/**
	 * mutator method for question value
	 *
	 * @param Uuid|int $newQuestionValue is not positive
	 * @throws \InvalidArgumentException if the id is not a string or is insecure
	 * @throws \RangeException if $newQuestionValue is not positive
	 * @throws \Exception for when an exception is thrown
	 * @throws \TypeError if $newQuestionValue is not an int
	 **/
	public function setQuestionValue(int $newQuestionValue): void {
		if(empty($newQuestionValue) == "i" || "c")

		//convert and store Question value
		$this->questionValue = $newQuestionValue;
	}
	/**

	/**
	 * insert this Question content in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \InvalidArgumentException if the content is not secure
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 * @throws \Exception for when an exception is thrown
	*	@throws \RangeException if content is not positive
	 **/

	public function insert(\PDO $pdo): void {
		// create query template
		$query = "INSERT INTO question(questionId, questionContent, questionValue) VALUES(:questionId, :questionContent, :questionValue)";
		$statement = $pdo->prepare($query);

		//bind the member variables to the place holders in the template
		$parameters = ["questionId" => $this->questionId->getBytes(), "questionContent" => $this->questionContent, "questionValue" => $this->questionValue];
		$statement->execute($parameters);
	}

	/**
	 * deletes this Question from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \InvalidArgumentException if the question is not secure
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 * @throws \Exception for when an exception is thrown
	 * @throws \RangeException if $newQuestionValue is not positive
	 **/
	public function delete(\PDO $pdo): void {

		// create query template
		$query = "DELETE FROM question WHERE questionId = :questionId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["questionId" => $this->questionId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * gets the Question by questionId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $questionId question id
	 * @return Question|null Question found or null if not found
	 * @throws \InvalidArgumentException if question is not a string or is insecure
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 * @throws \Exception for when an exception is thrown
	 * @throws \RangeException if question id is not positive
	 **/
	public static function getQuestionByQuestionId(\PDO $pdo, $questionId): ?Question {
		// sanitize the questionId before searching
		try {
			$questionId = self::validateUuid($questionId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT questionId, questionContent, questionValue FROM question WHERE questionId = :questionId";
		$statement = $pdo->prepare($query);

		// bind the Question id to the place holder in the template
		$parameters = ["questionId" => $questionId->getBytes()];
		$statement->execute($parameters);

		// grab the Question from mySQL
		try {
			$question = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$question = new Question($row["questionId"], $row["questionContent"], $row["questionValue"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			var_dump($exception);
			throw new \PDOException($exception->getMessage(), 0, $exception);
		}
		return ($question);
	}

	/**
	 *
	 * gets all Questions
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of Question found or null if not found
	 * @throws \InvalidArgumentException if PDO is insecure
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 * @throws \Exception for when an exception is thrown
	 **/
	public static function getAllQuestions(\PDO $pdo): \SPLFixedArray {
		// create query template
		$query = "SELECT questionId, questionContent, questionValue FROM question";
		$statement = $pdo->prepare($query);
		$statement->execute();

		// build an array of Questions
		$questions = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$question = new Question($row["questionId"], $row["questionContent"], $row["questionValue"]);
				$questions[$questions->key()] = $question;
				$questions->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception, $exception->getLine()));
			}
		}
		return ($questions);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize(): array {
		$fields = get_object_vars($this);

		$fields["questionId"] = $this->questionId->toString();
		return ($fields);
	}
}