<?php

namespace DeepDiveDatingApp\DeepDiveDating\Tests;

use DeepDiveDatingApp\DeepDiveDating\User;

require_once (dirname(__DIR__) . "/autoload.php");
require_once (dirname(__DIR__, 2) . "/lib/uuid.php");


/**
 * unit test for the User Class
 * PDO methods are located in the User Class
 * @ see php/Classes/User.php
 * @author Kathleen Mattos
 */
class UserTest extends DeepDiveDatingAppTest {
			/**
			 * valid user profile
			 * @var User user
			 */
			protected $user = null;

			/**
			 * id for this user
			 * @var Uuid $VALID_USERID
			 */
			protected $VALID_USERID;

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
			protected $VALID_USERIPADDRESS = "192.0.2.0/24";

			/**
			 * create all dependent objects so that the test can run properly
			 */
			public final function setUp(): void {
			//run the default setUp() method first
			parent::setUp();
			$password = "abc123";
			$this->VALID_USERHASH = password_hash($password, PASSWORD2I, ["time_cost" => 384]);

			// create and insert
			}

	/**
			 * preform the actual insert method and enforce that it meets expectations I.E corrupted data is worth nothing
			 */

			public function testValidUserInsert() {
				$numRows = $this->getConnection()->getRowCount("user");

				//create the user object
				$user = new User (generateUuidV4(), $this->VALID_USERACTIVATIONTOKEN, $this->VALID_USERAGENT, $this->VALID_USERAVATARURL, $this->VALID_USERBLOCKED, $this->VALID_USEREMAIL, $this->VALID_USERHANDLE, $this->VALID_USERHASH, $this->VALID_USERIPADDRESS);
				//insert the user object
				$user->insert($this->getPDO());

				//grab the data from mySQL and enforce that it meets expectations
				$pdoUser = User::getUserByUserId($this->getPDO(), $user->getUserId());
				$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("user"));
				$this->assertEquals($pdoUser->getUserId(), $user->getUserId());
				$this->assertEquals($pdoUser->getUserActivationToken(), $user->getUserActivationToken());
				$this->assertEquals($pdoUser->getUserAgent(), $user->getUserAgent());
				$this->assertEquals($pdoUser->getUserAvatarUrl(), $user->getUserAvatarUrl());
				$this->assertEquals($pdoUser->getUserBlocked(), $user->getUserBlocked());
				$this->assertEquals($pdoUser->getUserEmail(), $user->getUserEmail());
				$this->assertEquals($pdoUser->getUserHandle(), $user->getUserHandle());
				$this->assertEquals($pdoUser->getUserHash(), $user->getUserHash());
				$this->assertEquals($pdoUser->getUserIpAddress(), $user->getUserIpAddress());
			}

			/**
			 * create a user object, update it in the database and then enforce that it meets expectations
			 */
			public function testValidUserUpdate() {
				//grab the number of rows and save it for the test
				$numRows = $this->getConnection()->getRowCount("user");

				//create the user object and then insert it
				$user = new User(generateUuidV4(), $this->VALID_USERACTIVATIONTOKEN, $this->VALID_USERAGENT, $this->VALID_USERAVATARURL, $this->VALID_USERBLOCKED, $this->VALID_USEREMAIL, $this->VALID_USERHANDLE, $this->VALID_USERHASH, $this->VALID_USERIPADDRESS);
				$user->insert($this->getPDO());

				//edit the user object then insert it back into the database
				$user->setUserHandle($this->VALID_USERHANDLE1);
				$user->update($this->getPDO());
				$pdoUser = User::getUserByUserId($this->getPDO(), $user->getUserId());

				$this->assertEquals($pdoUser->getUserId(), $user->getUserId());
				$this->assertEquals($pdoUser->getUserActivationToken(), $user->getUserActivationToken());
				$this->assertEquals($pdoUser->getUserAgent(), $user->getUserAgent());
				$this->assertEquals($pdoUser->getUserAvatarUrl(), $user->getUserAvatarUrl());
				$this->assertEquals($pdoUser->getUserBlocked(), $user->getUserBlocked());
				$this->assertEquals($pdoUser->getUserEmail(), $user->getUserEmail());
				$this->assertEquals($pdoUser->getUserHandle(), $user->getUserHandle());
				$this->assertEquals($pdoUser->getUserHash(), $user->getUserHash());
				$this->assertEquals($pdoUser->getUserIpAddress(), $user->getUserIpAddress());
			}

			/**
			 * create a user object, delete it, then enforce that it was deleted
			 */
			public function testValidUserDelete() {
				//grab the numbers of rows and save it for the test
				$numRows = $this->getConnection()->getRowCount("user");

				//create the User object
				$user = new User(generateUuidV4(), $this->VALID_USERACTIVATIONTOKEN, $this->VALID_USERAGENT, $this->VALID_USERAVATARURL, $this->VALID_USERBLOCKED, $this->VALID_USEREMAIL, $this->VALID_USERHANDLE, $this->VALID_USERHASH, $this->VALID_USERIPADDRESS);

				//insert the user object
				$user->insert($this->getPDO());

				//delete the user from the database
				$this->assertSame($numRows + 1, $this->getConnection()->getRowCount("user"));
				$user->delete($this->getPDO());

				//enforce that the deletion was successful
				$pdoUser = User::getUserByUserId($this->getPDO(), $user->getUserId());
				$this->assertNull($pdoUser);
				$this->assertEquals($numRows, $this->getConnection()->getRowCount("user"));
			}

			/**
			 * try to grab the user by a primary key that does not exist
			 */
			public function testInvalidGetByUserId() {
				//grab the user by an invalid key
				$user = User::getUserByUserId($this->getPDO(), DeepDiveDatingAppTest::INVALID_KEY);
				$this->assertEmpty($user);
			}

			/**
			 * insert a user object, grab it by the user handle and enforce that it meets expectations
			 */
			public function testValidGetUserByHandle() {

				$numRows = $this->getConnection()->getRowCount("user");
				//create a user object and insert it into the database
				$user = new User(generateUuidV4(), $this->VALID_USERACTIVATIONTOKEN, $this->VALID_USERAGENT, $this->VALID_USERAVATARURL, $this->VALID_USERBLOCKED, $this->VALID_USEREMAIL, $this->VALID_USERHANDLE, $this->VALID_USERHASH, $this->VALID_USERIPADDRESS);

				//insert the user into the database
				$user->insert($this->getPDO());

				//grab the user from the database
				$results = User::getUserByUserHandle($this->getPDO(), $user->getUserHandle());
				$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("user"));

				$pdoUser = $results[0];

				$this->assertEquals($pdoUser->getUserId(), $user->getUserId());
				$this->assertEquals($pdoUser->getUserActivationToken(), $user->getUserActivationToken());
				$this->assertEquals($pdoUser->getUserAgent(), $user->getUserAgent());
				$this->assertEquals($pdoUser->getUserAvatarUrl(), $user->getUserAvatarUrl());
				$this->assertEquals($pdoUser->getUserBlocked(), $user->getUserBlocked());
				$this->assertEquals($pdoUser->getUserEmail(), $user->getUserEmail());
				$this->assertEquals($pdoUser->getUserHandle(), $user->getUserHandle());
				$this->assertEquals($pdoUser->getUserHash(), $user->getUserHash());
				$this->assertEquals($pdoUser->getUserIpAddress(), $user->getUserIpAddress());
			}

			/**
			 * try and grab the user by a handle that does not exist
			 */
			public function testInvalidGetByHandle() {
				$user = User::getUserByUserHandle($this->getPDO(), "notLonelyPerson");
				$this->assertEmpty($user);
			}

			/**
			 * insert a user object, grab it by the email address, and enforce it meets expectations
			 */
			public function testValidGetUserByEmail() {

				$numRows = $this->getConnection()->getRowCount("user");

				//create a user object and insert it into the database
				$user = new User(generateUuidV4(), $this->VALID_USERACTIVATIONTOKEN, $this->VALID_USERAGENT, $this->VALID_USERAVATARURL, $this->VALID_USERBLOCKED, $this->VALID_USEREMAIL, $this->VALID_USERHANDLE, $this->VALID_USERHASH, $this->VALID_USERIPADDRESS);

				//insert the user into the database
				$user->insert($this->getPDO());

				//grab the user from the database
				$pdoUser = User::getUserByEmail($this->getPDO(), $user->getUserEmail());


				$this->assertEquals($pdoUser->getUserId(), $user->getUserId());
				$this->assertEquals($pdoUser->getUserActivationToken(), $user->getUserActivationToken());
				$this->assertEquals($pdoUser->getUserAgent(), $user->getUserAgent());
				$this->assertEquals($pdoUser->getUserAvatarUrl(), $user->getUserAvatarUrl());
				$this->assertEquals($pdoUser->getUserBlocked(), $user->getUserBlocked());
				$this->assertEquals($pdoUser->getUserEmail(), $user->getUserEmail());
				$this->assertEquals($pdoUser->getUserHandle(), $user->getUserHandle());
				$this->assertEquals($pdoUser->getUserHash(), $user->getUserHash());
				$this->assertEquals($pdoUser->getUserIpAddress(), $user->getUserIpAddress());
			}

			/**
			 * try to grab a user by an email that does not exist
			 */
			public function testInvalidGetByEmail() {
				$user = User::getUserByEmail($this->getPDO(), "liarlairpantsonfire@email.com");
				$this->assertEmpty($user);
			}

			/**
			 * insert a user, use getAll method, then enforce it meets expectations
			 */
			public function testGetAllUsers() {
				$numRows = $this->getConnection()->getRowCount("user");

				//insert the user into the database
				$user = new User(generateUuidV4(), $this->VALID_USERACTIVATIONTOKEN, $this->VALID_USERAGENT, $this->VALID_USERAVATARURL, $this->VALID_USERBLOCKED, $this->VALID_USEREMAIL, $this->VALID_USERHANDLE, $this->VALID_USERHASH, $this->VALID_USERIPADDRESS);

				//insert the user into the database
				$user->insert($this->getPDO());

				//grab the results from mySQL and enforce they meet expectations
				$results = User::getAllUsers($this->getPDO());
				$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("user"));
				$this->assertCount(1, $results);
				//$this->assertContainsOnlyInstancesOf()

				//grab the results from the array and make sure it meets expectations
				$pdoUser = $results[0];
				//$this->assertEquals($pdoUser->getUserId(), $user->getUserId());
				$this->assertEquals($pdoUser->getUser(), $user->getUser());
				$this->assertEquals($pdoUser->getUserActivationToken(), $user->getUserActivationToken());
				$this->assertEquals($pdoUser->getUserAgent(), $user->getUserAgent());
				$this->assertEquals($pdoUser->getUserAvatarUrl(), $user->getUserAvatarUrl());
				$this->assertEquals($pdoUser->getUserBlocked(), $user->getUserBlocked());
				$this->assertEquals($pdoUser->getUserEmail(), $user->getUserEmail());
				$this->assertEquals($pdoUser->getUserHandle(), $user->getUserHandle());
				$this->assertEquals($pdoUser->getUserHash(), $user->getUserHash());
				$this->assertEquals($pdoUser->getUserIpAddress(), $user->getUserIpAddress());
			}
}
