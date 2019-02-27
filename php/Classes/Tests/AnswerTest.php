<?php

namespace DeepDiveDatingApp\DeepDiveDating\Tests;

use DeepDiveDatingApp\DeepDiveDating\{Answer, User, Question};
use Ramsey\Uuid\Uuid;

require_once(dirname(__DIR__, 1) . "/autoload.php");
require_once(dirname(__DIR__,2)."/lib/uuid.php");


/**
* unit test for the Answer class
* PDO methods are located in the Answer class
* @ see php/Classes/Answer.php
* @author Natalie Woodard
*/
class AnswerTest extends DeepDiveDatingAppTest {
	/**
	 * user that is answering; this is for foreign key relations
	 * @var User $user
	 **/
	protected $user = null;

	/**
	 * questions that user is answering; this is for foreign key relations
	 * @var Question $question
	 */
	protected $question = null;

	/**
	 * valid id to create the answer object to own the test
	 * @var Uuid $VALID_ANSWERQUESTIONID
	 **/
	protected $VALID_ANSWERQUESTIONID;

	/**
	 * valid id to create the object to own the test
	 * @var Uuid $VALID_ANSWERUSERID
	 **/
	protected $VALID_ANSWERUSERID = "PHPUnit test passing";

	/**
	 * Result of the answer input from user
	 * @var string $VALID_ANSWERRESULT
	 **/
	protected $VALID_ANSWERRESULT = "c";

	/**
	 *Score of the answers compared to Dan's preferred answers
	 * @var int $VALID_ANSWERSCORE
	 */
	protected $VALID_ANSWERSCORE = 1;

	/**
	 *score of answers for 2nd user
	 */
	//protected $VALID_ANSWERSCORE1 = "9";


protected $questionId = null;

	/**
	 * create dependent objects before running each test
	 **/
	public final function setUp(): void {
		// run the default method first
		parent::setup();
		//adding a user
		$password = "pimpinaintez";
		$userId = generateUuidV4();
		$hash = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);
		$activationToken = bin2hex(random_bytes(16));

		$this->user = new User($userId, $activationToken, "Firefox", "www.coolpix.biz", 0, "email@email.com", "Billy Bob", $hash, "177.108.73.111");
		$this->user->insert($this->getPDO());

		// adding a question
		$questionId = generateUuidV4();
		$this->question = new Question ($questionId, "test question content", "1");
		//insert the user object
		$this->question->insert($this->getPDO());
	}

	//perform the actual insert method and enforce that is meets expectations i.e, corrupted data is worth nothing


public function testValidAnswerInsert() : void {
	//count the number of rows and save it for later
$numRows = $this->getConnection()->getRowCount("answer");

//create the answer object
$answer = new Answer($this->user->getUserId(), $this->question->getQuestionId(), $this->VALID_ANSWERRESULT, $this->VALID_ANSWERSCORE);
//insert the answer object
$answer->insert($this->getPDO());

//grab the data from MySQL and enforce that it meets expectations
$pdoAnswer = Answer::getAnswerByAnswerQuestionId($this->getPDO(), $answer->getAnswerQuestionId());
$this->assertEquals($numRows +1, $this->getConnection()->getRowCount("answer"));
$this->assertEquals($pdoAnswer->getAnswerUserId(), $this->user->getUserId());
$this->assertEquals($pdoAnswer->getAnswerQuestionId(), $this->question->getQuestionId());
$this->assertEquals($pdoAnswer->getAnswerResult(), $this->VALID_ANSWERRESULT);
$this->assertEquals($pdoAnswer->getAnswerScore(), $this->VALID_ANSWERSCORE);
//$this->assertEquals($pdoAnswer->getAnswerScore1(), $this->VALID_ANSWERSCORE1);
}

	/**
	* create a answer object, update it in the database, and then enforce that it meets expectations
	**/
	public function testValidAnswerDelete() {
	//grab the number of answers and save it for the test
	$numRows = $this->getConnection()->getRowCount("answer");


	//create the answer object
	$answer = new Answer($this->user->getUserId(), $this->question->getQuestionId(), $this->VALID_ANSWERRESULT, $this->VALID_ANSWERSCORE);

	//insert the answer object
	$answer->insert($this->getPDO());

	//delete the answer from the database
	$this->assertSame($numRows +1, $this->getConnection()->getRowCount("answer"));
	$answer->delete($this->getPDO());

	//enforce that the deletion was successful
	$pdoAnswer = Answer::getAnswerByAnswerQuestionId($this->getPDO(), $answer->getAnswerQuestionId());
	$this->assertNull($pdoAnswer);
	$this->assertEquals($numRows, $this->getConnection()->getRowCount("answer"));
	}

