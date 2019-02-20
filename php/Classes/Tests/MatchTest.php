W<?php
namespace DeepDiveDatingApp\DeepDiveDating\Tests;


use DeepDiveDatingApp\DeepDiveDating\Match;

require_once(dirname(__DIR__) . "/autoload.php");

require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * unit test for the Match Class
 * PDO methods are located in the Match Class
 * @ see php/Classes/Match.php
 * @author Taylor Smith
 */

class MatchTest extends DeepDiveDatingAppTest {
	/**
	 * protected constant for initial match value
	 * @var Match match
	 **/
	protected $match = null;
	/**
	 * protect constant for the actual match approved value being used to test Match Class
	 * @var int $VALID_MATCH_APPROVED
	 **/
	protected $VALID_MATCH_APPROVED = 1;

	/**
	 * protect constant for the actual match approved value being used to test Match Class
	 * @var int $VALID_MATCH_APPROVED1
	 **/
	protected $VALID_MATCH_APPROVED1 = 0;

	/**
	 * create dependent objects before running each test
	 **/
	public final function setUp()  : void {
		// run the default setUp() method first
		parent::setUp();

		// create and insert a Profile to own the test Tweet
		$this->match = new Match(generateUuidV4(), generateUuidV4(),"0");
		$this->match->insert($this->getPDO());
	}

	/**
	 * create a match object, insert it in the database, and enforce that is meets expectations
	 */
	public function testValidMatchInsert() {
		//get number of rows and save it for the test
		$numRows = $this->getConnection()->getRowCount("match");

		// create the match object
		$match = new Match(generateUuidV4(), generateUuidV4(), $this->VALID_MATCH_APPROVED);
		//insert match object
		$match->insert($this->getPDO());

		//grab data from mySQL and enforce that it meets expectations
		$pdoMatch = Match::getMatchByMatchUserId($this->getPDO(), $match->getMatchUserId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("match"));
		$this->assertEquals($pdoMatch->getMatchUserId(), $match->getMatchUserId());
		$this->assertEquals($pdoMatch->getMatchToUserId(), $match->getMatchToUserId());
		$this->assertEquals($pdoMatch->getMatchApproved(), $match->getMatchApproved());
	}

	/**
	 * create a match object, update it in the database, and then enforce that it meets expectations
	 **/
	public function testValidMatchUpdate() {
		//get number of rows and save it for the test
		$numRows = $this->getConnection()->getRowCount("match");

		//create the match object
		$match = new Match(generateUuidV4(), generateUuidV4(), $this->VALID_MATCH_APPROVED);
		//insert match object
		$match->insert($this->getPDO());

		//edit the quote object then insert the object back into the database
		$match->setMatchApproved($this->VALID_MATCH_APPROVED1);
		$match->update($this->getPDO());
		$pdoMatch =  Match::getMatchByMatchUserId($this->getPDO(), $match->getQuoteId());
		// enforce expectations
		$this->assertEquals($pdoMatch->getMatchUserId(), $match->getMatchUserId());
		$this->assertEquals($pdoMatch->getMatchToUserId(), $match->getMatchToUserId());
		$this->assertEquals($pdoMatch->getMatchApproved(), $match->getMatchApproved());
	}

	/**
	 * create a match object, delete it, then enforce that it was deleted
	 **/
	public function testValidMatchDelete() {
		//get number of rows and save it for the test
		$numRows = $this->getConnection()->getRowCount("match");

		//create match object
		$match = new Match(generateUuidV4(), generateUuidV4(), $this->VALID_MATCH_APPROVED);
		//insert match object
		$match->insert($this->getPDO());
		//delete match from database
		$this->assertSame($numRows +1, $this->getConnection()->getRowCount("match"));
		$match->delete($this->getPDO());

		//enforce that the deletion was successful
		$pdoMatch = Match::getMatchByMatchUserId($this->getPDO(), $match->getMatchUserId());
		$this->assertNull($pdoMatch);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("match"));
	}

	/**
	 * try and get match by a User Id that does not exist
	 **/
	public function testInvalidGetByMatchUserId() {
		//get match by invalid key
		$match = Match::getMatchByMatchUserId($this->getPDO(), DeepDiveDatingAppTest::INVALID_KEY);
		$this->assertEmpty($match);
	}

	/**
	 * try and get match by a To User Id that does not exist
	 **/
	public function testInvalidGetByMatchToUserId() {
		//get match by invalid key
		$match = Match::getMatchByMatchToUserId($this->getPDO(), DeepDiveDatingAppTest::INVALID_KEY);
		$this->assertEmpty($match);
	}

	/**
	 * insert Match object, grab it by Valid Match ToUserId
	 **/
	public function testValidGetByMatchToUserId() {
		//get number of rows and save it for the test
		$numRows = $this->getConnection()->getRowCount("match");

		// create the match object
		$match = new Match(generateUuidV4(), generateUuidV4(), $this->VALID_MATCH_APPROVED);
		//insert match object
		$match->insert($this->getPDO());

		//grab data from mySQL and enforce that it meets expectations
		$pdoMatch = Match::getMatchByMatchToUserId($this->getPDO(), $match->getMatchToUserId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("match"));
		$this->assertEquals($pdoMatch->getMatchUserId(), $match->getMatchUserId());
		$this->assertEquals($pdoMatch->getMatchToUserId(), $match->getMatchToUserId());
		$this->assertEquals($pdoMatch->getMatchApproved(), $match->getMatchApproved());
	}

   /**
	 * insert Match object, grab it By Match UserId And Match ToUserId
	 **/
	public function testValidGetMatchByMatchUserIdAndMatchToUserId() {
		//get number of rows and save it for the test
		$numRows = $this->getConnection()->getRowCount("match");

		// create the match object
		$match = new Match(generateUuidV4(), generateUuidV4(), $this->VALID_MATCH_APPROVED);
		//insert match object
		$match->insert($this->getPDO());

		//grab data from mySQL and enforce that it meets expectations
		$pdoMatch = Match::getMatchByMatchUserIdAndMatchToUserId($this->getPDO(), $match->getMatchUserId(), $match->getMatchToUserId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("match"));
		$this->assertEquals($pdoMatch->getMatchUserId(), $match->getMatchUserId());
		$this->assertEquals($pdoMatch->getMatchToUserId(), $match->getMatchToUserId());
		$this->assertEquals($pdoMatch->getMatchApproved(), $match->getMatchApproved());
	}
}