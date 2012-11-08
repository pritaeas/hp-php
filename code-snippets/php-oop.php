<pre>
<?php
	// Test class KeyCard
	class KeyCard {
		// Public properties can be read and written by everyone.
		// In this case, the colour can be changed by anyone.
		public $colour;

		// Protected properties can be read and written by
		// the class itself, and any sub-class.
		// The information on the track is not visible by the outside world.
		protected $track;
		protected $version;

		// Private properties can be read and written by the class only.
		// The only way these properties could be read or written by somebody else,
		// is by exposing the information through a method.
		private $manufacturer;

		// This is the default definition for the constructor.
		function __construct() {
			// $this in a class method refers to the current class instance,
			// which is the object you will be working with at that point.

			// The initial colour will be white.
			$this->colour = 'white';

			// The manufacturer of this class will be me.
			$this->manufacturer = 'pritaeas';

			// The track will be empty when you create a new object of this class.
			$this->track = '';

			// The version of this class will be 1.
			$this->version = 1;
		}

		public function readManufacturer() {
			// Since the track property is not readable by anyone,
			// the readTrack function will return it's value.
			return $this->manufacturer;
		}

		public function readTrack() {
			// Since the track property is not readable by anyone,
			// the readTrack function will return it's value.
			return $this->track;
		}

		public function readVersion() {
			// Since the version property is not readable by anyone,
			// the readVersion function will return it's value.
			return $this->version;
		}

		public function writeTrack($newData) {
			// Since the track property is not writable by anyone,
			// the writeTrack function will set it's new value.
			$this->track = $newData;

			// Usually these functions perform additional validation.
			// If the new data is not valid, the track property should not be changed
			// and an exception should be raised, or the return value should be false.

			// Return true to indicate the writing of the track was succesful.
			return true;
		}
	}

	// Usage
	$keyCardObj = new KeyCard();

	// To access properties and methods from an object
	// you need to use the arrow notation (->).

	// These properties are visible trough their methods,
	// calling a method, like a function, uses parenthesis.
	echo "Keycard information:\n";
	echo sprintf("Manufacturer = [%s]\n", $keyCardObj->readManufacturer());
	echo sprintf("Version = [%d]\n", $keyCardObj->readVersion());

	// This property can be accessed,
	// like a variable, it is accessed without parenthesis.
	echo sprintf("Colour = [%s]\n", $keyCardObj->colour);

	// A public property can also be changed
	$keyCardObj->colour = 'red';

	// Show the new colour.
	echo sprintf("Colour = [%s]\n\n", $keyCardObj->colour);

	// Test class CardLock
	class CardLock {
		protected $version;

		function __construct() {
			$this->version = 1;
		}

		public function swipeCard(KeyCard $keyCard) {
			// Simulate a card swipe.
			// The parameter must be a KeyCard object.
			$track = $keyCard->readTrack();

			// We should now validate the track data,
			// and if it is valid open the lock.
			if ($this->validate($track)) {
				$this->openLock();
			}
			else {
				$this->showError();
			}
		}

		protected function openLock() {
			// Stub method that should open the lock,
			// for a certain period of time.
			echo "Lock open.\n";
		}

		protected function validate($data) {
			// Stub method that should validate the track data,
			// and see if this card is allowed access.

			// Any non empty track will be valid.
			return !empty($data);
		}

		protected function showError() {
			// Stub method that should blink the card lock's
			// indicator lights to show that the card was rejected.
			echo "Card not accepted.\n";
		}
	}

	// Usage
	$cardLockObj = new CardLock();

	// Let's swipe our keycard and see what happens.
	echo "Swipe keycard:\n";
	$cardLockObj->swipeCard($keyCardObj);

	// Since the keycard's track is empty by default,
	// the keycard is rejected.

	// Write some data.
	$keyCardObj->writeTrack('TRACKDATA');

	// Let's try again.
	echo "\nSwipe keycard:\n";
	$cardLockObj->swipeCard($keyCardObj);

	// Test class ChipCard
	// All public and protected properties and methods from the parent
	// will be available in this class too.
	class ChipCard extends KeyCard {
		protected $chip;

		function __construct() {
			// This call will call the parent's constructor,
			// inheriting all what is set.
			parent::__construct();

			// Since the parent's version is 1, increase it.
			// The version property is protected, so we can access it here.
			$this->version++;

			// Set the chip's default value to an empty string.
			$this->chip = '';
		}

		public function readChip() {
			return $this->chip;
		}

		public function writeChip($data) {
			$this->chip = $data;
			return true;
		}
	}

	// Usage
	$chipCardObj = new ChipCard();

	echo "\nChipcard information:\n";
	echo sprintf("Manufacturer = [%s]\n", $chipCardObj->readManufacturer());
	echo sprintf("Version = [%d]\n", $chipCardObj->readVersion());
	echo sprintf("Colour = [%s]\n", $chipCardObj->colour);

	// Test class ChipLock
	// All public and protected properties and methods from the parent
	// will be available in this class too.
	class ChipLock extends CardLock {
		// This property will control the new lock's behaviour.
		// When true it reads the chip, when false the track.
		public $chipMode;

		function __construct() {
			// This call will call the parent's constructor,
			// inheriting all what is set.
			parent::__construct();

			// Since the parent's version is 1, increase it.
			// The version property is protected, so we can access it here.
			$this->version++;

			$this->chipMode = true;
		}

		public function enterChip(ChipCard $chipCard) {
			if ($this->chipMode) {
				$data = $chipCard->readChip();
				if ($this->validate($data)) {
					$this->openLock();
				}
				else {
					$this->showError();
				}
			}
			else {
				// Return false if the mode is set to swipe
				$this->showError();
			}
		}

		public function swipeCard(KeyCard $keyCard) {
			if ($this->chipMode) {
				// Swipe not allowed in chip mode.
				$this->showError();
			}
			else {
				// Call the swipeCard method from the parent class.
				parent::swipeCard($keyCard);
			}
		}
	}

	// Usage
	$chipLockObj = new ChipLock();

	// Let's enter our chipcard and see what happens.
	echo "\nEnter chipcard:\n";
	$chipLockObj->enterChip($chipCardObj);

	// Since the keycard's chip is empty by default,
	// the chipcard is rejected.

	// Write some data.
	$chipCardObj->writeChip('CHIPDATA');

	// Let's try again.
	echo "\nEnter chipcard:\n";
	$chipLockObj->enterChip($chipCardObj);

	// If we set the mode to track,
	// then the chip must fail, and the old keycard will succeed
	$chipLockObj->chipMode = false;

	// Try the chipcard again
	echo "\nEnter chipcard:\n";
	$chipLockObj->enterChip($chipCardObj);

	// Swipe the keycard
	echo "\nSwipe keycard:\n";
	$chipLockObj->swipeCard($keyCardObj);
?>
</pre>