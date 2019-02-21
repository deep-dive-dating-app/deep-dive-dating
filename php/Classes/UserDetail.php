<?php
namespace DeepDiveDatingApp\DeepDiveDating;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;
/**
 * The userDetail class that will be used for each of the users that want to add more
 * information about themselves
 *
 * The userDetail contains all of the information that will be displayed on the user page/profile
 **/
class UserDetail implements \JsonSerializable {
	use ValidateUuid;
	/**
	 * Id for for this users details, this is the primary key
	 * @var string|Uuid $userDetailId
	 **/
	private $userDetailId;
	/**
	 * Id to link user details to correct user, this is a foreign key
	 * @var string|Uuid $userDetailUserId
	 **/
	private $userDetailUserId;
	/**
	 * space for user to type in information they choose about themselves
	 * @var string $userDetailAboutMe
	 **/
	private $userDetailAboutMe;
	/**
	 * Age of user
	 * @var int $userDetailAge
	 **/
	private $userDetailAge;
	/**
	 * Information about users career
	 * @var string $userDetailCareer
	 **/
	private $userDetailCareer;
	/**
	 * Users email that will display
	 * @var string $userDetailDisplayEmail
	 **/
	private $userDetailDisplayEmail;
	/**
	 * Education level of user
	 * @var string $userDetailEducation
	 **/
	private $userDetailEducation;
	/**
	 * Gender for user
	 * @var string $userDetailGender
	 **/
	private $userDetailGender;
	/**
	 * Users interests
	 * @var string $userDetailInterests
	 **/
	private $userDetailInterests;
	/**
	 * Users race
	 * @var string $userDetailRace
	 **/
	private $userDetailRace;
	/**
	 * Users religion
	 * @var string $userDetailReligion
	 **/
	private $userDetailReligion;

