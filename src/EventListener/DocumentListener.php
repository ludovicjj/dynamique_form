<?php

namespace App\EventListener;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Document::class)]
#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Document::class)]
class DocumentListener
{
//    public function preUpdate(Document $document, PreUpdateEventArgs $event): void
//    {
//        if ($event->hasChangedField('tag')) {
//            $tag = $event->getNewValue('tag');
//            $formattedTag = $this->generateTags($tag);
//
//            $event->setNewValue('tag', $formattedTag);
//        }
//    }

    public function postUpdate(Document $document, PostUpdateEventArgs $event): void
    {
        $tag = $document->getTag();
        if ($tag) {
            $formattedTag = $this->generateTags($tag);
            $document->setTag($formattedTag);

            $manager = $event->getObjectManager();
            $manager->persist($document);
            $manager->flush();
        }
    }

    public function prePersist(Document $document): void
    {
        $tag = $document->getTag();
        if ($tag) {
            $formattedTag = $this->generateTags($tag);
            $document->setTag($formattedTag);
        }
    }

    private function generateTags(string $tag): string
    {
        $words = preg_split('/\s+/', trim($tag));

        foreach ($words as &$word) {
            $word = '#' . ltrim($word, '#');
        }

        return implode(' ', $words);
    }
}