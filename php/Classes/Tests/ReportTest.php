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
	 * protect Constant for the actual Report Agent that is used for the test
	 * @var string $VALID_REPORT_AGENT1 value of test user agent info
	 **/
	protected $VALID_REPORT_AGENT1 = "Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/10.0 Mobile/14E304 Safari/602.1";

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
	 * protect Constant for the Report Date and Time
	 * @var \DateTime $VALID_REPORT_DATE1 actual value of test date and time
	 **/
	protected $VALID_REPORT_DATE1 = "2018-01-14 4:16:18";

	/**
	 * protect Constant actual value for the Report Ip Address
	 * @var string $VALID_REPORT_IP actual value for test Ip
	 **/
	protected $VALID_REPORT_IP = "192.0.2.16";

	/**
	 * protect Constant actual value for the Report Ip Address
	 * @var string $VALID_REPORT_IP1 actual value for test Ip
	 **/
	protected $VALID_REPORT_IP1 = "192.0.2.7";

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
	 * placeholder activation token for the initial profile creation
	 * @var string $VALID_USERACTIVATIONTOKEN
	 */
	protected $VALID_USERACTIVATIONTOKEN = "10101010101010101010101010101010";

	/**
	 * placeholder for user agent
	 * @var string $VALID_USERAGENT
	 */
	protected $VALID_USERAGENT = "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0 Mozilla/5.0 (Macintosh; Intel Mac OS X x.y; rv:42.0) Gecko/20100101 Firefox/42.0.";
	/**
	 * 2nd placeholder for user agent
	 * @var string $VALID_USERAGENT1
	 */
	protected $VALID_USERAGENT1 = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36.";

	/**
	 * valid url for the user avatar
	 * @var string $VALID_USERAVATARURL
	 */
	protected $VALID_USERAVATARURL = "avatarSAREUS.com";
	/**
	 * valid url for the user avatar
	 * @var string $VALID_USERAVATARURL1
	 */
	protected $VALID_USERAVATARURL1 = "allTheAvatars.com";

	/**
	 * valid int to tell if a user is blocked or not
	 * @var int $VALID_USERBLOCKED
	 */
	protected $VALID_USERBLOCKED = 0;
	/**
	 * valid int to tell if a user is blocked or not
	 * @var int $VALID_USERBLOCKED1
	 */
	protected $VALID_USERBLOCKED1 = 1;

	/**
	 * valid email address for user
	 * @var int $VALID_USEREMAIL
	 */
	protected $VALID_USEREMAIL = "exampleemail@test.com";
	/**
	 * valid email address for user
	 * @var string $VALID_USEREMAIL1
	 */
	protected $VALID_USEREMAIL1 = "anotherEmail@test.com";

	/**
	 * valid handle for user account
	 * @var string $VALID_USERHANDLE
	 */
	protected $VALID_USERHANDLE = "lonelyBoy";
	/**
	 * valid handle for user account
	 * @var string $VALID_USERHANDLE1
	 */
	protected $VALID_USERHANDLE1 = "lonelyGirl";

	/**
	 * valid hash for user password
	 * @var string $VALID_USERHASH
	 */
	protected $VALID_USERHASH = "weakpassword";

	/**
	 * valid binary of the user ip address
	 * @var string $VALID_USERIPADDRESS
	 */
	protected $VALID_USERIPADDRESS = "177.108.73.111";

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
		$results = Report::getReportByUserId($this->getPDO(), $report->getReportUserId());
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
		$results = Report::getReportByUserId($this->getPDO(), $report->getReportUserId());
		$pdoReport = $results[0];
		
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("report"));
		$this->assertEquals($pdoReport->getReportUserId(), $this->VALID_USERID);
		$this->assertEquals($pdoReport->getReportAbuserId(), $this->VALID_USERID2);
		$this->assertEquals($pdoReport->getReportAgent(), $report->getReportAgent());
		$this->assertEquals($pdoReport->getReportContent(), $report->getReportContent());
		$this->assertEquals($pdoReport->getReportDate(), $report->getReportDate());
		$this->assertEquals($pdoReport->getReportIp(), $report->getReportIp());
	}
}