	/*******Constructor for UserDetail class************
	 *
	 * @param string|Uuid $newUserDetailId id for new user detail
	 * @param string|Uuid $newUserDetailUserId id that links the details to the user?*
	 * @param string $newUserDetailAboutMe string showing users about me section
	 * @param int $newUserDetailAge number depicting users age
	 * @param string $newUserDetailCareer string showing users career information
	 * @param string $newUserDetailDisplayEmail string showing users display email
	 * @param string $newUserDetailEducation string showing users education
	 * @param string $newUserDetailGender string showing users gender
	 * @param string $newUserDetailInterests string showing users interests
	 * @param string $newUserDetailRace string showing users race
	 * @param string $newUserDetailReligion string showing users religion
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds
	 * @throws \Exception for when an exception is thrown
	 * @throws \TypeError if data types violate type hints
	 *
	 ************************************************************/
//todo add type hints
	public function __construct($newUserDetailId, $newUserDetailUserId, string $newUserDetailAboutMe, int $newUserDetailAge, ?string $newUserDetailCareer, string $newUserDetailDisplayEmail, string $newUserDetailEducation, string $newUserDetailGender, string $newUserDetailInterests, string $newUserDetailRace, string $newUserDetailReligion) {
		try {
			$this->setUserDetailId($newUserDetailId);
			$this->setUserDetailUserId($newUserDetailUserId);
			$this->setUserDetailAboutMe($newUserDetailAboutMe);
			$this->setUserDetailAge($newUserDetailAge);
			$this->setUserDetailCareer($newUserDetailCareer);
			$this->setUserDetailDisplayEmail($newUserDetailDisplayEmail);
			$this->setUserDetailEducation($newUserDetailEducation);
			$this->setUserDetailGender($newUserDetailGender);
			$this->setUserDetailInterests($newUserDetailInterests);
			$this->setUserDetailRace($newUserDetailRace);
			$this->setUserDetailReligion($newUserDetailReligion);
		} //determine the exception that was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/******Accessor method for user detail id`***************
	 *
	 * @return Uuid $userDetailId value of user detail id (or null if new user)
	 **/

	public function getUserDetailId(): Uuid {
		return ($this->userDetailId);
	}

	/**********Mutator method for user detail id***************
	 * @param Uuid| string $newUserDetailId value of new user detail id
	 * @throws \InvalidArgumentException if $newUserDetailId is not valid
	 * @throws \RangeException if $newUserDetailId is not positive
	 * @throws \TypeError if the $newUserDetailId is not correct type
	 **/
//todo make sure all at throws are in doc blocks
	public function setUserDetailId($newUserDetailId): void {
		try {
			$uuid = self::validateUuid($newUserDetailId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//convert and store the user detail id
		$this->userDetailId = $uuid;
	}

	/******Accessor method for user detail user id***************
	 *
	 * @return Uuid $userDetailUserId value of user detail user id
	 **/

	public function getUserDetailUserId(): Uuid {
		return ($this->userDetailUserId);
	}

	/*********Mutator method for user detail user id************
	 * @param Uuid| string $newUserDetailUserId value of new user detail user id
	 * @throws \RangeException if $newUserDetailUserId is not within range
	 * @throws \TypeError if the $newUserDetailUserId is not correct type
	 * @throws \InvalidArgumentException if $newUserDetailUserId is not secure
	 **/
	public function setUserDetailUserId($newUserDetailUserId): void {
		try {
			$uuid = self::validateUuid($newUserDetailUserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//convert and store the user detail user id
		$this->userDetailUserId = $uuid;
	}

	/************Accessor method for user detail about me **************
	 *
	 * @return string $userDetailUserId value of user detail about me
	 **/

	public function getUserDetailAboutMe(): string {
		return ($this->userDetailAboutMe);
	}

	/***********Mutator method for user detail about me ****************
	 *
	 **@param string $newUserDetailAboutMe value of new user detail about me
	 * @throws \InvalidArgumentException when about me is not a string or insecure
	 * @throws \RangeException if $newUserDetailAboutMe is > 144 characters
	 * @throws \TypeError if $newUserDetailAboutMe is not a string
	 **/
	public function setUserDetailAboutMe(?string $newUserDetailAboutMe): void {
		if($newUserDetailAboutMe === null) {
			$this->userDetailAboutMe = null;
		}
		$newUserDetailAboutMe = trim($newUserDetailAboutMe);
		$newUserDetailAboutMe = filter_var($newUserDetailAboutMe, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserDetailAboutMe) === true) {
			throw(new \InvalidArgumentException("About me is empty or insecure"));
		}
		//verify the education will fit in the database
		if(strlen($newUserDetailAboutMe) > 1024) {
			throw(new \InvalidRangeException("About Me is too large"));
		}
		//convert and store the about me section
		$this->userDetailAboutMe = $newUserDetailAboutMe;
	}
	/************Accessor method for user detail age*****************
	 * @return int $userDetailAge value for users age
	 **/
//todo make sure all accessors are doc blocked
	public function getUserDetailAge(): int {
		return $this->userDetailAge;
	}

	/**********Mutator method for user detail age*****************
	 *
	 * @param int $newUserDetailAge range is 18-120
	 * @throws \RangeException when input is out of range
	 * @throws \InvalidArgumentException if age is not valid or insecure
	 * @throws \TypeError if incorrect type
	 * */

	public function setUserDetailAge(int $newUserDetailAge): void {
		//if $userDetailAge is null return it right away
		if($newUserDetailAge === null) {
			$this->userDetailAge = null;
			return;
		}
		//verify the age is secure
		$newUserDetailAge = trim($newUserDetailAge);
		$newUserDetailAge = filter_var($newUserDetailAge, FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserDetailAge) === true) {
			throw(new \InvalidArgumentException("Age is empty or insecure"));
		}
		//*verify the age will fit in the database
		if($newUserDetailAge < 18 || $newUserDetailAge > 120) {
			throw(new \RangeException("Age specified is not allowed"));
		}
		//store the age
		$this->userDetailAge = $newUserDetailAge;
	}

	/***********Accessor method for user detail career**********
	 * @return string userDetailCareer
	***/

	public function getUserDetailCareer(): ?string {
		return $this->userDetailCareer;
	}


	/*********Mutator method for user detail career ***********
	 *
	 * @param string $newUserDetailCareer can be various characters
	 * @throws \RangeException when input is out of range
	 * @throws \InvalidArgumentException when input is unsecure
	 * @throws \TypeError when input is wrong type
	 */

	public function setUserDetailCareer(?string $newUserDetailCareer): void {
		if($newUserDetailCareer === null) {
			$this->userDetailCareer = null;
		}
		$newUserDetailCareer = trim($newUserDetailCareer);
		$newUserDetailCareer = filter_var($newUserDetailCareer, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

		if(strlen($newUserDetailCareer) > 1024) {
			throw(new \RangeException("Career is too large"));
		}
		if(empty($newUserDetailCareer) === true) {
			throw(new \InvalidArgumentException("Career is empty or insecure"));
		}

		//convert and store the career section
		$this->userDetailCareer = $newUserDetailCareer;
	}

	/*************Accessor method for user detail display email**************
	* @return string $userDetailDisplayEmail value for user
	**/

	public function getUserDetailDisplayEmail() {
		return $this->userDetailDisplayEmail;
	}

	/********Mutator method for user detail display email ***********
	 *
	 * @param string $newUserDetailDisplayEmail can be various characters
	 * @throws \InvalidArgumentException if $newUserDetailDisplayEmail is not a valid email or insecure
	 * @throws \RangeException if $newUserDetailDisplayEmail is > 128 characters
	 * @throws \TypeError if $newUserDetailDisplayEmail is not a string
	 * */

	public function setUserDetailDisplayEmail(string $newUserDetailDisplayEmail): void {
		//verify the email is secure//
		$newUserDetailDisplayEmail = trim($newUserDetailDisplayEmail);
		$newUserDetailDisplayEmail = filter_var($newUserDetailDisplayEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newUserDetailDisplayEmail) === true) {
			throw(new \InvalidArgumentException("Display email is empty or insecure"));
		}
		//verify the email will fit in the database
		if(strlen(@$newUserDetailDisplayEmail) > 128) {
			throw(new \RangeException("Email is too large"));
		}
		//store the email
		$this->userDetailDisplayEmail = $newUserDetailDisplayEmail;
	}

	/************Accessor method for user detail education****************
	* @return string $getUserDetailEducaton value for users education
	**/

	public function getUserDetailEducation() {
		return $this->userDetailEducation;
	}

	/********************Mutator method for user detail education************
	 *
	 * @param string $newUserDetailEducation new information about education
	 * @throws \InvalidArgumentException if $newUserDetailEducation is not a string or insecure
	 * @throws \RangeException if $newUserDetailEducation is > 256 characters
	 * @throws \TypeError if $newUserDetailEducation is not a string
	 **/

	public function setUserDetailEducation(?string $newUserDetailEducation): void {
		//if $userDetailEducation is null return it right away
		if($newUserDetailEducation === null) {
			$this->userDetailEducation = null;
			return;
		}
		//verify the education is secure
		$newUserDetailEducation = trim($newUserDetailEducation);
		$newUserDetailEducation = filter_var($newUserDetailEducation, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserDetailEducation) === true) {
			throw(new \InvalidArgumentException("Education is empty or insecure"));
		}
		//verify the education will fit in the database
		if(strlen(@$newUserDetailEducation) > 256) {
			throw(new \RangeException("Education is too large"));
		}
		//store the education
		$this->userDetailEducation = $newUserDetailEducation;
	}

	/**************Accessor for user detail gender *********************
	*@return string $userDetailGender value for users gender
	**/

	public function getUserDetailGender() {
		return $this->userDetailGender;
	}

	/*************************Mutator for user detail gender**********
	 *
	 * @param string $newUserDetailGender for gender of user
	 * @throws \InvalidArgumentException if $newUserDetailGender is not valid or insecure
	 * @throws \RangeException if $newUserDetailGender is > 32 characters
	 * @throws \TypeError if $newUserDetailGender is not a string
	 **/
	public function setUserDetailGender(string $newUserDetailGender): void {
		//if $userDetailGender is null return it right away
		if($newUserDetailGender === null) {
			$this->userDetailGender = null;
			return;
		}
		//verify the gender is secure
		$newUserDetailGender = trim($newUserDetailGender);
		$newUserDetailGender = filter_var($newUserDetailGender, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserDetailGender) === true) {
			throw(new \InvalidArgumentException("Gender is empty or insecure"));
		}
		//verify the gender will fit in the database
		if(strlen($newUserDetailGender) > 32) {
			throw(new \RangeException("Gender is too large"));
		}
		//*store the gender
		$this->userDetailGender = $newUserDetailGender;
	}

	/******************Accessor for User Detail Interests********************
	* @return string $userDetailInterests value for users interests
	**/

	public function getUserDetailInterests() {
		return $this->userDetailInterests;
	}

	/***************Mutator for User Detail Interests*************************
	 *
	 * @param string $newUserDetailInterests for interests of user
	 * @throws \InvalidArgumentException if $newUserDetailInterests is not a string or insecure
	 * @throws \RangeException if $newUserDetailInterests is > 144 characters
	 * @throws \TypeError if $newUserDetailInterests is not a string
	 **/

	public function setUserDetailInterests(string $newUserDetailInterests): void {
		//if $userDetailInterests is null return it right away
		if($newUserDetailInterests === null) {
			$this->userDetailInterests = null;
			return;
		}
		//verify the interests is secure
		$newUserDetailInterests = trim($newUserDetailInterests);
		$newUserDetailInterests = filter_var($newUserDetailInterests, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserDetailInterests) === true) {
			throw(new \InvalidArgumentException("Interests is empty or insecure"));
		}
		//verify the interests will fit in the database
		if(strlen(@$newUserDetailInterests) > 1024) {
			throw(new \RangeException("Interests is too large"));
		}
		//store the interests
		$this->userDetailInterests = $newUserDetailInterests;
	}

