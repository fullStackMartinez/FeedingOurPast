<?php
/**
 * Created by PhpStorm.
 * User: Jeffrey
 * Date: 2/5/2018
 * Time: 3:42 PM
 */

namespace Edu\Cnm\FeedPast;
require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");
use Ramsey\Uuid\Uuid;


class Favorite {
	use ValidateDate;
	use ValidateUuid;
	/**
	 * id of the post that this favorite is for;  this is a foriegn key
	 * @var Uuid $favoritePostId ;
	 **/
	private $favoritePostId;

	/**
	 * id of the organization that made this favorite; this is a foriegn key
	 * @var Uuid $favoriteVolunteerId
	 **/

	private $favoriteVolunteerId;

	/**
	 * accessor method for favorite post id
	 *
	 * @return Uuid value of favorite post id
	 **/

	public function getFavoritePostId():
	Uuid {
		return ($this->favoritePostId);
	}


	/**
	 * mutator method for favorite post id
	 *
	 * @param string | Uuid
	$newFavoritePostId new value of favorite post id
	 * @throws \RangeException if
	 * $newFavoritePostId is not positive
	 * @throws \TypeError if
	 * $newFavoritePostId is not an integer
	 **/

	public function setFavoritePostId
	($newFavoritePostId): void {
		try {
			$uuid = self::validateUuid
			($newFavoritePostId);
		} catch
		(\InvalidArgumentException |
		\RangeException | \Exception |
		\TypeError $exception) {
			$exceptionType = get_class
			($exception);
			throw(new $exceptionType
			($exception->getMessage(), 0,
				$exception));
		}
		// convert and store the post id

		$this->favoritePostId = $Uuid;
	}
	/**
	 * accessor method for favorite volunteer id
	 *
	 * @return Uuid value of favoriteVolunteerId
	 **/

	public function getFavoriteVolunteerId()
		:Uuid{
		return($this->favoriteVolunteerId);
	}
	/**
	 * mutator method for favorite volunteer id
	 *
	 * @param string | Uuid
	 * $newFavoriteVolunteerId new value of favorite volunteer id
	 * @throws \RangeException if $newfavoriteVolunteer is not positive
	 * @throws \TypeError if $newFavoriteVolunteerID is not an integer
	 **/

	public function setFavoriteVolunteerId
	($newFavoriteVolunteerId) : void {
		try {
			$Uuid = self::validateUuid
			($newFavoriteVolunteerId);
		} catch
		(\InvalidArgumentException |
	\RangeException | \Exception |
	\TypeError $exception) {
			$exceptionType = get_class
			($exception);
			throw(new $exceptionType)
			($exception->getMessage(), 0,
				$exception);
		}
		// convert and store the volunteer id
		$this->favoriteVolunteerId = $Uuid;
	}
}
