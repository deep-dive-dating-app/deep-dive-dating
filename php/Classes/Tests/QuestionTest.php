<?php


namespace DeepDiveDatingApp\DeepDiveDating\Tests;
use DeepDiveDatingApp\DeepDiveDating\Question;

require_once (dirname(__DIR__) . "/autoload.php");
require_once(dirname(__DIR__,2)."/lib/uuid.php");
//use DeepDiveDatingApp\DeepDiveDating\Question\Test;
/**
 * unit test for the Question class
 * PDO methods are located in the Question class
 * @ see php/Classes/Question.php
 * @author Natalie Woodard
 */
class QuestionTest extends DeepDiveDatingAppTest {

	/**
	 * uuid for users
	 * @var string uuid
	 **/
	protected $uuid1 = "ab963c9e-8994-4108-b3be-ffa5074f9d00";

	/**
	* Questions for users
	 * @var Question question
	 **/
	protected $question = null;

	/**
	 * valid id to create the question object to own the test
	 *@var string $VALID_QUESTIONID
	 **/
protected  $VALID_QUESTIONID = "9efa5233-8f3c-40ab-96ae-38d0814d9751";

	/**
	 * Content of questions
	 * @var string $VALID_QUESTIONCONTENT
	 **/
	protected $VALID_QUESTIONCONTENT = "This is the content of the question";

	/**
	 * Value applied to questions based on Dan's preferences
	 * @var int $VALID_QUESTIONVALUE
	 **/
	protected $VALID_QUESTIONVALUE = "1";
	/**
	 * create all dependent objects so that the test can run properly
	 */
	public final function setUp()  : void {
		// create and insert a Profile to own the Question test
		parent::setUp();
	}
	/**
	 * perform the actual insert method and enforce that is meets expectations i.e, corrupted data is worth nothing
	 **/

	public function testValidQuestionInsert(){
		$numRows = $this->getConnection()->getRowCount("question");

		//create the user object
		$questionId = generateUuidV4();
		$question = new Question ($questionId, $this->VALID_QUESTIONCONTENT, $this->VALID_QUESTIONVALUE);
		//insert the user object
		$question->insert($this->getPDO());


		//grab the data from MySQL and enforce that it meets expectations
		$pdoQuestion = Question::getQuestionByQuestionId($this->getPDO(), $question->getQuestionId());
		$this->assertEquals($numRows +1, $this->getConnection()->getRowCount("question"));
		$this->assertEquals($pdoQuestion->getQuestionId(), $this->VALID_QUESTIONID);
		$this->assertEquals($pdoQuestion->getQuestionContent(),$this->VALID_QUESTIONCONTENT);
		$this->assertEquals($pdoQuestion->getQuestionValue(), $this->VALID_QUESTIONVALUE);
	}

	/**
	 * create a question object, update it in the database, and then enforce that it meets expectations
	 **/
	public function testValidQuestionDelete() {
		//grab the number of questions and save it for the test
		$numRows = $this->getConnection()->getRowCount("question");

		//create the question object
		$questionId = generateUuidV4();
		$question = new Question ($questionId, $this->VALID_QUESTIONCONTENT, $this->VALID_QUESTIONVALUE);
		//insert the user object
		$question->insert($this->getPDO());

		//insert the question object
		$question = new Question ($questionId, $this->VALID_QUESTIONCONTENT, $this->VALID_QUESTIONVALUE);
		//insert the user object
		//$question->insert($this->getPDO());

		//delete the question from the database
		$this->assertSame($numRows +1, $this->getConnection()->getRowCount("question"));
		//$question->delete($this->getPDO);

		//enforce that the deletion was successful
		$pdoQuestion = Question::getQuestionByQuestionId($this->getPDO(), $question->getQuestionId());
		$this->assertEmpty($pdoQuestion);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("question"));
	}

	/**
	 * try and grab a question by a primary that does not exist
	 */

	public function testInvalidGetByQuestionId(){
		//grab the question by an invalid key
		$question = Question::getQuestionByQuestionId($this->getPDO(), DeepDiveDatingAppTest::INVALID_KEY);
		$this->assertEmpty($question);
	}

	/**
	 * insert a question object, grab it by the content, and enforce that it meets expectations
	 */
	public function testValidGetQuestionContent() {
		$numRows = $this->getConnection()->getRowCount("question");

		//create a question object and insert it into the database
		$question = new Question(generateUuidV4(), $this->VALID_QUESTIONCONTENT, $this->VALID_QUESTIONVALUE);

		//insert the question into the database
		$question->insert($this->getPDO());

		//grab the question from the database
		$pdoQuestion = Question::getQuestionByQuestionContent($this->getPDO(), $question->getQuestionContent());
		$this->assertEquals($numRows +1, $this->getConnection()->getRowCount("question"));

		$this->assertEquals($pdoQuestion->getQuestionId(), $this->VALID_QUESTIONID);
		$this->assertEquals($pdoQuestion->getQuestionContent(),$this->VALID_QUESTIONCONTENT);
		$this->assertEquals($pdoQuestion->getQuestionValue(), $this->VALID_QUESTIONVALUE);
	}
 	/**
	 * try and grab the question by a question that does not exist
	 */
	public function testInvalidGetQuestionByQuestionContent(){
		$question = Question::getQuestionByQuestionContent($this->getPDO());
		($this->getPDO());
		$this->assertEmpty($question);
	}

	/**
	 * insert a question use getAll method, then enforce it meets expectation
	 */
	public function testGetAllQuestions(){
		$numRows = $this->getConnection()->getRowCount("question");

		//insert the question into the database
		$question = new Question(generateUuidV4(), $this->VALID_QUESTIONCONTENT,$this->VALID_QUESTIONVALUE);

		//insert the question into the database
		$question->insert($this->getPDO());

		//grab the results from mySQL and enforce it meets expectations
		$results = Question::getAllQuestions($this->getPDO());
		$this->assertEquals($numRows +1, $this->getConnection()->getRowCount("question"));
		$this->assertCount(1, $results);
		//$this->assertContainsOnlyInstancesOf()

		//grab the results from the array and make sure it meets expectations
		$pdoQuestion = $results[0];
		$this->assertEquals($pdoQuestion->getQuestionId(), $this->VALID_QUESTIONID);
		$this->assertEquals($numRows +1, $this->getConnection()->getRowCount("question"));
		$this->assertEquals($pdoQuestion->getQuestionContent(),$this->VALID_QUESTIONCONTENT);
		$this->assertEquals($pdoQuestion->getQuestionValue(), $this->VALID_QUESTIONVALUE);
	}
}