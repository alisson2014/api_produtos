<?php

declare(strict_types=1);

namespace Produtos\Tests\Integration\Dao;

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
        self::$pdo = ConnectionCreator::createMemoryConn();
        self::$categorieRepository = new CategorieRepository(self::$pdo);
    }

    public function testShouldUpdateCategorie(): void
    {     
        $id = self::$categorieRepository->add(new Categorie("teste"));
        $categorie = new Categorie("teste_update");
        $categorie->setId($id);

        $result = self::$categorieRepository->update($categorie);
        $dbCategorie = self::$categorieRepository->find($id);

        self::assertNotFalse($result);
        self::assertInstanceOf(Categorie::class, $dbCategorie);
        self::assertSame("teste_update", $dbCategorie->nomeCategoria);
        self::assertSame($id, $dbCategorie->id);
    }

    #[DataProvider("categories")]
    public function testShouldBeRemoveAllCategories(array $categories): void
    {
        foreach ($categories as $categorie) {        
            self::$categorieRepository->add($categorie);
        }

        $results = self::$categorieRepository->removeAll($categories);
        
        self::assertIsArray($results);
        self::assertContainsOnly("bool", $results);
        self::assertEmpty(self::$categorieRepository->all());
    }

    #[DataProvider("categories")]
    public function testShouldBeCreateThreeCategories(array $categories): void
    {
        foreach ($categories as $categorie) {        
            $result = self::$categorieRepository->add($categorie);
            self::assertNotFalse($result);
        }

        $dbCategories = self::$categorieRepository->all();
        self::assertCount(2, $dbCategories);
        self::assertContainsOnlyInstancesOf(Categorie::class, $dbCategories);
        self::assertSame("Alimentos", $dbCategories[0]->nomeCategoria);
        self::assertSame("Bebidas", $dbCategories[1]->nomeCategoria);
    }

    protected function tearDown(): void
    {
        self::$pdo->exec("DELETE FROM subcategoria");
    }

    public static function categories(): array
    {
        $categories = [
            new Categorie("Alimentos"), 
            new Categorie("Bebidas")
        ];

        return [
            [$categories]
        ];
    }
}