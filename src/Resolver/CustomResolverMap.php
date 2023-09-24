<?php

namespace App\Resolver;

use App\Entity\Measurement;
use App\Manager\MeasurementManager;
use App\Repository\MeasurementRepository;
use ArrayObject;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Overblog\GraphQLBundle\Resolver\ResolverMap;

class CustomResolverMap extends ResolverMap
{
    private EntityManagerInterface $entityManager;
    private MeasurementManager $measurementManager;

    /**
     * @param EntityManagerInterface $entityManager
     * @param MeasurementManager $measurementManager
     */
    public function __construct(EntityManagerInterface $entityManager, MeasurementManager $measurementManager)
    {
        $this->entityManager = $entityManager;
        $this->measurementManager = $measurementManager;
    }


    protected function map()
    {

        return [
            'RootQuery' => [
                self::RESOLVE_FIELD => function (
                    $value,
                    ArgumentInterface $args,
                    ArrayObject $context,
                    ResolveInfo $info
                ) {
                    /** @var MeasurementRepository $measurementRepository */
                    $measurementRepository = $this->entityManager->getRepository(Measurement::class);
                    return match ($info->fieldName) {
                        'measurement' => $measurementRepository->findOneBy(['id' => (int)$args['id']]),
                        'measurements' => $measurementRepository->findAll(),
                        'createMeasurement' => $this->measurementManager->createMeasurement(
                            $args['title'],
                            $args['abbreviation']
                        ),
                    };
                },
            ],

            'Mutation' => [
                self::RESOLVE_FIELD => function (
                    $value,
                    Argument  $args,
                    ArrayObject $context,
                    ResolveInfo $info
                ) {
                    return match ($info->fieldName) {
                        'createMeasurement' => $this->measurementManager->createMeasurement(
                            $args->offsetGet('input')['title'],
                            $args->offsetGet('input')['abbreviation']
                        ),
                    };
                },
            ],
        ];
    }
}