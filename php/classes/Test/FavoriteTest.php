<?php
namespace Edu\Cnm\Feedpast\Test;

use Edu\Cnm\feedpast\{Favorite, Organization, Volunteer};

// grab the class under scrunity
require_once(dirname(__DIR__,1) . "/autoload.php");

// grab the uuid generator
require_once(dirname(__DIR__,2) . "/lib/uuid.php");

/**
 * Full PhpUnit test for the favorite class.
 *
 * It is complete because ALL mySQL/PDO enabled methods
 * are tested for both valid and invalid inputs.
 *
 * @see Favorite
 * @author Jeffrey Brink <jeffreybrink@gmx.com
 * @author Dylan McDonald <dmcdonald@cnm.edu>
 **/

class FavoriteTest extends FeedPastTest {

	/**
	 * Post that was favorited, this is for foriegn Key relations
	 * @var Post $post
	 **/
	protected $post;

	/**
	 * Volunteer that created the Favorite, this is
	 * for the foriegn Key relations
	 * @var Volunteer $volunteer
	 **/
	protected $volunteer;

	/**
	 * valid hash to use
	 * @var $valid_Hash
	 */
	protected $valid_Hash;


	/**
	 * valid salt to use to create the profile object to own the text
	 * @var string $VALID_SALT
	 */
	protected $VALID_SALT;


	/**
	 * valid activationToken to create the post object to own the test
	 * @var string $VALID_ACTIVATION
	 */
	protected $VALID_ACTIVATION;


	/**
	 * create dependant objects before running each test
	 **/
	public final function setup() : void {
		// run the default setup() method first
		parent::setUp();

		// create a salt and hash for the mocked post
		$password = "abc123";
		$this->VALIDATE_SALT = bin2hex(random_bytes(32));
		$this->VALIDATE_HASH = hash_pbkdf2("sha512", $password, $this->VALIDATE_SALT, 26144);
		$this->VALIDATE_ACTIVATION = bin2hex(random_bytes(16));


		// create and insert the mocked post
		$this->post = new Post(generateUuidV4(), $this->organization->getOrganizationId(), null, "some-post-content", ??postEndDate??, "some-random-image-url", ??postStartDate??, "some-post-title");

		//create and insert the mocked volunteer
		$this->volunteer = new Volunteer(generateUuidV4(), null, null, "phillyonfire@burn.com",
			$this->VALID_HASH, "Ted Random", "719-367-9856", $this->VALID_SALT);
			
			$this->post->getPostId(), "PHPUnit like test passing");
		$this->volunteer->insert($this->getPDO());

