<?php


namespace DeepDiveDatingApp\DeepDiveDating\Question\Tests;
use DeepDiveDatingApp\DeepDiveDating\Question;

require_once("autoload.php");
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
	* Questions for users
	 * @var Question question
	 **/
	protected $question = null;

	/**
	 * valid id to create the question object to own the test
	 *@var string $VALID_QUESTIONID
	 **/
protected $VALID_QUESTIONID;

	/**
	 * Content of questions
	 * @var string $VALID_QUESTION_CONTENT
	 **/
	protected $VALID_QUESTIONCONTENT = "PHPUnit test passing";

	/**
	 * Value applied to questions based on Dan's preferences
	 * @var int $VALID_QUESTION_VALUE
	 **/
	protected $VALID_QUESTIONVALUE;
	/**
	 * create all dependent objects so that the test can run properly
	 */
	public final function setUp()  : void {
		// create and insert a Profile to own the test Tweet
		$this->question = new Question(generateUuidV4(), null, "1", $this->VALID_QUESTIONID, "+1212555121212121");
		$this->question->insert($this->getPDO());
	}
	/**
	 * perform the actual insert method and enforce that is meets expectations i.e, corrupted data is worth nothing
	 **/

	public function testValidQuestionInsert(){
		$numRows = $this->getConnection()->getRowCount("question");

		//create the question object
		$question = new Question(generateUuidV4(), $this->VALID_QUESTIONID, $this->VALID_QUESTIONCONTENT, $this->VALID_QUESTIONVALUE);
		//insert the question object
		$question->insert($this->getPDO());

		//grab the data from MySQL and enforce that it meets expectations
		$pdoQuestion = Question::getQuestionByQuestionId($this->getPDO(), $question->getQuestionId());
		$this->assertEquals($numRows +1, $this->getConnection()->getRowCount("question"));
		$this->assertEquals($pdoQuestion->getQuestionId(), $question->getQuestionId());
		$this->assertEquals($pdoQuestion->getQuestionContent(), $question->getQuestionContent());
		$this->assertEquals($pdoQuestion->getQuestionValue(), $question->getQuestionValue);
	}

	/**
	 * create a question object, update it in the database, and then enforce that it meets expectations
	 **/
	public function testValidQuestionDelete() {
		//grab the number of questions and save it for the test
		$numRows = $this->getConnection()->getRowCount("question");

		//create the question object
		$question = new Question(generateUuidV4(), $this->VALID_QUESTIONID, $this->VALID_QUESTIONCONTENT, $this->VALID_QUESTIONCONTENT);

		//insert the question object
		$question->insert($this->getPDO());

		//delete the question from the database
		$this->assertSame($numRows +1, $this->getConnection()->getRowCount("question"));
		$question->delete($this->getPDO);

		//enforce that the deletion was successful
		$pdoQuestion = Question::getQuestionByQuestionId($this->getPDO(), $question->getQuestionId());
		$this->assertNull($pdoQuestion);
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
	public function testValidGetQuestionByContent() {
		$numRows = $this->getConnection()->getRowCount("question");

		//create a question object and insert it into the database
		$question = new Question(generateUuidV4(), $this->VALID_QUESTIONID, $this->VALID_QUESTIONCONTENT, $this->VALID_QUESTIONVALUE);

		//insert the question into the database
		$question->insert($this->getPDO());

		//grab the question from the database
		$results = Question::getQuestionByContent($this->getPDO(), $question->getQuestionContent());
		$this->assertEquals($numRows +1, $this->getConnection()->getRowCount("question"));

		$pdoQuestion = $results[1];

		$this->assertEquals($pdoQuestion->getQuestionId());
		$this->assertEquals($pdoQuestion->getQuestion(), $question->getQuestion());
		$this->assertEquals($pdoQuestion->getQuestionContent());
		$this->assertEquals($pdoQuestion->getQuestionValue());
	}
 	/**
	 * try and grab the question by a question that does not exist
	 */
	public function testInvalidGetByQuestionContent(){
		$question = Question::getQuestionByQuestionContent($this->getPDO());
		$this->assertEmpty($question);
	}

	/**
	 * insert a question use getAll method, then enforce it meets expectation
	 */
	public function testGetAllQuestions(){
		$numRows = $this->getConnection()->getRowCount("question");

		//insert the question into the database
		$question = new Question(generateUuidV4(), $this->VALID_QUESTIONID, $this->VALID_QUESTIONCONTENT,$this->VALID_QUESTIONVALUE);

		//insert the question into the database
		$question->insert($this->getPDO());

		//grab the results from mySQL and enforce it meets expectations
		$results = Question::getAllQuestions($this->getPDO());
		$this->assertEquals($numRows +1, $this->getConnection()->getRowCount("question"));
		$this->assertCount(1, $results);
		//$this->assertContainsOnlyInstancesOf()

		//grab the results from the array and make sure it meets expectations
		$pdoQuestion = $results[0];
		//$this->assertEquals($pdoQuestion->getQuestionId(), $question->getQuestionId());
		$this->assertEquals($pdoQuestion->getQuestion(), $question->getQuestion());
		$this->assertEquals($pdoQuestion->getQuestionId(), $question->getQuestionId());
		$this->assertEquals($pdoQuestion->getQuestionContent(), $question->getQuestionContent());
		$this->assertEquals($pdoQuestion->getQuestionValue(), $question->getQuestionValue());
	}
}