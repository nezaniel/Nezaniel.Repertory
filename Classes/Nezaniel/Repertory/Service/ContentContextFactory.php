<?php
namespace Nezaniel\Repertory\Service;

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
use TYPO3\Neos\Domain\Repository\DomainRepository;
use TYPO3\Neos\Domain\Repository\SiteRepository;
use TYPO3\Neos\Domain\Service\ContentContext;

/**
 * {@inheritdoc}
 *
 * @Flow\Scope("singleton")
 */
class ContentContextFactory extends \TYPO3\Neos\Domain\Service\ContentContextFactory {

	/**
	 * @var DomainRepository
	 * @Flow\Inject
	 */
	protected $domainRepository;

	/**
	 * @var SiteRepository
	 * @Flow\Inject
	 */
	protected $siteRepository;


	/**
	 * @param boolean $invisibleContentShown
	 * @return ContentContext
	 */
	public function createCurrent($invisibleContentShown = FALSE) {
		$contextProperties = [
			'invisibleContentShown' => $invisibleContentShown
		];
		$currentDomain = $this->domainRepository->findOneByActiveRequest();
		if ($currentDomain !== NULL) {
			$contextProperties['currentSite'] = $currentDomain->getSite();
			$contextProperties['currentDomain'] = $currentDomain;
		} else {
			$contextProperties['currentSite'] = $this->siteRepository->findFirstOnline();
		}
		return parent::create($contextProperties);
	}

}
