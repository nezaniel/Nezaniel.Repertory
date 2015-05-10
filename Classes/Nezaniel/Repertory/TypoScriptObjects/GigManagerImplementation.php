<?php
namespace Nezaniel\Repertory\TypoScriptObjects;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Nezaniel.Repertory".    *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Nezaniel\Repertory\Domain\Model\Gig;
use Nezaniel\Repertory\Service\ContentContextFactory;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Http\Request;
use TYPO3\Neos\Domain\Service\ContentContext;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Search\Eel\SearchHelper;
use TYPO3\TYPO3CR\Search\Search\QueryBuilderInterface;
use TYPO3\TypoScript\TypoScriptObjects\TemplateImplementation;

/**
 * A TypoScript implementation to manage Gigs
 */
class GigManagerImplementation extends TemplateImplementation {

	/**
	 * @Flow\Inject("lazy=FALSE")
	 * @var ContentContextFactory
	 */
	protected $contentContextFactory;

	/**
	 * @Flow\Inject
	 * @var Gig
	 */
	protected $gig;


	/**
	 * @var ContentContext
	 */
	protected $contentContext;

	/**
	 * @var Request
	 */
	protected $request;


	/**
	 * @return void
	 */
	public function initializeObject() {
		$this->contentContext = $this->contentContextFactory->createCurrent();
		$this->request = $request = Request::createFromEnvironment();
	}


	/**
	 * @return NodeInterface
	 */
	public function getTrackProposal() {
		$this->ensureGigHasBeenStarted();


		if ($this->request->hasArgument('playedTrack')) {
			$this->gig->markTrackAsPlayed($this->request->getArgument('playedTrack'));
		}
		if ($this->request->hasArgument('restartGig')) {
			$this->gig->start();
		}

		$search = new SearchHelper();

		/** @var QueryBuilderInterface $query */
		$query = $search->query($this->contentContext->getCurrentSiteNode())
			->nodeType('Nezaniel.Repertory:Track')
			->exactMatch('state', 'complete')
			->limit(90000);


		$this->applyFilters($query);
		$this->excludeTracks($query);


		$suitableTracks = $query->execute();

		shuffle($suitableTracks);

		return current($suitableTracks);
	}
	

	/**
	 * @return void
	 */
	protected function ensureGigHasBeenStarted() {
		if (!$this->gig->hasBeenStarted()) {
			$this->gig->start();
		}
	}

	/**
	 * @param QueryBuilderInterface $query
	 * @return void
	 */
	protected function applyFilters(QueryBuilderInterface $query) {
		$allowedFilters = [
			'awesomeness',
			'emotionType'
		];
		$filters = [];
		foreach ($allowedFilters as $filter) {
			if ($this->request->hasArgument($filter) && $this->request->getArgument($filter) != '*') {
				$filters[$filter] = $this->request->getArgument($filter);
			}
		}
		if (count($filters) > 0) {
			foreach ($filters as $property => $value){
				$query->exactMatch($property, $value);
			}
		}
	}

	/**
	 * @param QueryBuilderInterface $query
	 * @return void
	 */
	protected function excludeTracks(QueryBuilderInterface $query) {
		$excludedTracks = [];
		if ($this->request->hasArgument('postponedTrack')) {
			$excludedTracks[] = $this->request->getArgument('postponedTrack');
		}
		if (count($this->gig->getPlayedTracks()) > 0) {
			$excludedTracks = array_merge($excludedTracks, $this->gig->getPlayedTracks());
		}
		if (count($excludedTracks) > 0) {
			$query->queryFilter('terms', ['__identifier' => $excludedTracks], 'must_not');
		}
	}

}