/**
* try and grab an answer by a primary that does not exist
*/

public function testInvalidGetByAnswerQuestionId(){
//grab the answer by an invalid key
$answer = Answer::getAnswerByAnswerQuestionId($this->getPDO(), DeepDiveDatingAppTest::INVALID_KEY);
$this->assertEmpty($answer);
}

/**
* insert an answer object, grab it by the content, and enforce that it meets expectations
*/
public function testValidGetAnswerByAnswerUserId() {
$numRows = $this->getConnection()->getRowCount("answer");


//create a answer object and insert it into the database
$answer = new Answer($this->user->getUserId(), $this->question->getQuestionId(), $this->VALID_ANSWERRESULT, $this->VALID_ANSWERSCORE);

//insert the answer into the database
$answer->insert($this->getPDO());

//grab the answer from the database
	$pdoAnswer  = Answer::getAnswerByAnswerUserId($this->getPDO(), $answer->getAnswerUserId());
$this->assertEquals($numRows +1, $this->getConnection()->getRowCount("answer"));



	$this->assertEquals($pdoAnswer->getAnswerQuestionId(), $this->question->getQuestionId());
	$this->assertEquals($pdoAnswer->getAnswerUserId(), $this->user->getUserId());
	$this->assertEquals($pdoAnswer->getAnswerResult(), $answer->getAnswerResult());
	$this->assertEquals($pdoAnswer->getAnswerScore(), $answer->getAnswerScore());
}
/**
* try and grab the answer by an answer that does not exist
*/
public function testInvalidGetByAnswerUserId(){
	// grab a profile id that exceeds the maximum allowable profile id
	$answerUserId = generateUuidV4();
$answer = Answer::getAnswerByAnswerUserId($this->getPDO(),$answerUserId);
$this->assertEmpty($answer);
}

/**
* insert an answer use getAll method, then enforce it meets expectation
*/
public function testGetAllAnswers() {
	$numRows = $this->getConnection()->getRowCount("answer");


//insert the answer into the database
	$answer = new Answer($this->user->getUserId(), $this->question->getQuestionId(), $this->VALID_ANSWERRESULT, $this->VALID_ANSWERSCORE);

//insert the answer into the database
	$answer->insert($this->getPDO());

//grab the results from mySQL and enforce it meets expectations
	$results = Answer::getAllAnswers($this->getPDO());
	$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("answer"));
	$this->assertCount(1, $results);
//$this->assertContainsOnlyInstancesOf()

//grab the results from the array and make sure it meets expectations
	$pdoAnswer = $results[0];
//$this->assertEquals($pdoAnswer->getAnswerQuestionId(), $answer->getAnswerQuestionId());
	$this->assertEquals($pdoAnswer->getAnswerQuestionId(), $this->question->getQuestionId());
	$this->assertEquals($pdoAnswer->getAnswerUserId(), $this->user->getUserId());
	$this->assertEquals($pdoAnswer->getAnswerResult(), $answer->getAnswerResult());
	$this->assertEquals($pdoAnswer->getAnswerScore(), $answer->getAnswerScore());
}
}
