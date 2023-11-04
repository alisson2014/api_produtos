<?php

declare(strict_types=1);

namespace Tests\Integration\Dao;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Produtos\Action\Domain\Model\Categorie;
use Produtos\Action\Infrastructure\Persistence\ConnectionCreator;
use Produtos\Action\Infrastructure\Repository\CategorieRepository;

class DaoCategorieTest extends TestCase
{
    private static \PDO $pdo;
    private static CategorieRepository $categorieRepository;

    public static function setUpBeforeClass(): void
    {
        self::$pdo = ConnectionCreator::createConnection(true);
        self::$categorieRepository = new CategorieRepository(self::$pdo);
    }

    #[DataProvider("categories")]
    public function shouldBeCreateThreeCategories(array $categories): void
    {
        foreach ($categories as $categorie) {        
            $result = self::$categorieRepository->add($categorie);
            self::assertTrue($result);
        }

        $dbCategories = self::$categorieRepository->all(false);
        self::assertCount(3, $dbCategories);
    }

    public static function categories(): array
    {
        $alimentos = new Categorie("Alimentos");
        $bebidas = new Categorie("Bebidas");
        $doces = new Categorie("Doces");
        $categories = [$alimentos, $bebidas, $doces];

        return [
            [$categories]
        ];
    }
}