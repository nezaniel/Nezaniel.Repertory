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

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Media\Domain\Model\Asset;
use TYPO3\Media\Domain\Model\Tag;
use TYPO3\Media\Domain\Repository\AssetRepository;
use TYPO3\Media\Domain\Repository\TagRepository;
use TYPO3\TypoScript\TypoScriptObjects\Helpers\FluidView;
use TYPO3\TypoScript\TypoScriptObjects\TemplateImplementation;

/**
 *
 */
class TagBasedAssetListImplementation extends TemplateImplementation {

	/**
	 * @Flow\Inject
	 * @var TagRepository
	 */
	protected $tagRepository;

	/**
	 * @Flow\Inject
	 * @var AssetRepository
	 */
	protected $assetRepository;


	/**
	 * @param FluidView $view
	 * @return void
	 */
	public function initializeView(FluidView $view) {
		$tag = $this->tagRepository->findOneByLabel($this->tsValue('tag'));
		if ($tag instanceof Tag) {
			$view->assign('assets', $this->assetRepository->findByTag($tag));
		} else {
			$view->assign('assets', []);
		}
	}

}