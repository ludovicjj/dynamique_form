<?php

namespace App\EventListener;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Document::class)]
#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Document::class)]
class DocumentListener
{
    public function preUpdate(Document $document, PreUpdateEventArgs $event): void
    {
        $tag = $document->getTag();
        if ($tag) {
            $formattedTag = $this->generateTags($tag);
            $event->setNewValue('tag', $formattedTag);
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