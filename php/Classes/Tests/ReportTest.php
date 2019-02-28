<?php
namespace DeepDiveDatingApp\DeepDiveDating\Tests;


use DeepDiveDatingApp\DeepDiveDating\Report;
use DeepDiveDatingApp\DeepDiveDating\User;

require_once(dirname(__DIR__) . "/autoload.php");

require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * unit test for the Report Class
 * PDO methods are located in the Report Class
 * @ see php/Classes/Report.php
 * @author Taylor Smith
 */

class ReportTest extends DeepDiveDatingAppTest {
	/**
	 * protected constant for initial report value
	 * @var Report report
	 **/
	protected $report = null;
	/**
	 * protect Constant for the actual Report Agent that is used for the test
	 * @var string $VALID_REPORT_AGENT value of test user agent info
	 **/
	protected $VALID_REPORT_AGENT = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36";

	/**
	 * protect Constant for the actual Report Content that is used for the test
	 * @var string $VALID_REPORT_CONTENT value of test report content
	 **/
	protected $VALID_REPORT_CONTENT = "This guy is a super creep!";

	/**
	 * protect Constant for the actual Report Content that is used for the test
	 * @var string $VALID_REPORT_CONTENT1 value of test report content
	 **/
	protected $VALID_REPORT_CONTENT1 = "This girl will not leave me alone!";

	/**
	 * protect Constant for the Report Date and Time
	 * @var \DateTime $VALID_REPORT_DATE actual value of test date and time
	 **/
	protected $VALID_REPORT_DATE = "2018-02-14 14:16:18";

	/**
	 * protect Constant actual value for the Report Ip Address
	 * @var string $VALID_REPORT_IP actual value for test Ip
	 **/
	protected $VALID_REPORT_IP = "192.0.2.16";

	/**
	 * Variables for test users (reporter and abuser)
	 *
	* valid user profile
	* @var User $userOne
	*/
	protected $user = null;

	/**
	 * id for this user
	 * @var Uuid $VALID_USERID
	 */
	protected $VALID_USERID = "ed0c874c-6b0c-4b8c-9478-c70640558a26";

	/**
	 * id for this user
	 * @var Uuid $VALID_USERID2
	 */
	protected $VALID_USERID2= "190671de-177d-4034-86ac-a598887f3ae6";

	/**
	 * create dependent objects before running each test
	 **/
	public final function setUp()  : void {
		// run the default setUp() method first
		parent::setUp();

		$password = "pimpinaintez";
		$hash = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);
		$activationToken = bin2hex(random_bytes(16));
		$this->user = new User($this->VALID_USERID, $activationToken, "Firefox", "www.coolpix.biz", 0, "email@email.com", "Billy Bob", $hash, "177.108.73.111");
		$this->user->insert($this->getPDO());

