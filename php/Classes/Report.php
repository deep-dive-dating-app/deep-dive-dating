<?php
namespace DeepDiveDatingApp\DeepDiveDating;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;
use DeepDiveDatingApp\DeepDiveDating\ValidateDate;

/**
 * Report Class
 *
 * this class is used to store information regarding malicious user activity
 *
 * @author Taylor Smith
 **/

class Report implements \JsonSerializable {
	use ValidateUuid;
	use ValidateDate;
	/**
	 * id for the user who submitted the report
	 * @var \Uuid $reportUserId
	 **/
	private $reportUserId;
	/**
	 * id for the user accused of misconduct
	 * @var \Uuid $reportAbuserId
	 **/
	private $reportAbuserId;
	/**
	 * User Agent information
	 * @var \string $reportAgent
	 **/
	private $reportAgent;
	/**
	 * details of the incident being reported
	 * @var \string $reportContent
	 **/
	private $reportContent;
	/**
	 * date and time that the report was made
	 * @var \DateTime $reportDate
	 **/
	private $reportDate;
	/**
	 * Ip address of the user making the report
	 * @var \Binary $reportIp
	 **/
	private $reportIp;

	/**
	 * Constructor Method for Report
	 *
	 * @param string|Uuid $newReportUserId user id for the account making the report
	 * @param string|Uuid $newReportAbuserId user id for the account detailed in the report
	 * @param string $newReportAgent agent information for the user who made the report
	 * @param string $newReportContent value/contents of the report
	 * @param \DateTime|string $newReportDate date and time report was sent
	 * @param string|Binary $newReportIp Ip address of user who submits this report
	 * @throws \InvalidArgumentException if data type is invalid
	 * @throws \RangeException if data values exceed limits
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	//todo add type hints
	public function __construct($newReportUserId, $newReportAbuserId, string $newReportAgent, string $newReportContent, $newReportDate, string $newReportIp) {
		try {
			$this->setReportUserId($newReportUserId);
			$this->setReportAbuserId($newReportAbuserId);
			$this->setReportAgent($newReportAgent);
			$this->setReportContent($newReportContent);
			$this->setReportDate($newReportDate);
			$this->setReportIp($newReportIp);
		}

		catch (\InvalidArgumentException | \TypeError | \RangeException | \Exception $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * Accessor Method for Report User Id
	 *
	 * @return string|Uuid value of User Id for the person who made the report
	 **/
	public function getReportUserId() : Uuid {
		return($this->reportUserId);
	}

