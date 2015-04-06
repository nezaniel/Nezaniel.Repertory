<?php
namespace Nezaniel\Repertory\Service\DataSource;

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
use TYPO3\Flow\Persistence\Doctrine\PersistenceManager;
use TYPO3\Flow\Persistence\QueryInterface;
use TYPO3\Media\Domain\Model\Tag;
use TYPO3\Neos\Service\DataSource\AbstractDataSource;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * The data source for providing the TYPO3.Neos SelectBox editor with TYPO3.Media tags
 */
class TagDataSource extends AbstractDataSource {

	/**
	 * @var PersistenceManager
	 * @Flow\Inject
	 */
	protected $persistenceManager;

    /**
     * @var \TYPO3\Media\Domain\Repository\TagRepository
     * @Flow\Inject
     */
    protected $tagRepository;


    /**
     * @var string
     */
    static protected $identifier = 'nezaniel-repertory-tags';


    /**
     * Get data
     *
     * @param NodeInterface $node The node that is currently edited (optional)
     * @param array $arguments Additional arguments (key / value)
     * @return mixed JSON serializable data
     */
    public function getData(NodeInterface $node = NULL, array $arguments)
    {
        $result = array('' => new Tag(''));
        $tagQuery = $this->tagRepository->createQuery();
        $tagQuery->setOrderings(array(
            'label' => QueryInterface::ORDER_ASCENDING
        ));
        foreach ($tagQuery->execute() as $tag) {
            /** @var Tag $tag */
            $result[$this->persistenceManager->getIdentifierByObject($tag)] = $tag;
        }
        return $result;
    }

}