		$password2 = "passforward";
		$hash2 = password_hash($password2, PASSWORD_ARGON2I, ["time_cost" => 384]);
		$activationToken2 = bin2hex(random_bytes(16));
		$this->user = new User($this->VALID_USERID2, $activationToken2, "Firefox", "www.coolpix.com", 0, "email2@email.com", "Billy Joe", $hash2, "177.108.73.121");
		$this->user->insert($this->getPDO());

	}

	/**
	 * Create Report Object, insert into database, enforce the expectations
	 **/
	public function testValidReportInsert() {
		//get number of rows and save it for the test
		$numRows = $this->getConnection()->getRowCount("report");
		//create report object
		$report = new Report($this->VALID_USERID, $this->VALID_USERID2, $this->VALID_REPORT_AGENT, $this->VALID_REPORT_CONTENT, $this->VALID_REPORT_DATE, $this->VALID_REPORT_IP);
		//insert report into database
		$report->insert($this->getPDO());

		//grab data my database and enforce expectations
		$results = Report::getReportByReportUserId($this->getPDO(), $report->getReportUserId());
		$pdoReport = $results[0];

		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("report"));
		$this->assertEquals($pdoReport->getReportUserId(), $report->getReportUserId());
		$this->assertEquals($pdoReport->getReportAbuserId(), $report->getReportAbuserId());
		$this->assertEquals($pdoReport->getReportAgent(), $report->getReportAgent());
		$this->assertEquals($pdoReport->getReportContent(), $report->getReportContent());
		$this->assertEquals($pdoReport->getReportDate(), $report->getReportDate());
		$this->assertEquals($pdoReport->getReportIp(), $report->getReportIp());
	}

	/**
	 * create Report object, update it in the database, enforce expectations
	 **/
	public function testValidReportUpdate() {
		//get number of rows and save it for the test
		$numRows = $this->getConnection()->getRowCount("report");

		//create report object
		$report = new Report($this->VALID_USERID, $this->VALID_USERID2, $this->VALID_REPORT_AGENT, $this->VALID_REPORT_CONTENT, $this->VALID_REPORT_DATE, $this->VALID_REPORT_IP);
		//insert report into database
		$report->insert($this->getPDO());


		//edit the report object and insert back into the database
		$report->setReportContent($this->VALID_REPORT_CONTENT1);
		$report->update($this->getPDO());
		$results = Report::getReportByReportUserId($this->getPDO(), $report->getReportUserId());
		$pdoReport = $results[0];
		
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("report"));
		$this->assertEquals($pdoReport->getReportUserId(), $this->VALID_USERID);
		$this->assertEquals($pdoReport->getReportAbuserId(), $this->VALID_USERID2);
		$this->assertEquals($pdoReport->getReportAgent(), $report->getReportAgent());
		$this->assertEquals($pdoReport->getReportContent(), $report->getReportContent());
		$this->assertEquals($pdoReport->getReportDate(), $report->getReportDate());
		$this->assertEquals($pdoReport->getReportIp(), $report->getReportIp());
	}
	// todo test get report by report abuser id
	/**
	 * Create Report Object, insert into database, get by Abuser ID, enforce the expectations
	 **/
	public function testValidGetByAbuserId() {
		//get number of rows and save it for the test
		$numRows = $this->getConnection()->getRowCount("report");
		//create report object
		$report = new Report($this->VALID_USERID, $this->VALID_USERID2, $this->VALID_REPORT_AGENT, $this->VALID_REPORT_CONTENT, $this->VALID_REPORT_DATE, $this->VALID_REPORT_IP);
		//insert report into database
		$report->insert($this->getPDO());

		//grab data my database and enforce expectations
		$results = Report::getReportByReportAbuserId($this->getPDO(), $report->getReportAbuserId());
		$pdoReport = $results[0];

		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("report"));
		$this->assertEquals($pdoReport->getReportUserId(), $report->getReportUserId());
		$this->assertEquals($pdoReport->getReportAbuserId(), $report->getReportAbuserId());
		$this->assertEquals($pdoReport->getReportAgent(), $report->getReportAgent());
		$this->assertEquals($pdoReport->getReportContent(), $report->getReportContent());
		$this->assertEquals($pdoReport->getReportDate(), $report->getReportDate());
		$this->assertEquals($pdoReport->getReportIp(), $report->getReportIp());
	}
	//todo test get report by report user id and report abuser
	/**
	 * Create Report Object, insert into database, get by User Id AND Abuser Id, enforce the expectations
	 **/
	public function testValidGetByUserIdAndAbuserId() {
		//get number of rows and save it for the test
		$numRows = $this->getConnection()->getRowCount("report");
		//create report object
		$report = new Report($this->VALID_USERID, $this->VALID_USERID2, $this->VALID_REPORT_AGENT, $this->VALID_REPORT_CONTENT, $this->VALID_REPORT_DATE, $this->VALID_REPORT_IP);
		//insert report into database
		$report->insert($this->getPDO());
		//print_r($report);
		//grab data my database and enforce expectations
		$results = Report::getReportByReportUserIdAndReportAbuserId($this->getPDO(), $report->getReportUserId(), $report->getReportAbuserId());
		$this->assertEquals($numRows +1, $this->getConnection()->getRowCount("report"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("DeepDiveDatingApp\\DeepDiveDating\\Report", $results);
		$pdoReport = $results[0];

		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("report"));
		$this->assertEquals($pdoReport->getReportUserId(), $report->getReportUserId());
		$this->assertEquals($pdoReport->getReportAbuserId(), $report->getReportAbuserId());
		$this->assertEquals($pdoReport->getReportAgent(), $report->getReportAgent());
		$this->assertEquals($pdoReport->getReportContent(), $report->getReportContent());
		$this->assertEquals($pdoReport->getReportDate(), $report->getReportDate());
		$this->assertEquals($pdoReport->getReportIp(), $report->getReportIp());
	}
}