	/******************Accessor for User Detail Race********************
	* @return string $userDetailRace value for users race
	**/

	public function getUserDetailRace() {
		return $this->userDetailRace;
	}

	/***************Mutator for User Detail Race*************************
	 *
	 * @param string $newUserDetailRace for interests of user
	 * @throws \InvalidArgumentException if $newUserDetailRace is invalid or insecure
	 * @throws \RangeException if $newUserDetailRace is > 32 characters
	 * @throws \TypeError if $newUserDetailRace is not a string
	 **/

	public function setUserDetailRace(string $newUserDetailRace): void {
		//if $userDetailRace is null return it right away
		if($newUserDetailRace === null) {
			$this->userDetailRace = null;
			return;
		}
		//verify the race is secure
		$newUserDetailRace = trim($newUserDetailRace);
		$newUserDetailRace = filter_var($newUserDetailRace, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserDetailRace) === true) {
			throw(new \InvalidArgumentException("Race is empty or insecure"));
		}
		//verify the race will fit in the database
		if(strlen(@$newUserDetailRace) > 32) {
			throw(new \RangeException("Race is too large"));
		}
		//store the race
		$this->userDetailRace = $newUserDetailRace;
	}

	/******************Accessor for User Detail Religion********************
	* @return string $userDetailReligion value for users religion
	**/

	public function getUserDetailReligion() {
		return $this->userDetailReligion;
	}

	/***************Mutator for User Detail Religion*************************
	 *
	 * @param string $newUserDetailReligion for interests of user
	 * @throws \InvalidArgumentException if $newUserDetailReligion is not a string or insecure
	 * @throws \RangeException if $newUserDetailReligion is > 32 characters
	 * @throws \TypeError if $newUserDetailReligion is not a string
	 **/

	public function setUserDetailReligion(string $newUserDetailReligion): void {
		//if $userDetailReligion is null return it right away
		if($newUserDetailReligion === null) {
			$this->userDetailReligion = null;
			return;
		}
		//verify the religion is secure
		$newUserDetailReligion = trim($newUserDetailReligion);
		$newUserDetailReligion = filter_var($newUserDetailReligion, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

		if(empty($newUserDetailReligion) === true) {
			throw(new \InvalidArgumentException("Religion is empty or insecure"));
		}
		//verify the religion will fit in the database
		if(strlen($newUserDetailReligion) > 128) {
			throw(new \RangeException("Religion is too large"));
		}
		//store the religion
		$this->userDetailReligion = $newUserDetailReligion;
	}

	/**
	 * inserts this User Detail into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo): void {
		//create query template
		$query = "INSERT INTO userDetail(userDetailId, userDetailUserId, userDetailAboutMe, userDetailAge, userDetailCareer, userDetailDisplayEmail, userDetailEducation, userDetailGender, userDetailInterests, userDetailRace, userDetailReligion) VALUES(:userDetailId, :userDetailUserId, :userDetailAboutMe, :userDetailAge, :userDetailCareer, :userDetailDisplayEmail, :userDetailEducation, :userDetailGender, :userDetailInterests, :userDetailRace, :userDetailReligion)";
		$statement = $pdo->prepare($query);

		//bind the member variables to the place holders in the template
		$parameters = ["userDetailId" => $this->userDetailId->getBytes(), "userDetailUserId" => $this->userDetailUserId->getBytes(), "userDetailAboutMe" => $this->userDetailAboutMe, "userDetailAge" => $this->userDetailAge, "userDetailCareer" => $this->userDetailCareer, "userDetailDisplayEmail" => $this->userDetailDisplayEmail, "userDetailEducation" => $this->userDetailEducation, "userDetailGender" => $this->userDetailGender, "userDetailInterests" => $this->userDetailInterests, "userDetailRace" => $this->userDetailRace, "userDetailReligion" => $this->userDetailReligion];
		$statement->execute($parameters);
	}

	/**
	 * deletes this user detail from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 */
	public function delete(\PDO $pdo): void {
		// create query template
		$query = "DELETE FROM userDetail WHERE userDetailId = :userDetailId";
		$statement = $pdo->prepare($query);
		// bind the member variables to the place holder in the template
		$parameters = ["userDetailId" => $this->userDetailId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * updates this user detail in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo): void {
		// create query template
		$query = "UPDATE userDetail SET userDetailId = :userDetailId, userDetailUserId = :userDetailUserId, userDetailAboutMe = :userDetailAboutMe, userDetailAge = :userDetailAge, userDetailCareer = :userDetailCareer, userDetailDisplayEmail = :userDetailDisplayEmail, userDetailEducation = :userDetailEducation, userDetailGender = :userDetailGender, userDetailInterests = :userDetailInterests, userDetailRace = :userDetailRace, userDetailReligion = :userDetailReligion WHERE userDetailId = :userDetailId";

		$statement = $pdo->prepare($query);

		$parameters = ["userDetailId" => $this->userDetailId->getBytes(), "userDetailUserId" => $this->userDetailUserId->getBytes(), "userDetailAboutMe" => $this->userDetailAboutMe, "userDetailAge" => $this->userDetailAge, "userDetailCareer" => $this->userDetailCareer, "userDetailDisplayEmail" => $this->userDetailDisplayEmail, "userDetailEducation" => $this->userDetailEducation, "userDetailGender" => $this->userDetailGender, "userDetailInterests" => $this->userDetailInterests, "userDetailRace" => $this->userDetailRace, "userDetailReligion" => $this->userDetailReligion];
		$statement->execute($parameters);
	}

	/**
	 * gets the userDetail by by userDetailId
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $userDetailId user detail id to search for
	 * @return UserDetail|null User detail if found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variable are not the correct data type
	 **/
	public static function getUserDetailByUserDetailId(\PDO $pdo, $userDetailId): ?UserDetail {
		// sanitize the userDetailId before searching
		try {
			$userDetailId = self::validateUuid($userDetailId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		// create query template
		$query = "SELECT userDetailId, userDetailUserId, userDetailAboutMe, userDetailAge, userDetailCareer, userDetailDisplayEmail, userDetailEducation, userDetailGender, userDetailInterests, userDetailRace, userDetailReligion 
					FROM userDetail 
					WHERE userDetailId = :userDetailId";
		$statement = $pdo->prepare($query);
		// bind the user detail id to the place holder in the template
		$parameters = ["userDetailId" => $userDetailId->getBytes()];
		$statement->execute($parameters);
		// grab the userDetail from mySQL
		try {
			$userDetail = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$userDetail = new UserDetail($row["userDetailId"], $row["userDetailUserId"], $row["userDetailAboutMe"], $row["userDetailAge"], $row["userDetailCareer"], $row["userDetailDisplayEmail"], $row["userDetailEducation"], $row["userDetailGender"], $row['userDetailInterests'], $row["userDetailRace"], $row["userDetailReligion"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($userDetail);
	}
	//todo add getUserDetailByUserId
	/**
	 * gets the userDetail by by UserId
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $userDetailUserId user id to search for
	 * @return UserDetail|null User detail if found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variable are not the correct data type
	 **/
	public static function getUserDetailByUserDetailUserId(\PDO $pdo, $userDetailUserId): ?UserDetail {
		// sanitize the userDetailUserId before searching
		try {
			$userDetailUserId = self::validateUuid($userDetailUserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		// create query template
		$query = "SELECT userDetailId, userDetailUserId, userDetailAboutMe, userDetailAge, userDetailCareer, userDetailDisplayEmail, userDetailEducation, userDetailGender, userDetailInterests, userDetailRace, userDetailReligion 
					FROM userDetail 
					WHERE userDetailUserId = :userDetailUserId";
		$statement = $pdo->prepare($query);
		// bind the user detail id to the place holder in the template
		$parameters = ["userDetailUserId" => $userDetailUserId->getBytes()];
		$statement->execute($parameters);
		// grab the userDetail from mySQL
		try {
			$userDetail = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$userDetail = new UserDetail($row["userDetailId"], $row["userDetailUserId"], $row["userDetailAboutMe"], $row["userDetailAge"], $row["userDetailCareer"], $row["userDetailDisplayEmail"], $row["userDetailEducation"], $row["userDetailGender"], $row['userDetailInterests'], $row["userDetailRace"], $row["userDetailReligion"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($userDetail);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize(): array {
		$fields = get_object_vars($this);
		$fields["userDetailId"] = $this->userDetailId->toString();
		$fields["userDetailUserId"] = $this->userDetailUserId->toString();
		return ($fields);
	}
}