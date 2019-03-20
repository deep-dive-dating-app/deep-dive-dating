<?php
namespace DeepDiveDatingApp\DeepDiveDating;
require_once ("autoload.php");
require_once (dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;
/**
 * The user class will contain the identifying info for the account.
 */
class User implements \JsonSerializable {
	use ValidateUuid;
	/**
	 * Id for the user's account, this is the primary key
	 * @var string | Uuid $userId
	 */
	private $userId;
	/**
	 *activation token for the user account
	 * @var string $userActivationToken
	 */
	private $userActivationToken;
	/**
	 * the browser and system info used by an account, recorded to help with the blocking/reporting process.
	 * @var string $userAgent
	 */
	private $userAgent;
	/**
	 * url for the user avatar
	 * @var string $userAvatarUrl
	 */
	private $userAvatarUrl;
	/**
	 *info on the blocked status on the account
	 * @var int $userBlocked
	 */
	private $userBlocked;
	/**
	 * email address associated with the user account; this is unique
	 * @var string $userEmail
	 */
	private $userEmail;
	/**
	 * handle for the user account, this is unique and should also be indexed
	 * @var string $userHandle
	 */
	private $userHandle;
	/**
	 * encrypted password for the user account
	 * @var string $userHash
	 */
	private $userHash;
	/**
	 * recorded ip address for the user account
	 * @var string userIpAddress
	 */
	private $userIpAddress;
	/**
	 * Cloudinary token for userAvatarUrl
	 * @var string $userCloudinaryToken
	 *
	private $userCloudinaryToken;
	/**
	 * Cloudinary Url for userAvatarUrl
	 * @var string $userCloudUrl
	 *
	 private $userCloudUrl;

	/**
	 * Constructor for this user profile
	 *
	 * @param string|Uuid $newUserId id of this user or null if a new user account
	 * @param string $newUserActivationToken activation token to safe guard against malicious accounts
	 * @param string $newuserAgent string recorded info about the browser and system to assist with blocking/reporting
	 * @param string $newUserAvatarUrl string url to the user's avatar image
	 * @param int $newUserBlocked int info on the blocked status of the user
	 * @param string $newUserEmail string containing the user email
	 * @param string $newUserHandle string containing the handle/username of the user
	 * @param string $newUserHash string containing the password hash
	 * @param string $newUserIpAddress binary, requiring some conversion of the ip address
	 * @throws \InvalidArgumentException if the data types are not valid
	 * @throws \RangeException if the data values are out of bounds (e.g. strings too long, negative integers)
	 * @throws \Exception if some other exception occurs
	 * @throws \TypeError if the data type violates the data hint
	 * @throws  \ArgumentCountError is phpunit don't line up
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 */
	//@param string $newUserCloudinaryToken string containing Cloudinary Token for this user

	public function __construct($newUserId, ?string $newUserActivationToken, string $newuserAgent, string $newUserAvatarUrl, int $newUserBlocked, string $newUserEmail, string $newUserHandle, string $newUserHash, string $newUserIpAddress) {
		//string $newUserCloudinaryToken, string $newUserCloudUrl
		try {
			$this->setUserId($newUserId);
			$this->setUserActivationToken($newUserActivationToken);
			$this->setuserAgent($newuserAgent);
			$this->setUserAvatarUrl($newUserAvatarUrl);
			$this->setUserBlocked($newUserBlocked);
			$this->setUserEmail($newUserEmail);
			$this->setUserHandle($newUserHandle);
			$this->setUserHash($newUserHash);
			$this->setUserIpAddress($newUserIpAddress);
			//$this->>setUserCloudinaryToken($newUserCloudinaryToken);
			//this->setUserCloudUrl($newUserCloudUrl);
 		}

		catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError | \ArgumentCountError $exception) {
			$exceptionType = get_class($exception);
			throw new $exceptionType($exception->getMessage(), 0, $exception);
		}
	}
	/**
	 * accessor method for user id
	 *
	 * @return Uuid value of user id
	 */
	public function getUserId(): Uuid {
		return($this->userId);
	}

	/**
	 * Mutator method for user Id
	 *
	 * @param string | Uuid $newUserId new value of user id
	 * @throws \InvalidArgumentException if the data types are not valid
	 * @throws \RangeException if the data values are out of bounds (e.g. strings too long, negative integers)
	 * @throws \Exception if some other exception occurs
	 * @throws \TypeError if the data type violates the data hint
	 */
	public function setUserId($newUserId): void {
		try{
					$uuid = self::validateUuid($newUserId);
		} catch(\InvalidArgumentException | \RangeException |\Exception | \TypeError $exception) {
					$exceptionType = get_class($exception);
					throw( new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store user Id
		$this->userId = $uuid;
	}
	/**
	 * accessor method for user activation token
	 *
	 * @return string value of the user activation token
	 */
	public function getUserActivationToken(): ?string {
			return ($this->userActivationToken);
	}
	/**
	 * @param string $newUserActivationToken
	 * @throws \InvalidArgumentException if the url is not a string or is insecure
	 * @throws \RangeException if the url is over 255 characters
	 * @throws \TypeError if the url is not a string
	 * @throws \Exception if other errors occur
	 */
	public function setUserActivationToken(?string $newUserActivationToken): void {
		if($newUserActivationToken === null) {
			$this->userActivationToken = null;
			return;
		}
		$newUserActivationToken = strtolower(trim($newUserActivationToken));
		if(ctype_xdigit($newUserActivationToken) === false) {
				throw(new\InvalidArgumentException("user activation token is not valid"));
		}
		// make sure the user activation token is only 32 characters
		if(strlen($newUserActivationToken) !== 32) {
							throw (new\RangeException("user token needs to be 32 characters"));
		}
		$this->userActivationToken = $newUserActivationToken;
	}
	/**
	 * accessor method for user Age
	 *
	 * @return string value for user Age
	 */
	public function getuserAgent(): string {
				return ($this->userAgent);
	}
	/**
	 * mutator method for the user Age
	 *
	 * @param string $newuserAgent new value of the user Age
	 * @throws \InvalidArgumentException if $newuserAgent is not a string or insecure
	 * @throws \RangeException if $newuserAgent is > 255 characters
	 * @throws \TypeError if $newuserAgent is not a string
	 * @throws \Exception
	 */
	public function setUserAgent (string $newuserAgent) : void {
		//verify the the user Age is secure
		$newuserAgent = trim($newuserAgent);
		$newuserAgent = filter_var($newuserAgent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newuserAgent) === true) {
					throw (new \InvalidArgumentException("user Age is empty or insecure"));
		}
		//verify the user Age will fit in the database
		if(strlen($newuserAgent) > 255) {
					throw(new \RangeException("user Age is too large"));
		}
		//store the user Age
		$this->userAgent = $newuserAgent;
	}
	/**
	 * accessor method for user avatar url
	 *
	 * @return string value of user avatar url
	 */
	public function getUserAvatarUrl(): string {
		return ($this->userAvatarUrl);
	}
	/**
	 * mutator method for the user avatar url
	 *
	 * @param string $newUserAvatarUrl new value of the user avatar
	 * @throws \InvalidArgumentException if $newUserAvatarUrl is not a string or is insecure
	 * @throws \RangeException if $newUserAvatarUrl is > 255 characters
	 * @throws \TypeError if the $newUserAvatarUrl is not a string
	 * @throws \Exception
	 */
	public function setUserAvatarUrl (string $newUserAvatarUrl) : void {
		//verify the user Avatar url is secure
		$newUserAvatarUrl = trim($newUserAvatarUrl);
		$newUserAvatarUrl = filter_var($newUserAvatarUrl, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserAvatarUrl) === true) {
					throw(new \InvalidArgumentException("user avatar url is empty or insecure"));
		}
		//verify the user avatar url is secure
		$newUserAvatarUrl = trim($newUserAvatarUrl);
		$newUserAvatarUrl = filter_var($newUserAvatarUrl, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserAvatarUrl) === true) {
					throw (new \InvalidArgumentException("user avatar url is empty or insecure"));
		}
		//store the user avatar url
		$this->userAvatarUrl = $newUserAvatarUrl;
	}
	/**
	 * accessor method for user blocked
	 *
	 * @return int value 1 or 0 representing if the user is blocked
	 */
	public function getUserBlocked(): int {
		return ($this->userBlocked);
	}

	/**
	 * mutator method for userBlocked
	 *
	 * @param int $newUserBlocked blocked value
	 * @throws \InvalidArgumentException if input is not a valid type
	 * @throws \RangeException if integer is not a 0 or 1
	 * @throws \TypeError if values do not match type hint
	 * @throws \Exception if other errors occur
	 */
	public function setUserBlocked($newUserBlocked) {
		//check if input is valid
		if (($newUserBlocked > 1) || ($newUserBlocked < 0)) {
			throw(new \RangeException("user blocked value is invalid"));
		}
		//store new value on the server
		$this->userBlocked = $newUserBlocked;
	}

	/**
	 * accessor method for user email
	 *
	 * @return string value for email
	 */
	public function getUserEmail(): string {
				return $this->userEmail;
	}
	/**
	 * mutator method for email
	 *
	 *@param string $newUserEmail new value of email
	 *@throws \InvalidArgumentException if $newUserEmail is not valid or is insecure
	 *@throws \RangeException if $newUserEmail is > 128 characters
	 *@throws \TypeError if $newUserEmail is not a string
	 */
	public function setUserEmail(string $newUserEmail): void {
		//verify the email is secure
		$newUserEmail = trim($newUserEmail);
		$newUserEmail = filter_var($newUserEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newUserEmail) === true) {
					throw(new \InvalidArgumentException("user email may be invalid or insecure"));
		}
		// verify the email will fit in the database
		if(strlen($newUserEmail) > 128) {
					throw(new \RangeException("user email is too large"));
		}
		//store the email
		$this->userEmail = $newUserEmail;
	}
	/**
	 * accessor method for the user handle
	 *
	 * @return string value for userHandle
	 */
	public function getUserHandle(): string {
				return ($this->userHandle);
	}
	/**
	 * mutator method for user handle
	 *
	 * @param string $newUserHandle new value of the user handle
	 * @throws \InvalidArgumentException if $newUserHandle is not a string or is insecure
	 * @throws \RangeException if $newUserHandle is > 32 characters
	 * @throws \TypeError if $newUserHandle is not a string
	 */
	public function setUserHandle(string $newUserHandle): void {
		//verify the handle is secure
		$newUserHandle = trim($newUserHandle);
		$newUserHandle = filter_var($newUserHandle, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newUserHandle) === true) {
					throw(new \InvalidArgumentException("user handle is empty or insecure"));
		}
		//verify the user handle will fit in the database
		if(strlen($newUserHandle) > 32) {
					throw(new \InvalidArgumentException("user handle is too large"));
		}
		//store the username
		$this->userHandle = $newUserHandle;
	}
	/**
	 * accessor method for user hash
	 *
	 * @return string value of hash
	 */
	public function getUserHash(): string {
				return $this->userHash;
	}

	/**
	 * mutator method for the user hash password
	 *
	 * @param string $newUserHash
	 * @throws \InvalidArgumentException if the hash is not secure
	 * @throws \RangeException if the hash is not 97 characters
	 * @throws \TypeError if the user hash is not a string
	 */
	public function setUserHash(string $newUserHash): void {
		//enforce that the hash is properly formatted
		$newUserHash = trim($newUserHash);
		if(empty($newUserHash) === true) {
					throw(new \InvalidArgumentException("user password hash is empty or insecure"));
		}
		//enforce this hash is really an argon hash
		$userHashInfo = password_get_info($newUserHash);
		if($userHashInfo["algoName"] !== "argon2i") {
					throw(new \InvalidArgumentException("user hash must be 97 characters"));
		}
		//enforce the hash is exactly 97 characters
		if(strlen($newUserHash) !== 97) {
			throw(new \RangeException("user hash must be 97 characters"));
		}
		//store the hash
		$this->userHash = $newUserHash;
	}
	/**
	 * accessor method for the ip address
	 *
	 * @return string value for the ip address
	 */
	public function getUserIpAddress(): string {
				return ($this->userIpAddress);
	}
	/**
	 * mutator method for user Ip Address
	 *
	 * @param string $newUserIpAddress
	 * @throws \InvalidArgumentException if ip address is empty or insecure
	 * @throws \TypeError if the ip address is not a string
	 */
	public function setUserIpAddress(string $newUserIpAddress) {
		//detect the Ip's format and assign it in binary mode
		if(@inet_pton($newUserIpAddress) !== false) {
			$this->userIpAddress = inet_pton($newUserIpAddress);
		} else if (@inet_ntop($newUserIpAddress) !== false) {
			$this->userIpAddress = $newUserIpAddress;
		} else {
			throw(new \InvalidArgumentException("invalid user IP address"));
		}
		//store the ip address
		$this->userIpAddress =$newUserIpAddress;
	}

	/**
	 * mutator method for user cloudinary token
	 *
	 * @param string $newUserCloudinaryToken new value of image cloudinary token
	 * @throws \InvalidArgumentException if $newUserCloudinaryToken is not a string or insecure
	 * @throws \TypeError if $newUserCloudinaryToken is not a string
	 */
	//public function setUserCloudinaryToken(string $newUserCloudinaryToken): void {
			// verify the user cloudinary token content is secure
			//$newUserCloudinaryToken = filter_var($newUserCloudinaryToken, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			//if(empty($newUserCloudinaryToken) === true) {
					//throw(new \InvalidArgumentException("user cloudinary token is empty or insecure"));
			//}

			//store the user cloudinary token
			//$this->userCloudinaryToken = $newUserCloudinaryToken;
	//}

	/**
	 * accessor method for user cloud url
	 *
	 * @return string value user cloud url
	 *
	 */
	//public function getUserCloudUrl(): string {
			//return ($this->userCloudUrl);
	//}

	/*
	 * mutator method for user cloud url
	 *
	 * @param string $newUserCloudUrl new value of user cloud url
	 * @throws \InvalidArgumentException if $newUserCloudUrl is not a string or insecure
	 * @throws \TypeError if $newUserCloudUrl is not a string
	 */
	//public function setUserCloudUrl(string $newUserCloudUrl): void {
			//verify the user cloud url content is secure
			//$newUserCloudUrl = filter_var($newUserCloudUrl, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			//if(empty($newUserCloudUrl) === true) {
					//throw(new \InvalidArgumentException("user cloud url is empty or insecure"));
	//}

	/**
	 *inserts this user into mySQL
	 *
	 *@param \PDO $pdo PDO connection object
	 *@throws \PDOException when mySQL related errors occur
	 *@throws \TypeError if $pdo is not a PDO connection object
	 */
	public function insert(\PDO $pdo) : void {

		// create query template
		$query = "INSERT INTO `user`(userId, userActivationToken, userAgent, userAvatarUrl, userBlocked, userEmail, userHandle, userHash, userIpAddress) VALUES(:userId, :userActivationToken, :userAgent, :userAvatarUrl, :userBlocked, :userEmail, :userHandle, :userHash, :userIpAddress)";
		//userCloudinaryToken, userCloudUrl             :userCloudinaryToken, :userCloudUrl
		$statement = $pdo->prepare($query);

		//bind the member variables to the placeholders in the template
		/**
		 *
		 *double check which ones need getBytes
		 *
		 */
		$parameters = ["userId" => $this->userId->getBytes(), "userActivationToken" => $this->userActivationToken, "userAgent" => $this->userAgent, "userAvatarUrl" => $this->userAvatarUrl, "userBlocked" => $this->userBlocked, "userEmail" => $this->userEmail, "userHandle" => $this->userHandle, "userHash" => $this->userHash, "userIpAddress" => $this->userIpAddress];
		//"userCloudinaryToken" => $this->userCloudinaryToken, "userCloudUrl" => $this->userCloudUrl
		$statement->execute($parameters);
	}

	/**
	 * deletes this user from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection
	 * on object
	 */
	public function delete(\PDO $pdo): void {

		//create query template
		$query = "DELETE FROM user WHERE userId = :userId";
		$statement = $pdo->prepare($query);

		//bind the member variables to the place holder in the template
		$parameters = ["userId" =>$this->userId->getBytes()];
		$statement->execute($parameters);
	}
	/**
	 * updates this user in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 */
	public function update(\PDO $pdo) : void {
		//create query template
		$query = "UPDATE `user` SET userActivationToken = :userActivationToken, userAgent = :userAgent, userAvatarUrl = :userAvatarUrl, userBlocked =:userBlocked, userEmail = :userEmail, userHandle = :userHandle, userHash = :userHash, userIpAddress = :userIpAddress WHERE userId = :userId";
		//userCloudinaryToken, userCloudUrl             :userCloudinaryToken, :userCloudUrl
		$statement = $pdo->prepare($query);

		$parameters = ["userId" => $this->userId->getBytes(), "userActivationToken" => $this->userActivationToken, "userAgent" => $this->userAgent, "userAvatarUrl" => $this->userAvatarUrl, "userBlocked" => $this->userBlocked, "userEmail" => $this->userEmail, "userHandle" => $this->userHandle, "userHash" => $this->userHash, "userIpAddress" => $this->userIpAddress];
		//"userCloudinaryToken" => $this->userCloudinaryToken, "userCloudUrl" => $this->userCloudUrl
		$statement->execute($parameters);
	}
	/**
	 * gets the user by userId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $userId user id to search for
	 * @return user found or null if not found
	 * @throws \PDOException when mySql related errors are found
	 * @throws \TypeError when a variable is not the correct data type
	 */
	public static function getUserByUserId(\PDO $pdo, $userId) : ?User {
		//sanitize the userId before searching
		try {
					$userId = self::validateUuid($userId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
					throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		//create query template
		$query = "SELECT userId, userActivationToken, userAgent, userAvatarUrl, userBlocked, userEmail,userHandle, userHash, userIpAddress FROM `user` WHERE userId=:userId";
		//userCloudinaryToken, userCloudUrl
		$statement = $pdo->prepare($query);

		// bind the user id  to the placeholder in the template
		$parameters = ["userId" => $userId->getBytes()];
		$statement->execute($parameters);

		//grab the user from mySQL
		try {
				$user = null;
				$statement->setFetchMode(\PDO::FETCH_ASSOC);
				$row = $statement->fetch();

				if($row !== false) {
							$user = new User($row["userId"], $row["userActivationToken"], $row["userAgent"], $row["userAvatarUrl"], $row["userBlocked"], $row["userEmail"], $row["userHandle"], $row["userHash"], $row["userIpAddress"]);
							//$row["userCloudinaryToken"], $row["userCloudUrl"]
			}
		} catch(\Exception $exception) {
			//if the row can't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($user);
	}
	/**
	 * gets the user by activation token
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $userActivationToken user activation token to search for
	 * @return User|null user found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \InvalidArgumentException when a variable is not the correct data type
	 */
	public static function getUserByUserActivationToken(\PDO $pdo, string $userActivationToken): ?User {
		//verify activation token is in the correct format and a string representation of hexidecimal
		$userActivationToken = trim($userActivationToken);
		if(ctype_xdigit($userActivationToken) === false) {
				throw(new \InvalidArgumentException("activation token is empty or incorrect format"));
		}

		//create query template
		/**$query = "SELECT user.userId, user.userActivationToken, user.userAgent, user.userAvatarUrl, user.userBlocked, user.userEmail, user.userHandle, user.userHash, user.userIpAddress FROM `user` INNER JOIN userDetail ON user.userId = userDetail.userDetailUserId WHERE userActivationToken = :userActivationToken";**/
		$query = "SELECT userId, userActivationToken, userAgent, userAvatarUrl, userBlocked, userEmail, userHandle, userHash, userIpAddress FROM `user` WHERE userActivationToken = :userActivationToken";
		////userCloudinaryToken, userCloudUrl
		$statement = $pdo->prepare($query);

		//bind activation token to placeholder in template
		$parameters = ["userActivationToken" => $userActivationToken];
		$statement->execute($parameters);

		//grab user from the database
		try {
				$user = null;
				$statement->setFetchMode(\PDO::FETCH_ASSOC);
				$row = $statement->fetch();
				if($row !== false) {
						$user = new User($row["userId"], $row["userActivationToken"], $row["userAgent"], $row["userAvatarUrl"], $row["userBlocked"], $row["userEmail"], $row["userHandle"], $row["userHash"], $row["userIpAddress"]);
						//$row["userCloudinaryToken"], $row["userCloudUrl"]
				}
		} catch(\Exception $exception) {
			//if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($user);
	}
	/**
	 * gets the user by handle
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $userHandle user handle to search for
	 * @return \SplFixedArray of similar values to the User handle
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when the variables are not the correct data type
	 */
	public static function getUserByUserHandle(\PDO $pdo, string $userHandle) : \SplFixedArray {
		//sanitize the description before searching
		$userHandle = trim($userHandle);
		$userHandle = filter_var($userHandle, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($userHandle) === true) {
					throw(new \PDOException("user handle is invalid"));
		}

		//escape any mySQL wildcards
		$userHandle = str_replace("_", "\\_", str_replace("%", "\\%", $userHandle));

		//create query template
		$query = "SELECT userId, userActivationToken, userAgent, userAvatarUrl, userBlocked, userEmail, userHandle, userHash, userIpAddress FROM `user` WHERE userHandle LIKE :userHandle";
		//userCloudinaryToken, userCloudUrl
		$statement = $pdo->prepare($query);

		//bind the user handle to the placeholder in the template
		$userHandle = "%$userHandle%";
		$parameters = ["userHandle" => $userHandle];
		$statement->execute($parameters);

		//build an array of users
		$users = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
					try {
						$user = new User($row["userId"], $row["userActivationToken"], $row["userAgent"], $row["userAvatarUrl"], $row["userBlocked"], $row["userEmail"], $row["userHandle"], $row["userHash"], $row["userIpAddress"]);
						//$row["userCloudinaryToken"], $row["userCloudUrl"]
						$users[$users->key()] = $user;
						$users->next();
					} catch(\Exception $exception) {
						// if the row couldn't be converted, rethrow it
						throw(new \PDOException($exception->getMessage(), 0, $exception));
					}
		}
		return($users);
	}
	/**
	 * gets user by email
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $userEmail user email to search for
	 * @return User|null user found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable is not the correct data type
	 */
	public static function getUserByEmail(\PDO $pdo, string $userEmail): ?User {
		// sanitize the userEmail before searching
		$userEmail = trim($userEmail);
		$userEmail = filter_var($userEmail, FILTER_VALIDATE_EMAIL);
		if(empty($userEmail) === true) {
					throw(new \PDOException("not a valid email"));
		}
		//create query template
		$query = "SELECT userId, userActivationToken, userAgent, userAvatarUrl, userBlocked, userEmail, userHandle, userHash, userIpAddress FROM `user` WHERE userEmail = :userEmail";
		//userCloudinaryToken, userCloudUrl
		$statement = $pdo->prepare($query);

		//bind the user email to the placeholder in the template
		$parameters = ["userEmail" => $userEmail];
		$statement->execute($parameters);

		//grab user from the database
		try {
			$user = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
					$user = new User($row["userId"], $row["userActivationToken"], $row["userAgent"], $row["userAvatarUrl"], $row["userBlocked"], $row["userEmail"], $row["userHandle"], $row["userHash"], $row["userIpAddress"]);
				//$row["userCloudinaryToken"], $row["userCloudUrl"]
			}
		} catch(\Exception $exception) {
			//if the row couldn't be converted, rethrow it.
			throw(new \PDOException($exception->getMessage(), $exception));
		}
		return ($user);
	}
	/**
	 * gets all Users
	 *
	 * @param \PDO $pdo PDO Connection object
	 * @return \SplFixedArray SplFixedArray of Users found or null if not found
	 * @throws \PDOException when MySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 */
	public static function getAllUsers(\PDO $pdo) : \SPLFixedArray {
			//create query template
			$query = " SELECT userId, userActivationToken, userAgent, userAvatarUrl, userBlocked, userEmail, userHandle, userHash, userIpAddress FROM `user`";
			//userCloudinaryToken, userCloudUrl
			$statement = $pdo->prepare($query);
			$statement->execute();

			//build an array of users
			$users = new \SplFixedArray($statement->rowCount());
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			while(($row = $statement->fetch()) !== false) {
					try {
						$user = new User($row["userId"], $row["userActivationToken"], $row["userAgent"], $row["userAvatarUrl"], $row["userBlocked"], $row["userEmail"], $row["userHandle"], $row["userHash"], $row["userIpAddress"]);
						//$row["userCloudinaryToken"], $row["userCloudUrl"]
						$usersWithUserDetail = (object)[
						"user" => $user,
						"userDetail" => UserDetail::getUserDetailByUserDetailUserId($pdo, $user->getUserId())
						];
						$users[$users->key()] = $usersWithUserDetail;
						$users->next();
					} catch(\Exception $exception) {
						// if row couldn't be converted, rethrow it
						throw(new \PDOException($exception->getMessage(), 0, $exception));
					}
			}
			return ($users);
}
	/**
	 * formats the state variables for the JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 */
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["userId"] = $this->userId->toString();
		return ($fields);
	}
}