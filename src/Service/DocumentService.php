<?php

namespace App\Service;

use App\Entity\ClientCase;
use App\Entity\Document;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Exception;

class DocumentService
{
    private const CREATE_DATA_KEY = [
        'name',
        'reference',
        'addedAt',
        'createdBy',
        'indice',
        'tag'
    ];

    public function __construct(
        private readonly Security $security,
        private readonly EntityManagerInterface $entityManager,
        private array $documents = []
    ) {
    }

    /**
     * @throws Exception
     */
    public function build(FormInterface $form, ClientCase $clientCase, array $files): void
    {
        $data = $this->extractFormData($form);

        // create document without file
        if (empty($files)) {
            // check if user try to create document without file
            $hasValue = $this->hasValue($data);

            if (!$hasValue) {
                return;
            }

            $document = $this->buildDocument($data);
            $clientCase->addDocument($document);
            $this->entityManager->persist($document);
            $this->documents[] = $document;
        }

        // create document with one or many files
        if (!empty($files)) {
            $fileCount = $this->getFileCount($files);
            foreach ($files as $file) {
                $document = $this->buildDocument($data, $file, $fileCount);
                $clientCase->addDocument($document);
                $this->entityManager->persist($document);
                $this->documents[] = $document;
            }
        }
    }

    public function hasChangeSet(ClientCase $clientCase): bool
    {
        if (!empty($this->documents)) {
            return true;
        }

        $unitOfWork = $this->entityManager->getUnitOfWork();
        foreach ($clientCase->getDocuments() as $document) {
            $originalData = $unitOfWork->getOriginalEntityData($document);

            if (
                $originalData['name'] !== $document->getName() ||
                $originalData['reference'] !== $document->getReference() ||
                $originalData['tag'] !== $document->getTag() ||
                $originalData['indice'] !== $document->getIndice() ||
                $originalData['addedAt'] !== $document->getAddedAt() ||
                $originalData['created_by_id'] !== $document->getCreatedBy()?->getId()
            ) {
                return true;
            }
        }

        return false;
    }

    private function extractFormData(FormInterface $form): array
    {
        $data = [];

        foreach (self::CREATE_DATA_KEY as $key) {
            $data[$key] = $form->get($key)->getData();
        }

        return $data;
    }

    private function hasValue(array $data): bool
    {
        return count(array_filter($data, fn($value) => !empty($value))) > 0;
    }

    private function getFileCount($files): int
    {
        return count($files);
    }

    /**
     * @throws Exception
     */
    private function buildDocument(
        array $data,
        ?UploadedFile $file = null,
        int $fileCount = 0
    ): Document {
        $document = new Document();

        $document
            ->setAddedBy($this->security->getUser())
            ->setIndice($data['indice'])
            ->setReference($data['reference'])
            ->setTag($data['tag'])
            ->setCreatedBy($data['createdBy'])
            ->setAddedAt($data['addedAt']);

        if ($file) {
            if (!empty($data['name']) && $fileCount < 2) {
                $document->setName($data['name']);
            } else {
                $document->setName(substr($file->getClientOriginalName(), 0, 255));
            }

        } else {
            if (empty($data['name'])) {
                throw new Exception("La désignation du document est obligatoire lors d'une creation sans fichier.");
            } else {
                $document->setName($data['name']);
            }
        }

        return $document;
    }
}