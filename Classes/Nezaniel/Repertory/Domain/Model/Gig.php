<?php
namespace Nezaniel\Repertory\Domain\Model;

/*                                                                               *
 * This script belongs to the TYPO3 Flow package "Nezaniel.Repertory". 			 *
 *                                                                               *
 * It is free software; you can redistribute it and/or modify it under           *
 * the terms of the GNU General Public License, either version 3 of the          *
 * License, or (at your option) any later version.                               *
 *                                                                               *
 * The TYPO3 project - inspiring people to share!                                *
 *                                                                               */
use TYPO3\Flow\Annotations as Flow;

/**
 * The model representing a gig
 *
 * @Flow\Scope("session")
 */
class Gig {

	/**
	 * Tracks that have already been played during this gig
	 *
	 * @var array
	 */
	protected $playedTracks = [];

	/**
	 * Defines whether the gig has already been started
	 *
	 * @var boolean
	 */
	protected $started = FALSE;


	/**
	 * Starts a new gig
	 *
	 * @Flow\Session(autoStart = TRUE)
	 * @return void
	 */
	public function start() {
		$this->playedTracks = [];
		$this->started = TRUE;
	}

	/**
	 * @return boolean
	 */
	public function hasBeenStarted() {
		return $this->started;
	}

	/**
	 * @return array
	 */
	public function getPlayedTracks() {
		return $this->playedTracks;
	}

	/**
	 * @param string $nodeIdentifier
	 * @return void
	 */
	public function markTrackAsPlayed($nodeIdentifier) {
		if (array_search($nodeIdentifier, $this->playedTracks) === FALSE) {
			$this->playedTracks[] = $nodeIdentifier;
		}
	}

}