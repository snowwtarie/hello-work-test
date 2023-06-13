<?php

namespace App\Repository;

use App\Model\Offre;
use Exception;
use PDO;
use PDOException;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class OffreRepository
{
    private PDO $connection;
    private Serializer $serializer;

    public function __construct()
    {
        try {
            $this->connection = new PDO('sqlite:' . __DIR__ . '/../../database.db');
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->serializer = new Serializer(
                [
                    new ObjectNormalizer(null, null, null, new ReflectionExtractor()),
                    new ArrayDenormalizer()
                ],
                [new JsonEncoder()]
            );
        } catch (Exception $e) {
            throw $e->getMessage();
        }
    }

    /**
     * 
     * @return Offre[] 
     * @throws NotNormalizableValueException 
     */
    public function findAll(): array
    {
        $query = <<<SQL
            SELECT * FROM offre
            ORDER BY typeContrat, nomEntreprise
        SQL;
        $stmt = $this->connection->query($query);

        return $this->parse($stmt->fetchAll());
    }

    /**
     * 
     * @param Offre $offre 
     * @return bool 
     * @throws PDOException 
     */
    public function addOffre(Offre $offre): bool
    {
        $query = <<<SQL
            INSERT INTO offre VALUES (
                :id,
                :url,
                :intitule,
                :description,
                :typeContrat,
                :nomEntreprise,
                :dateCreation,
                :dateActualisation
            );
        SQL;

        $stmt = $this->connection->prepare($query);

        return $stmt->execute([
            'id' => $offre->getId(),
            'url' => $offre->getContact()->getUrlPostulation(),
            'intitule' => $offre->getIntitule(),
            'description' => $offre->getDescription(),
            'typeContrat' => $offre->getTypeContrat(),
            'nomEntreprise' => $offre->getEntreprise()->getNom(),
            'dateCreation' => $offre->getDateCreation(),
            'dateActualisation' => $offre->getDateActualisation(),
        ]);
    }

    /**
     * 
     * @param array $data 
     * @return mixed 
     * @throws NotNormalizableValueException 
     */
    private function parse(array $data)
    {
        return $this->serializer->denormalize(
            $data,
            'App\Model\Offre[]'
        );
    }
}