		// calculate the date (just use the time the unit test was setup
		$this->VALID_FAVORITEDATE = new \DateTime();
		}

		/**
		 * test inserting a valid favorite and verifying that the actual mySQL data matches
		 **/

		public function testInsertValidFavorite() : void {
			// count the numbers of rows and save it for later
			$numRows = $this->getConnection()->getRowCount("favorite");

			// create a new Like and insert to into mySQL
			$favorite = new favorite($this->favorite->getPostId(), $this->volunteer->getVolunteerId(), $this->VALID_FAVORITEDATE);
			$favorite->insert($this->getPDO());


			// grab the data from mySQL and enforce the fields match our expectations
			$pdoFavorite = Favorite::getFavoriteByFavoritePostIdAndFavoriteVolunteerId($this->getPDO(),
				$this->post->getPostId(), this->getFavoriteVolunteerId());
			$this->assertEquals($numRows + 1, $this->getConnection()getRowCount("favorite"));
			$this->assertEquals($pdoFavorite->getFavoritePostId(), $this->post->getPostId());
			$this->assertEquals($pdoFavorire->getFavoriteVolunteerId(), $this->volunteer->getVolunteerId());
			//format the date too seconds since the beginning of time to avoid round off error
			$this->assertEquals($pdoFavorite->getFavoriteDate()->getTimeStamp(), $this->VALID_FAVORITEDATE->getTimestamp());
	}
		/**
		 * test creating a favorite and the deleting it
		 */
		public function testDeleteValidFavorite() : void {
			// count the rows and save for later
			$numRows = $this->getConnection()->getRowContent("favorite");

			// create a new Favorite and insert to into mySQL
			$favorite = new Favorite($this->post->getPostId(), $this->volunteer->getVolunteerId(), $this->VALID_FAVORITEDATE);
			$favorite->insert($this->getPDO());

			// delete the Favoritefrom mySQL
			$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("favorite"));
			$favorite->delete($this->getPDO());

			// grab the data from mySQL and enforce the Volunteer does not exist
			$pdoLike = Favorite::getFavoriteByFavoritePostIdAndFavoriteVolunteerId($this->getPDO(), $this->post->getPostId(),; 	$this->volunteer->getVolunteerId());
			$this->assertNull($pdoFavorite);
			$this->assertEquals($numRows, $this->getConnection()->getRowCount("favorite"));
			}

	/**
	 * test inserting a Favorite and regrabbing it from mySQL
	 **/
	public function testGetValidFavoriteByPostIDAndVolunteerId() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("favorite");

		// create a new Favorite and insert to into mySQL
		$favorite = new Favorite($this->post->getPostId(), $this->volunteer->getVolunteerId(), $this->VALID_FAVORITEDATE);
		$favorite->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoFavorite = Favorite::getFavoriteByFavoritePostIdAndFavoriteVolunteerId($this->getPDO(), $this->post->getPostId(), $this->volunteer
			->getVolunteerId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("favorite"));
		$this->assertEquals($pdoFavorite->getFavoritePostId(), $this->post->getPostId());
		$this->assertEquals($pdoFavorite->getFavoriteVolunteerId(), $this->volunteer->getVolunteerId());

		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoFavorite->getFavoriteDate()->getTimeStamp(), $this->VALID_FavoriteDATE->getTimestamp());
	}

	/**
	 * test grabbing a Favorite that does not exist
	 **/
	public function testGetInvalidFavoriteByPostIdAndVolunteerId() {
		// grab a volunteerid and post id that exceeds the maximum allowable volunteer id and post id
		$favorite = Favorite::getFavoriteByFavoritePostIdAndFavoriteVolunteerId($this->getPDO(), generateUuidV4(), generateUuidV4());
		$this->assertNull($favorite);
	}

	/**
	 * test grabbing a Favorite by volunteerid
	 **/
	public function testGetValidFavoritebyvolunteerId() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("favorite");
		// create a new Favorite and insert to into mySQL
		$favorite = new Favorite($this->post->getPostId(), $this->volunteer->getVolunteerId(), $this->VALID_FAVORITEDATE);
		$favorite->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Favorite::getFavoriteByFavoriteVolunteerId($this->getPDO(), $this->volunteer->getVolunteerId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("favorite"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\Feeding-Our-Past\\Favorite", $results);


		// grab the result from the array and validate it
		$pdoFavorite = $results[0];
		$this->assertEquals($pdoFavorite->getFavoritePostId(), $this->post->getPostId());
		$this->assertEquals($pdoFavorite->getFavoriteVolunteerId(), $this->volunteer->getVolunteerId());
		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoFavorite->getFavoriteDate()->getTimeStamp(), $this->VALID_FAVORITEDATE->getTimestamp());
	}

	/**
	 * test grabbing a Favorite by a volunteer id that does not exist
	 **/
	public function testGetInvalidFavoriteVolunteerId() : void {
		// grab a Volunteer id that exceeds the maximum allowable tweet id
		$Favorite = Favorite::getFavoriteByFavoriteVolunteerId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $Favorite);
	}


	/**
	 * test grabbing a Favorite by post id
	 **/
	public function testGetValidFavoriteByPostId() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("favorite");
		// create a new Favorite and insert to into mySQL
		$favorite = new Favorite($this->post->getPostId(), $this->volunteer->getVolunteerId(), $this->VALID_FAVORITEDATE);
		$favorite->insert($this->getPDO());


		// grab the data from mySQL and enforce the fields match our expectations
		$results = Favorite::getFavoriteByFavoritePostId($this->getPDO(), $this->post->getPostId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("favorite"));
		$this->assertCount(1, $results);
		// enforce no other objects are bleeding into the test
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\DFeeding-Our_Past\\Favorite", $results);


		// grab the result from the array and validate it
		$pdoFavorite = $results[0];
		$this->assertEquals($pdoFavorite->getFavoritePostId(), $this->post->getPostId());
		$this->assertEquals($pdoFavorite->getFavoriteVolunteerId(), $this->volunteer->getVolunteerId());
		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoFavorite->getFavoriteDate()->getTimeStamp(), $this->VALID_FAVORITEDATE->getTimestamp());
	}

	/**
	 *  * test grabbing a Like by a profile id that does not exist
	 **/

	public function testGetInvalidFavoritebyPostId() : void {
		// grab a volunteer id that exceeds the maximum allowable post id
		$favorite = Favorite::getFavoriteByFavoritePostId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $favorite);
	}
}