	/**
	 * Mutator Method for Report User Id
	 *
	 * @param Uuid|string new value of Report User Id
	 * @throws \RangeException if $newReportUserId is not positive
	 * @throws \TypeError if $newReportUserId is not a Uuid or string
	 **/
	public function setReportUserId( $newReportUserId ) : void {
		try {
			$uuid = self::validateUuid($newReportUserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->reportUserId = $uuid;
	}

	/**
	 * Accessor Method for Report Abuser Id
	 *
	 * @return string|Uuid value of User Id for the person who the report is about
	 **/
	public function getReportAbuserId() : Uuid {
		return($this->reportAbuserId);
	}

	/**
	 * Mutator Method for Report Abuser Id
	 *
	 * @param Uuid|string $newReportAbuserId new value of Report Abuser Id
	 * @throws \RangeException if $newReportAbuserId is not positive
	 * @throws \TypeError if $newReportAbuserId is not a Uuid or string
	 **/
	public function setReportAbuserId( $newReportAbuserId ) : void {
		try {
			$uuid = self::validateUuid($newReportAbuserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->reportAbuserId = $uuid;
	}

	/**
	 * Accessor Method for Report Agent
	 *
	 * @return string user agent details
	 **/
	public function getReportAgent() : string {
		return($this->reportAgent);
	}

	/**
	 * Mutator Method for Report Agent
	 *
	 * @param string $newReportAgent user agent info for the person making the report
	 * @throws \InvalidArgumentException if $newReportAgent is not a string or insecure
	 * @throws \RangeException if $newReportAgent is > 255 characters
	 * @throws \TypeError if $newReportAgent is not a string
	 **/
	public function setReportAgent( $newReportAgent ) : void {
		// verify that agent is secure
		$newReportAgent = trim($newReportAgent);
		$newReportAgent = filter_var($newReportAgent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newReportAgent) === true ) {
			throw(new \InvalidArgumentException("Agent information is empty or insecure"));
		}
		// check that length will fit in database
		if(strlen($newReportAgent) > 255 ) {
			throw(new \RangeException("Agent Information is to Large"));
		}
		// store agent
		$this->reportAgent = $newReportAgent;
	}

	/**
	 * Accessor Method for Report Content
	 *
	 * @return string details of incident being reported
	 **/
	public function getReportContent() : string {
		return($this->reportContent);
	}

	/**
	 * Mutator Method for Report Content
	 *
	 * @param string $newReportContent new value of report content
	 * @throws \InvalidArgumentException if $newReportContent is not a string or insecure
	 * @throws \RangeException if $newReportContent is > 255 characters
	 * @throws \TypeError if $newReportContent is not a string
	 **/
	public function setReportContent( $newReportContent ) : void {
		//verify that content is secure
		$newReportContent = trim($newReportContent);
		$newReportContent = filter_var($newReportContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newReportContent) === true ) {
			throw(new \InvalidArgumentException("Content is empty or insecure"));
		}
		//check that length will fit in database
		if(strlen($newReportContent) > 255 ) {
			throw(new \RangeException("Report Content is too large"));
		}
		//store content
		$this->reportContent = $newReportContent;
	}

	/**
	 * Accessor Method for Report Date
	 *
	 * @return \DateTime value of report date and time
	 **/
	public function getReportDate() : \DateTime {
		return($this->reportDate);
	}

	/**
	 * Mutator Method for Report Date
	 *
	 * @param \DateTime|string|null $newReportDate datetime object, string, or null
	 * @throws \InvalidArgumentException if $newReportDate is not a valid object or string
	 * @throws \RangeException if $newReportDate is a date that does not exist
	 * @throws \TypeError if $newReportDate is not a \DateTime object
	 * @throws \Exception if some other exception occurs
	 **/
	//todo add all exceptions and throws in doc block and in mutator
	public function setReportDate( $newReportDate ) : void {
		//if date time is null use current date and time
		if($newReportDate === null) {
			$this->reportDate = new \DateTime();
			return;
		}
		//use ValidateDate to store new date time
		try {
			$newReportDate = self::validateDateTime($newReportDate);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->reportDate = $newReportDate;
	}

	/**
	 * Accessor Method for Report Ip
	 *
	 * @return string Ip address of the person making the report
	 **/
	public function getReportIp() : string {
		return(@inet_ntop($this->reportIp));
	}

	/**
	 * Mutator Method for Report Ip
	 *
	 * @param string $newReportIp new value of user's IP address
	 * @throws \InvalidArgumentException if $newReportIp is not a valid IP address
	 **/
	public function setReportIp(string $newReportIp) {
		// detect the IP's format and assign it in binary mode
		if(@inet_pton($newReportIp) !== false) {
			$this->reportIp = inet_pton($newReportIp);
		} else if(@inet_ntop($newReportIp) !== false) {
			$this->reportIp = $newReportIp;
		} else {
			throw(new \InvalidArgumentException("Invalid Report IP Address"));
		}
	}

	/**
	 * Inserts report into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {
		//query template
		$query = "INSERT INTO report (reportUserId, reportAbuserId, reportAgent, reportContent, reportDate, reportIp) VALUES (:reportUserId, :reportAbuserId, :reportAgent, :reportContent, :reportDate, :reportIp)";
		$statement = $pdo->prepare($query);
		//bind variables to the template
		$formattedDate = $this->reportDate->format("Y-m-d H:i:s.u");
		$parameters = ["reportUserId" => $this->reportUserId->getBytes(), "reportAbuserId" => $this->reportAbuserId->getBytes(), "reportAgent" => $this->reportAgent, "reportContent" => $this->reportContent, "reportDate" => $formattedDate, "reportIp" => $this->reportIp];
		$statement->execute($parameters);
	}

	/**
	 * Deletes report from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {
		//query template
		$query = "DELETE FROM report WHERE reportUserId = :reportUserId";
		$statement = $pdo->prepare($query);
		//bind variables to the template
		$parameters = ["reportUserId" => $this->reportUserId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * Updates report in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) :void {
		//query template
		$query = "UPDATE report SET reportAbuserId = :reportAbuserId, reportAgent = :reportAgent, reportContent = :reportContent, reportDate = :reportDate WHERE reportUserId = :reportUserId";
		$statement = $pdo->prepare($query);
		//bind variables to the template
		$formattedDate = $this->reportDate->format("Y-m-d H:i:s.u");
		$parameters = ["reportUserId" => $this->reportUserId->getBytes(), "reportAbuserId" => $this->reportAbuserId->getBytes(), "reportAgent" => $this->reportAgent, "reportContent" => $this->reportContent, "reportDate" => $formattedDate, "reportIp" => $this->reportIp];
		$statement->execute($parameters);
	}

	/**
	 * Gets Reports by User Id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $reportUserId ID of user who made reports
	 * @return \SplFixedArray collection of reports that were found or null if not found
	 * @throws \PDOException if mySQL errors occur
	 * @throws \TypeError if a variable is not of the correct data type
	 **/
	public static function getReportByUserId(\PDO $pdo, $reportUserId) : \SplFixedArray {
		//sanitize user id before search
		try {
			$reportUserId = self::validateUuid($reportUserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0 ,$exception));
		}
		//query template
		$query = "SELECT reportUserId, reportAbuserId, reportAgent, reportContent, reportDate, reportIp FROM report WHERE reportUserId = :reportUserId";
		$statement = $pdo->prepare($query);
		//bind variables to template
		$parameters = ["reportUserId" => $reportUserId->getBytes()];
		$statement->execute($parameters);
		//build an array of Reports
		$reports = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while (($row = $statement->fetch()) !== false) {
			try {
				$report = new Report($row["reportUserId"], $row["reportAbuserId"], $row["reportAgent"], $row["reportContent"], $row["reportDate"], $row["reportIp"]);
				$reports[$reports->key()] = $report;
				$reports->next();
			} catch(\Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return $reports;
	}

	/**
	 * Gets Reports by Abuser Id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uudi|string $reportAbuserId ID of user who reports are about
	 * @return \SplFixedArray collection of reports that were found or null if not found
	 * @throws \PDOException if mySQL errors occur
	 * @throws \TypeError if a variable is not of the correct data type
	 **/
	public static function getReportByAbuserId(\PDO $pdo, $reportAbuserId) : \SplFixedArray {
		//sanitize string before search
		try {
			$reportAbuserId = self::validateUuid($reportAbuserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		//query template
		$query = "SELECT reportUserId, reportAbuserId, reportAgent, reportContent, reportDate, reportIp FROM report WHERE reportAbuserId = :reportAbuserId";
		$statement = $pdo->prepare($query);
		//bind variables to template
		$parameters = ["reportAbuserId" => $reportAbuserId->getBytes()];
		$statement->execute($parameters);
		//build an array of Reports
		$reports = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$report = new Report($row["reportUserId"], $row["reportAbuserId"], $row["reportAgent"], $row["reportContent"], $row["reportDate"], $row["reportIp"]);
				$reports[$reports->key()] = $report;
				$reports->next();
			} catch(\Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return $reports;
	}

	/**
	 * Gets Reports by Content
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $reportContent value of report details
	 * @return \SplFixedArray collection of reports that were found or null if not found
	 * @throws \PDOException if mySQL errors occur
	 * @throws \TypeError if a variable is not of the correct data type
	 **/
	public static function getReportByContent(\PDO $pdo, string $reportContent) : \SplFixedArray {
		//sanitize string before search
		$reportContent = trim($reportContent);
		$reportContent = filter_var($reportContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($reportContent) === true) {
			throw(new \PDOException("report content is invalid"));
		}

		//escape any mySQL wildcards
		$reportContent = str_replace("_", "\\_", str_replace("%", "\\%", $reportContent));

		//query template
		$query = "SELECT reportUserId, reportAbuserId, reportAgent, reportContent, reportDate, reportIp FROM report WHERE reportContent LIKE :reportContent";
		$statement = $pdo->prepare($query);

		//bind variables to the template
		$reportContent = "%$reportContent%";
		$parameters = ["reportContent" => $reportContent];
		$statement->execute($parameters);

		//build an array of Reports
		$reports = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$report = new Report($row["reportUserId"], $row["reportAbuserId"], $row["reportAgent"], $row["reportContent"], $row["reportDate"], $row["reportIp"]);
				$reports[$reports->key()] = $report;
				$reports->next();
			} catch(\Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($reports);
	}

	//todo write getReportByReportUserIdAndReportAbuserId
	/**
	 * Gets Reports By Report User Id And Report Abuser Id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $reportUserId
	 * @param Uuid|string $reportAbuserId
	 * @return \SplFixedArray Spl Fixed Array of Reports found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public static function getReportByReportUserIdAndReportAbuserId(\PDO $pdo, Uuid $reportUserId, Uuid $reportAbuserId) : \SplFixedArray {
		//sanitize both Uuids
		try {
			$reportUserId = self::validateUuid($reportUserId);
			$reportAbuserId = self::validateUuid($reportAbuserId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		//create query
		$query = "SELECT reportUserId, reportAbuserId, reportAgent, reportContent, reportDate, reportIp FROM report WHERE reportUserId = :reportUserId AND reportAbuserId = :reportAbuserId";
		$statement = $pdo->prepare($query);
		//bind variables to query template
		$parameters = ["reportUserId" => $reportUserId, "reportAbuserId" => $reportAbuserId];
		$statement->execute($parameters);
		//build array of reports
		$reports = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$report = new Report($row["reportAbuserId"], $row["reportAbuserId"], $row["reportAgent"], $row["reportContent"], $row["reportDate"], $row["reportIp"]);
				$reports[$reports->key()] = $report;
				$reports->next();
			} catch( \Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($reports);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() : array {
		$fields = get_object_vars($this);

		$fields["reportUserId"] = $this->reportUserId->toString();
		$fields["reportAbuserId"] = $this->reportAbuserId->toString();

		//format the date so that the front end can consume it
		$fields["reportDate"] = round(floatval($this->reportDate->format("U.u")) * 1000);
		return($fields);
	}
}