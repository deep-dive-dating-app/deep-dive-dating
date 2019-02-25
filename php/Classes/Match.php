<?php
namespace DeepDiveDatingApp\DeepDiveDating;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
use Ramsey\Uuid\Uuid;


/**
 * Match Class
 *
 * This class will be used to store and monitor when a user likes or matches with another user
 *
 * @author Taylor Smith
 * @version 1
 **/

class Match implements \JsonSerializable {
	use ValidateUuid;
	/**
	 * id for the user who liked a profile; this is a foreign key
	 * @var Uuid $matchUserId
	 **/
	private $matchUserId;
	/**
	 * id for the user who's profile was liked; this is a foreign key
	 * @var Uuid $matchToUserId
	 **/
	private $matchToUserId;
	/**
	 * value of match; 1 = true, both users are matched; 0 = false, at least one user has not matched
	 * @var Int $matchApproved
	 **/
	private $matchApproved;

	/**
	 * Constructor for this Match
	 *
	 * @param string|Uuid $newMatchUserId id of the user who liked a profile
	 * @param string|Uuid $newMatchToUserId id of the user who's profile was liked
	 * @param int $newMatchApproved value representing match reciprocity
	 * @throws \InvalidArgumentException if data types are invalid
	 * @throws \RangeException if values are too long or negative
	 * @throws \TypeError if data types violate provided hints
	 * @throws \Exception for other exceptions
	 **/
	//todo add type hints
	public function __construct( $newMatchUserId, $newMatchToUserId, int $newMatchApproved) {
		try {
			$this->setMatchUserId($newMatchUserId);
			$this->setMatchToUserId($newMatchToUserId);
			$this->setMatchApproved($newMatchApproved);
		}

		catch (\InvalidArgumentException | \TypeError | \RangeException | \Exception $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * Accessor Method for Match User Id
	 *
	 * @return Uuid value of User Id for the person who liked a profile
	 **/
	public function getMatchUserId() : Uuid {
		return ($this->matchUserId);
	}

	/**
	 * Mutator Method for Match User Id
	 *
	 * @param Uuid|string new value of Report User Id
	 * @throws \RangeException if $newReportUserId is not positive
	 * @throws \TypeError if $newReportUserId is not a Uuid or string
	 **/
	public function setMatchUserId($newMatchUserId) : void {
		try {
			$uuid = self::validateUuid($newMatchUserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->matchUserId = $uuid;
	}

	/**
	 * Accessor Method for Match To User Id
	 *
	 * @return Uuid value of the Id for the person who;s page was liked
	 **/
	public function getMatchToUserId() : Uuid {
		return ($this->matchToUserId);
	}

	/**
	 * Mutator Method for Match To User Id
	 *
	 * @param Uuid|string new value of Report User Id
	 * @throws \RangeException if $newReportUserId is not positive
	 * @throws \TypeError if $newReportUserId is not a Uuid or string
	 **/
	public function setMatchToUserId($newMatchToUserId) : void {
		try {
			$uuid = self::validateUuid($newMatchToUserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->matchToUserId = $uuid;
	}

	/**
	 * accessor method for Match Approved
	 *
	 * @return INT value 1 or 0 representing if a match is mutual or not
	 **/
	public function getMatchApproved() : int {
		return ($this->matchApproved);
	}

	/**
	 * mutator method for Match Approved
	 *
	 * @param INT new Match Approved Value
	 * @throws \InvalidArgumentException if input is not a valid type
	 * @throws \RangeException if integer is not 0 or 1
	 **/

	public function setMatchApproved(int $newMatchApproved) : void {
		// check if input is valid
		if ($newMatchApproved > 1 || $newMatchApproved < 0) {
			throw(new \RangeException("Match Approved Value is invalid"));
		}
		//store new value on server
		$this->matchApproved = $newMatchApproved;
	}

	/**
	 * inserts match into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {
		//first create query template
		$query = "INSERT INTO `match` (matchUserId, matchToUserId, matchApproved) VALUES (:matchUserId, :matchToUserId, :matchApproved)";
		$statement = $pdo->prepare($query);
		//then bind member variables to placeholders in the template
		$parameters = ["matchUserId" => $this->matchUserId->getBytes(),  "matchToUserId" => $this->matchToUserId->getBytes(), "matchApproved" => $this->matchApproved];
		$statement->execute($parameters);
	}

	/**
	 * Deletes Match from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {
		//first create query template
		$query = "DELETE FROM `match` WHERE matchUserId = :matchUserId";
		$statement = $pdo->prepare($query);
		//then bind member variables to the placeholders in the template
		$parameters = ["matchUserId" => $this->matchUserId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * Updates Match in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) : void {
		//first create query template
		$query = "UPDATE `match` SET matchToUserId = :matchToUserId, matchApproved = :matchApproved WHERE matchUserId = :matchUserId";
		$statement = $pdo->prepare($query);
		//then bind member variables to the placeholder in the template
		$parameters = ["matchUserId" => $this->matchUserId->getBytes(), "matchToUserId" => $this->matchToUserId->getBytes(), "matchApproved" => $this->matchApproved];
		$statement->execute($parameters);
	}
// todo write getMatchByMatchUserIdAndMatchToUserId
	/**
	 * Gets Match by Match User Id and Match To User Id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $matchUserId
	 * @param Uuid|string $matchToUserId
	 * @return \SplFixedArray instance when two users match each other
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public static function getMatchByMatchUserIdAndMatchToUserId(\PDO $pdo, $matchUserId, $matchToUserId) : \SplFixedArray {
		//sanitize both Uuids
		try {
			$matchUserId = self::validateUuid($matchUserId);
			$matchToUserId = self::validateUuid($matchToUserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		//create query template
		$query = "SELECT matchUserId, matchToUserId, matchApproved FROM `match` WHERE matchUserId = :matchUserId AND matchToUserId = :matchToUserId";
		$statement = $pdo->prepare($query);
		//bind variables to the template
		$parameters = ["matchUserId" => $matchUserId->getBytes(), "matchToUserId" => $matchToUserId->getBytes()];
		$statement->execute($parameters);
		//build array
		$matches = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false ) {
			try {
				$match = new Match($row["matchUserId"], $row["matchToUserId"], $row["matchApproved"]);
				$matches[$matches->key()] = $match;
				$matches->next();
			} catch(\Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($matches);
	}

	/**
	 * Gets Matches by User Id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $matchUserId to search by/with
	 * @return \SplFixedArray SPl Fixed Array of matches found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public static function getMatchByMatchUserId(\PDO $pdo, $matchUserId) : \SplFixedArray {
		//sanitize the Uuid
		try {
			$matchUserId = self::validateUuid($matchUserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		//create query template
		$query = "SELECT matchUserId, matchToUserId, matchApproved FROM `match` WHERE matchUserId = :matchUserId";
		$statement = $pdo->prepare($query);
		//bind elements to template
		$parameters = ["matchUserId" => $matchUserId->getBytes()];
		$statement->execute($parameters);
		//build array
		$matches = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$match = new Match($row["matchUserId"], $row["matchToUserId"], $row["matchApproved"]);
				$matches[$matches->key()] = $match;
				$matches->next();
			} catch(\Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($matches);
	}

	/**
	 * Gets Matches By Match To User Id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $matchToUserId user Id for person who was matched to
	 * @return \SplFixedArray collection of reports found or null if none
	 * @throws \PDOException if mySQL errors occur
	 * @throws \TypeError if a variable is not of the correct data type
	 **/
	//todo recompose getAllMatches to getMatchesByMatchToUserId
	public static function getMatchByMatchToUserId(\PDO $pdo, $matchToUserId): \SplFixedArray {
		//sanitize Uuid
		try {
			$matchToUserId = self::validateUuid($matchToUserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		//query template
		$query = "SELECT matchUserId, matchToUserId, matchApproved FROM `match` WHERE matchToUserId = :matchToUserId";
		$statement = $pdo->prepare($query);
		//bind parameters to the template
		$parameters = ["matchToUserId" => $matchToUserId->getBytes()];
		$statement->execute($parameters);
		//build an array of Matches
		$matches = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$match = new Match($row["matchUserId"], $row["matchToUserId"], $row["matchApproved"]);
				$matches[$matches->key()] = $match;
				$matches->next();
			} catch(\Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($matches);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() : array {
		$fields = get_object_vars($this);

		$fields["matchUSerId"] = $this->matchUserId->toString();
		$fields["matchToUserId"] = $this->matchToUserId->toString();

		return($fields);
	}
}